<?php
/**
 * Load.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Controller_Helper_Load
 *
 * Provides class loading and instantiation within the application directory
 *
 * @package A_Controller
 */
class A_Controller_Helper_Load
{

	protected $locator;
	protected $mapper;
	protected $parent;
	protected $paths = array(
		'app'=>'',
		'module'=>'',
		'controller'=>'',
		'action'=>'',
	);
	protected $dirs = array(
		'controller'=>'controllers/',
		'event'=>'events/',
		'helper'=>'helpers/',
		'model'=>'models/',
		'template'=>'templates/',
		'view'=>'views/',
		'class'=>'',
	);
	protected $action = null;
	protected $method = null;
	protected $suffix = array(
		'controller'=>'',
		'model'=>'Model',
		'view'=>'View',
		'helper'=>'Helper',
		'class'=>'',
	);
	protected $rendererTypes = array('view', 'template');
	protected $scope;
	protected $scopePath;
	protected $responseName = '';
	protected $renderClasses = array(
		'php' => 'A_Template_Include',
		'xml' => 'A_Template_Include',
		'json' => 'A_Template_Include',
		'js' => 'A_Template_Include',
		'html' => 'A_Template_Strreplace',
		'txt' => 'A_Template_Strreplace',
	);
	protected $renderClass = 'A_Template_Include';
	protected $renderExtension = 'php';
	protected $responseSet = false;
	protected $errorMsg = array();

	public function __construct($locator, $parent, $scope=null)
	{
		$this->locator = $locator;
		if ($locator) {
			$mapper = $locator->get('Mapper');
			if ($mapper) {
				$this->setMapper($mapper);
			} else {
				$this->errorMsg[] =  "No Mapper to provide paths. ";
			}
		} else {
			$this->errorMsg[] =  "No Locator: Action Controller constructor may not be calling self::__construct($locator). ";
		}
		$this->parent = $parent;
		$this->load($scope);
	}

	/**
	 * Scopes are:
	 * global /app/
	 * module /app/$module/
	 * controller /app/$module/$type/$controller/
	 * action /app/$module/$type/$controller/$action/
	 *
	 * @param A_Controller_Mapper
	 * @return $this
	 */
	public function setMapper($mapper)
	{
		if ($mapper) {
			$this->mapper = $mapper;
			$this->action = $mapper->getClass();
			$this->method = $mapper->getMethod();
		}
		return $this;
	}

	public function setPath($name, $path, $relative_name='')
	{
		$path = $path ? (rtrim($path, '/') . '/') : '';		// add trailing dir separator
		if ($relative_name) {
			$this->paths[$name] = $this->paths[$relative_name] . $path;
		} else {
			$this->paths[$name] = $path;
		}
		return $this;
	}

	public function setDir($name, $dir)
	{
		$this->dirs[$name] = $dir ? (rtrim($dir, '/') . '/') : '';
		return $this;
	}

	public function setRenderClass($name, $ext='php')
	{
		$ext = ltrim($ext, '.');
		$this->renderClasses[$ext] = $name;
		return $this;
	}

	public function setSuffix($name, $suffix)
	{
		$this->suffix[$name] = $suffix;
		return $this;
	}

	/**
	 * Get error messages
	 *
	 * @param string $separator Delimiter to place between error messages.  If empty, an array of error messages is returned.
	 * @return string|array
	 */
	public function getErrorMsg($separator="\n")
	{
		if ($separator) {
			return implode($separator, $this->errorMsg);
		}
		return $this->errorMsg;
	}

	public function response($name='')
	{
		$this->responseSet = true;
		$this->responseName = $name;
		return $this;
	}

	public function load($scope=null, $target=null)
	{
		if (is_array($scope)) {
			$scope = $scope[0];
		}
		$overrides = array();
		if (! isset($this->paths[$scope])) {
			if ($scope && (substr($scope, 0, 7) == 'module=')) {
				$overrides['dir'] = substr($scope, 7);
			}
			$scope = 'module';	 // the default setting e.g., "/app/module/models"
		}
		$this->scope = $scope;
		if (isset($this->mapper)) {
			$paths = $this->mapper->getPaths('%s', $overrides);	// get paths array with sprintf placeholder
			$this->scopePath = $paths[$scope];
		}
		$this->responseSet = false;		// reset response mode to off for each call
		return $this;
	}

