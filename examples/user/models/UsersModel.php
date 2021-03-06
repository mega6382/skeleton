<?php

class UsersModel {
	protected $users;

	function __construct($locator=null) {
		$this->users = array(
			'user' => array(
					'userid' => 'user',
					'password' => 'user',
					'access' => '',
				),
			'admin' => array(
					'userid' => 'admin',
					'password' => 'admin',
					'access' => 'admin',
				),
			);
	}
	
	function findAuthorized($userid, $password) {
		if (array_key_exists($userid, $this->users)) {
			if ($this->users[$userid]['password'] == $password) {
				return $this->users[$userid];
			}
		}
		return array();
	}

}

?>