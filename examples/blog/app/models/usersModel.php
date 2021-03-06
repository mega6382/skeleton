<?php


class usersModel extends A_Model {
	
	protected $errorMsg = array();
		
	protected $dbh = null;
	
	protected $hashalgo = null;
	
	protected $hashoptions = null;
	
	public function __construct($dbh){
		
		$this->dbh = $dbh;
		
		$this->addField(new A_Model_Field('id'));
		$this->addField(new A_Model_Field('firstname'));
		$this->addField(new A_Model_Field('lastname'));
		$this->addField(new A_Model_Field('username'));
		$this->addField(new A_Model_Field('password'));
		$this->addField(new A_Model_Field('email'));
		$this->addField(new A_Model_Field('active'));
		$this->addField(new A_Model_Field('access'));

		$this->addRule(new A_Rule_Numeric('id', 'invalid ID'), 'id');
		$this->addRule(new A_Rule_Regexp('/^[A-Za-z ]+$/', 'firstname', 'The first name is not valid'), 'firstname'); 
		$this->addRule(new A_Rule_Regexp('/^[0-9a-zA-Z\-\ \\\']+$/', 'lastname', 'The last name is not valid'), 'lastname'); 
		$this->addRule(new A_Rule_Length(3, 15, 'username', 'The username must be between 3 to 25 characters'), 'username'); 
		$this->addRule(new A_Rule_Regexp('/^[A-Za-z0-9]+$/D', 'username', 'The username is not valid'), 'username');		
		$this->addRule(new A_Rule_Regexp('/[0-9a-zA-Z\-\_\@\.]+/', 'password', 'The password is not valid'), 'password'); 
		$this->addRule(new A_Rule_Email('email', 'This is not a valid email adress'), 'email');
		$this->addRule(new A_Rule_Regexp('[^01]', 'active', 'active'), 'active');
		$this->addRule(new A_Rule_Regexp('/[~0-9a-zA-Z\-\_\|]/', 'access', 'User access'), 'access'); 
		
		// create a Gateway style datasource for the Model
		$this->datasource = new A_Db_Tabledatagateway($this->dbh, 'blog_users', 'id');
		// set the field names for the Gateway to fetch
		$this->datasource->columns($this->getFieldNames());
		
		// set up hash options
		$this->hashalgo = PASSWORD_BCRYPT;
		$this->hashoptions = array('cost' => 7);
	}
	
	public function getStatus(){
		return $this->status;
	}
	
	public function save(){
		// if doesn't exist yet create
		if(!$this->get('id')){
			// insert new 
		} else {
			// update
		}
	}
	
	public function findAll(){
		$this->errorMsg = array();;
		$result = $this->datasource->find(array('active'=>1));
		if($result->numRows() > 0) {
			return $result->fetchAll();
		} else {
			return array();
		}
	}
	
	public function findAuthorlist(){
		$this->errorMsg = array();;
		$result = $this->datasource->columns('id,username')->find(array('active'=>1 ));
		if($result->numRows() > 0) {
			return $result->fetchAll();
		} else {
			return array();
		}
	}
	
	public function find($id){
		$result = $this->datasource->find(array('id'=>$id));
		if($result->numRows() > 0) {
			return $result->current();
		} else {
			return array();
		}
	}
	
	public function findByEmail($email) {
		$result = $this->datasource->find(array('email'=>$email));
		if($result->numRows() > 0) {
			return $result->current();
		} else {
			return array();
		}
	}
	
	public function delete($id){}	
		
	public function login($username, $password) {
		$this->errorMsg = array();

		$result = $this->datasource->find(array('username'=>$username, 'active'=>1));
		
		if($result->numRows() > 0) {
			$row = $result->current();
			if ($row['username'] == $username) { 
				if (password_verify($password, $row['password'])) { 
					if (password_needs_rehash($row['password'], $this->hashalgo, $this->hashoptions)) { 
						$hash = password_hash($password, $this->hashalgo, $this->hashoptions);
						$this->updateUser( array('password' => $hash) , $row['id']);
					}
					return $row;
				} else {
					$this->errorMsg[] = 'Username and/or password are not correct.';
				}
			} else {
				$this->errorMsg[] = 'Username and/or password are not correct.';
			}
		} else {
			$this->errorMsg[] = $this->datasource->getErrorMsg();
		}
		return array();
	}
	