	public function __call($type, $args)
	{
		$obj = null;
		// is this a defined type of subdirectory
		if (isset($this->dirs[$type])) {
			// get class name parameter or use action name or use method name in controller scope for $type/$controller/$action.php
			$class = isset($args[0]) && $args[0] ? $args[0] : ($this->scope == 'controller' && $this->method ? $this->method : $this->action);
			// has a . in name is extension so remove for class name and use ext
			$length = strpos($class, '.');
			if ($length) {
				// get extension to use below
				$ext = substr($class, $length + 1);
				$extPassed = true;
				$class = substr($class, 0, $length);
			} else {										// no extension
				$ext = $type == 'template' ? $this->renderExtension : '.php';
				$extPassed = false;
				if (isset($this->suffix[$type])) {
					$length = strlen($this->suffix[$type]);
					// if a suffix is defined and the end of the action name does not contain it -- append it
					if ($length && (substr($class, -$length) != $this->suffix[$type])) {
						$class .= $this->suffix[$type];
					}
				}
			}

			// insert type path into scope path
			if ($this->scopePath) {
				$path = str_replace('%s', $this->dirs[$type], $this->scopePath);
			} else {
				$path = $this->dirs[$type];		// just in case no scopePath
			}

			// helpers take a parent instance as the parameter
			if ($type == 'helper') {
				if (count($args) > 1) {
					// add parent as arg[1]
					$name = array_shift($args);
					array_unshift($args, $name, $this->parent);
				} else {
					$args[1] = $this->parent;
				}
			}

			// templates are a template filename, not a class name -- need to load/create template class
			if ($type == 'template') {
				// lookup the renderer by extension, if given
				$path_parts = pathinfo($class);
				// if dir in name the add to path
				if ($path_parts['dirname'] != '.') {
					$path .= trim($path_parts['dirname'], '/') . '/';
				}
			}
			// generic class
			if ($type == 'class') {
				// lookup the renderer by extension, if given
				$path_parts = pathinfo($class);
				// if dir in name the add to path
				if ($path_parts['dirname'] != '.') {
					$path .= trim($path_parts['dirname'], '/') . '/';
				}
				$class = $path_parts['basename'];
			}
			if ($type == 'template') {
		        // fix by thinsoldier for NT servers that do not return filename
				$class = isset($path_parts['filename']) ? $path_parts['filename'] : $path_parts['basename'];
				if (!$extPassed) {
					// add in path separators if passed with route style rather than filename with ext
					$class = str_replace(array('_','-'), array('/','_'), $class);
				}

				$obj = new $this->renderClasses[$ext]("$path$class.$ext");
				// if 2nd param is array then use it to set template values
				if (isset($args[1]) && is_array($args[1])) {
					foreach ($args[1] as $key => $val) {
						$obj->set($key, $val);
					}
				}
			} elseif ($this->locator) {
				if ($this->locator->loadClass($class, $path)) { // load class if necessary
					switch (count($args)) {
					case 1:
						$obj = new $class($this->locator);		// no args pass Locator
						break;
					case 2:
						$obj = new $class($args[1]);			// one arg
						break;
					default:
						$refObj = new ReflectionClass($class);	// array as param list
						$obj = $refObj->newInstanceArgs(array_slice($args, 1));
					}
				} else {
					$this->errorMsg[] = "\$this->_load('{$this->scope}')->$type(" . (isset($args[0]) ? "'{$args[0]}'" : '') . ") call to Locator->loadClass('$class', '$path') failed. Check scope, path and class name. ";
				}
			} elseif (file_exists("$path$class.php")) {
				if (class_exists($class)) {
					$obj = new $class(isset($args[1]) ? $args[1] : $this->locator);
				}
			} else {
				$this->errorMsg[] =  "Could not load $path$class.php. ";
			}
			// initialize object
			if ($obj) {
				if (method_exists($obj, 'setLocator') && $this->locator) {
					// set directly so constructor not needed
					$obj->setLocator($this->locator);
				}
				// template and view need passed values set
				switch ($type) {
					case 'template':
					case 'view':
						if (isset($args[1]) && is_array($args[1])) {
							// if 2nd param is array then use it to set template values
							foreach ($args[1] as $key => $val) {
								$obj->set($key, $val);
							}
						}
						break;
					case 'helper':
						$this->parent->_helper($args[0], $obj);
						break;
				}
				 // this is the section for when response() has been called
				 if ($this->responseSet) {
					 if ($this->locator) {
					 	$response = $this->locator->get('Response');
						if ($response && $obj) {
							if ($this->responseName) {
								$response->set($this->responseName, $obj);		// if name then set data in response

							} elseif (in_array($type, $this->rendererTypes)) {	// if renderer set as renderer
								$response->setRenderer($obj);
							} else {
								$response->set($class, $obj);					// otherwise set by class name
							}
						} else {
							echo $obj->render();	// do we really want this option? or should the action do this?
						}
						return $this;				// if response set then allow chained
					}
				}
			} else {
				$this->errorMsg[] = "Did not create $class object. ";
			}
			//reset scope and response
			$this->scope = null;
			$this->scopePath = null;
			$this->responseSet = false;
			return $obj;
		}
	}

}
