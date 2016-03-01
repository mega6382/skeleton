# Syntax Standards #

This document gives the required standards for committing to the repository.  This is a useless piece of code demonstrating the various standards to be followed:

```
<?php
/**
 * Class.php
 * 
 * @package	A
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 * @author	John Doe, Jane Doe
 */

/**
 * A_Class
 * 
 * A class that does cool stuff.
 */
class A_Class extends A_Nother_Class implements A_SomeInterface
{

	/**
	 * Represents an inactive state.  It influences how blah blah....
	 */
	const STATE_INACTIVE = 1;
	
	/**
	 * Represents an active statement.  When set as the current state, something...
	 */
	const STATE_ACTIVE = 2;
	
	/**
	 * Singleton instance
	 */
	public static $singleInstance = new A_Class();
	
	/**
	 * Database connection object
	 */
	public $dbConnection;
	
	/**
	 * User name
	 */
	protected $userName;
	
	// True if this class has been read
	private $readYet = false;
	
	/**
	 * Contstructor
	 * 
	 * @param string $userId The database ID of the user to load
	 * @param bool $lazyLoad Wait to load until read
	 */
	public function __construct($userId, $lazyLoad=false)
	{
		$row = $dbConnection->queryForId($userId);
		if (!$lazyLoad) {
			$this->userName = $row['userName'];
		}
	}
	
	/**
	 * Get the user name
	 * 
	 * @return string
	 */
	public function getUserName()
	{
		return $this->userName;
	}
	
	/**
	 * Reverse the letters in the user name
	 */
	protected function reverseUserName()
	{
		$this->userName = strrev($this->userName);
	}
	
	/*
	 * This comment is unnecessary, but can be useful.  Notice it's a normal comment block, not a PHPDoc comment.
	 */
	private function doNothing()
	{
		if (
			$something == true
			&& $somethingElse = false
			|| (
				$something == false
				&& $foo != 1
			)
		) {
			$this->doSomething('hello', $foo);
			$this->child
				->doSomething()
				->doSomethingElse();
			while ($foo < 40) {
				$foo++;
			}
			$i = 0;
			do {
				$foo = $this->dbConncetion->queryForId($i++);
			} while ($foo == null);
			
			switch ($foo) {
				case 1:
					$this->selfDestruct();
					break;
				case 2:
					$this->implode();
					break;
			}
		} elseif ($something == 'foo') {
			$this->implode();
		} else {
			$this->explode();
		}
	}

}

```

# The Breakdown #

  * Each page should start with a full opening PHP tag (<?php)
  * The top of the page has a document-level PHPDoc header, containing at least the file name, @package, @license, @link, and @author.
  * After a single empty line is the class-level PHPDoc comment, containing at least the class name and a description.
  * Immediately following is the class.  All extends and implements keywords are on the same line.  The opening hash is on the next line, at the same indentation.
  * Following that is an empty line at the same indentation.
  * Tabs should **always be used for indentation**, and should be used **for indentation only**.
  * Class constants come first.
  * Next is all class properties.  They should be grouped and sorted according to their type (static before instance, public before protected before private).  Groups of a certain type should be separated by an empty line.
  * Method/property keywords should be in this order: exposure (static?) variablename.  (e.x. public static $variable or public static $variable = 'hello').
  * All class properties should be formatted in camelCase (e.x. $someVariable, $someOtherVariable).
  * Private property variables should be prefixed with an underscore.
  * All empty lines in the code should be indented _as if there was code present_.
  * The constructor is the first method.
  * There should be parentheses directly after the method name, with no spacing between them.
  * Method arguments should be separated by a comma and a space, in that order.
  * Default arguments should _not_ be spaced (e.x. $arg='hello').
  * All method names should be clear and descriptive of what the method does.  They must be intuitive, and use **full words**.
  * The position of the opening hash relative to the method is the same as a class (down one line, same indentation).
  * Control structures should have a space between the keyword (e.x. if, while, for, switch, etc.) and the parentheses, spacing between logical operators (e.x. ||, &&, etc.) and expressions, and a space between the closing parentheses and the opening hash.
  * Logical NOT (!) should not have a space between it and the thing being "NOT"ed.
  * If the logic is too long, it can be wrapped and put on the next line, indented one level.
  * Method PHPDoc comments should give a clear description of the method, and list all parameters and the return value type (and optionally an explanation).
  * Switch clauses should have cases indented one level, and their contents indented another level.  The break should be at the same level as the contents.
  * When calling methods or language constructs (i.e. array()), there should not be a space between the name and the parentheses.
  * When fluently calling methods on an object, put the object and one line, and subsequent method invocations on the next lines, indented by one level.
  * At the end of the class (before the closing hash brace) there should be one last un-indented line.
  * Place one more empty line after the closing hash.
  * Do not put a closing tag (?>) at the end of the file, to impede unintentional white-space output.

This seems complete for now.  These are up for dispute of course; but if you _do_ dispute them, _you_ get to apply the changes :)