	public function loginErrorMsg() {
		return $this->errorMsg;
	}

	public function isUsernameAvailable($username){ 
		$result = $this->datasource->find(array('username'=>$username));
		if($result->numRows() > 0) {
			return false;
		} else {
			return true;
		}
	}
	
	public function isEmailAvailable($email){	
		$result = $this->datasource->find(array('email'=>$email));
		if($result->numRows() > 0) {
			return false;
		} else {
			return true;
		}
	}
	
	public function usernameMatchesEmail($username, $email){ 
		$result = $this->datasource->find(array('username'=>$username ,'email'=>$email));
		if($result->numRows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	public function isAccountActivated($username, $email){
		$result = $this->datasource->find(array('username'=>$username ,'active'=>1));
		if($result->numRows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	public function isPasswordCorrect($username, $password){
		$result = $this->datasource->find(array('username'=>$username, 'active'=>1));
		if($result->numRows() > 0) {
			$row = $result->current();
			if (password_verify($password, $row['password'])) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	public function createActivationkey(){
		return md5(uniqid(rand(), true));
	}

	public function insertUser($username, $password, $email, $activationkey){
		$user_hash = password_hash($password, $this->hashalgo, $this->hashoptions);
		$this->datasource->insert(array(
								'username'		=>$username,
								'email'			=>$email,
								'password'		=>$user_hash, 
								'activationkey'	=>$activationkey
								));
	}
	
	public function updateUser( $data = array(), $id ) {

		$result = $this->datasource->update($data, array('id'=>$id));

		if($result->numRows() > 0) {
			$this->errorMsg[] = 'User data updated';
			return true;
		} else {
			$this->errorMsg[] = 'User data could not be updated';
			return false;
		}
	}
	
	public function activate($activationkey){
		if(!empty($activationkey)){
			
			// Is there a row with this activationkey
			$result = $this->datasource->find(array('activationkey'=>$activationkey));

			// If there is activate the acount
			if($result->numRows() > 0) {
				$row = $result->current();
				// set to active and remove key
				$result = $this->datasource->update(array('active'=>'1', 'activationkey'=>''), array('id'=>$row['id']));
				if($result->numRows() > 0) {
					$this->errorMsg[] = 'Your account is now activated. ';
					return true;
				}
			}
			// something went wrong..
			$this->errorMsg[] = 'We could not activate the account. ';
			
		} else {
			// User is on activate page but the activation key is missing. What to show?
			$this->errorMsg[] = 'The activation key is missing. ';
		}
		return false;
	}
	
	public function insertResetkey($resetkey, $id) {
		$data = array('resetkey'=>$resetkey);
		$result = $this->datasource->update($data, array('id'=>$id));
		if($result->numRows() > 0) {
			$this->errorMsg[] = 'User resetkey inserted';
			return true;
		} else {
			$this->errorMsg[] = 'User resetkey could not be inserted';
			return false;
		}
	}
	
	public function findResetkey($resetkey){
		$result = $this->datasource->find(array('resetkey'=>$resetkey));
		if($result->numRows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	public function resetPassword($password, $resetkey){
		$result = $this->datasource->find(array('resetkey'=>$resetkey));
		if($result->numRows() > 0) {
			$user_hash = password_hash($password, $this->hashalgo, $this->hashoptions);
			// insert new password and delete resetkey
			$result = $this->datasource->update(array('password'=>$user_hash, 'resetkey'=>''), array('resetkey'=>$resetkey));
			return true;
		} else {
			$this->errorMsg[] = 'Password could not be reset';
			return false;
		}
	}
	
}