<?php

class Rule_RegexpTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testRuleRegexp() {
		$dataspace = new A_Collection();

		$rule = new A_Rule_Regexp('/123$/', 'test', 'error');

		$dataspace->set('test', 'test123');
		$result = $rule->isValid($dataspace);
		$this->assertTrue($result);
		
		$dataspace->set('test', 'test234');
 		$result = $rule->isValid($dataspace);
		$this->assertFalse($result);
	}
	
}
