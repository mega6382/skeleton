<?php
error_reporting(E_ALL);
require 'config.php';
require dirname(__FILE__) . '/../../A/Locator.php';

#require_once 'A/Http/Request.php';
#require_once 'A/Http/Response.php';
#require_once 'A/Controller/Mapper.php';
#require_once 'A/Controller/Front.php';
#require_once 'A/Controller/Front/Injector.php';
#require_once 'A/Controller/Front/Premethod.php';

// create Registry/Loader and initialize autoloading
$Locator = new A_Locator();
$Locator->autoload();

$Response = new A_Http_Response();
$Locator->set('Request', new A_Http_Request());
$Locator->set('Response', $Response);

$DefaultAction = array('', 'home', 'index');
$ErrorAction = array('', 'error', 'index');
$SecurityAction = array('', 'security-default', 'index');
$NoAction = 0;

$Mapper = new A_Controller_Mapper('', $DefaultAction);
#$Mapper->setParams('action', '');		// add this line to run 0.3.x code 
#$Mapper->setDefaultMethod('execute');	// add this line to run 0.4.x and 0.3.x code

$Controller = new A_Controller_Front($Mapper, $ErrorAction);
$Controller->addPreFilter(new A_Controller_Front_Premethod('denyAccess', $SecurityAction, $Locator));
$Controller->addPreFilter(new A_Controller_Front_Premethod('forceError', $NoAction, $Locator));
$Controller->addPreFilter(new A_Controller_Front_Injector('_response', $Response));
$Controller->run($Locator);

$Response->out();

class InjectResponseFilter {
	protected $locator;
	
	function InjectResponseFilter($locator) {
		$this->locator = $locator;
	}
	
	function run($controller) {
		$controller->_response = $this->locator->get('Response');
	}
}