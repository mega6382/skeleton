<?php

class FormView extends A_Http_View {
	
	protected $values = array();
	protected $errmsgs = array();
	
	function __construct($locator) {
        parent::__construct($locator);
	}

	function setValues($values) {
		$this->values = $values;
	}
	
	function setErrorMsg($errmsgs) {
		$this->errmsgs = $errmsgs;
	}
	
	function render() {
		$layout = $this->_load()->template('template');
		$layout->set('values', $this->values);
		$layout->set('errmsg', $this->errmsgs);
		$content = $layout->render();
		echo $content;
	}

}