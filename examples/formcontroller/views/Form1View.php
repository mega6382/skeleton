<?php
#include 'Template.php';
include 'URL.php';

class Form1View {

function Form1View () {
}

	function init($locator) {
		echo 'Form1View: InitHandler: STATE INIT<br/>';
		$controller = $locator->get('Controller');
		
		$param1 = $controller->getField('field1');
		$param2 = $controller->getField('field2');
		$param3 = $controller->getField('field3');
		$param4 = $controller->getField('field4');
		$param1->setValue('15');
		$param2->value = 'init';
		$param3->value = 'init';
		$param4->value = 'init';
		include 'templates/example_form.php';
	}
	
	function submit($locator) {
		echo 'Form1View: SubmitHandler: STATE SUBMITTED<br/>';
		$controller = $locator->get('Controller');
	
		$param1 = $controller->getField('field1');
		$param2 = $controller->getField('field2');
		$param3 = $controller->getField('field3');
		$param4 = $controller->getField('field4');
		include 'templates/example_form.php';
	}
	
	function done($locator) {
		$response = $locator->get('Response');
		$url = new URL('action');
		$response->setRedirect($url->getURL('foo'));
#		echo 'Form1View: DoneHandler: STATE DONE<br/>';
	}
	
}

