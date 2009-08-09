<?php

class example extends A_Controller_Action {

	function index($locator) {
		$view = $this->_load()->view();
		$view->set('title', 'This is the title set in the controller');	
		$view->set('colors', array(
								array('id'=>'1', 'color'=>'Red'), 
								array('id'=>'2', 'color'=>'Blue'), 
								array('id'=>'3', 'color'=>'Green'), 
								));	
		echo $view->render();
	}
	
	function simpletemplate($locator) {
		$view = $this->_load()->view();
		$view->set('title', 'This is the title set in the controller');	
		echo $view->render();
	}
	
}

