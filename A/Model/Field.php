<?php
/**
 * Encapsulate a field/column within a Model 
 * 
 * @package A_Model 
 */

class A_Model_Field {
	// from Input Controller
	public $name = '';
	public $value = '';
	public $required = 0;
	public $filters = null;
	public $rules = null;
	public $errorMsg = array();
	public $error = false;
	// from Form Controller
	public $default = '';
	public $source_name = '';
	public $save = true;
	
	public function __construct($name) {
		$this->name = $name;
	}
	
	public function setDefault($value) {
		$this->default = $value;
		return $this;
	}
	
	public function setSourceName($value) {
		$this->source_name = $value;
		return $this;
	}
	
	public function setSave($value=true) {
		$this->save = $value;
		return $this;
	}

	public function addFilter($filter) {
		// assign one or more filters
		if (is_array($filter)) {
			foreach ($filter as $f) {
				$this->filters[] = $f;
			}
		} else {
			$this->filters[] = $filter;
		}
		return $this;
	}
	
	public function addRule($rule) {
		// assign one or more rules
		if (is_array($rule)) {
			foreach ($rule as $r) {
				$this->rules[] = $r;
			}
		} else {
			$this->rules[] = $rule;
		}
		return $this;
	}
	
	public function getValue() {
		return $this->value;
	}
	
	public function setValue($value) {
		$this->value = $value;
		return $this;
	}
	
	public function getErrorMsg($separator=null) {
		if (($separator === null) || ! is_array($this->errorMsg)) {
			return $this->errorMsg;
		} else {
			return implode($separator, $this->errorMsg);
		}
	}
	
	public function setError($value=array()) {
		$this->errorMsg = array_merge($this->errorMsg, $value);
		$this->error = true;
		return $this;
	}
	
	public function isError() {
		return $this->error;
	}
	
/*
	public function setRequired($value=1) {
		$this->required = $value;
		return $this;
	}
	
	public function isRequired() {
		return $this->required;
	}
*/
		
	public function isValid() {
		return ! $this->error;
	}

}
