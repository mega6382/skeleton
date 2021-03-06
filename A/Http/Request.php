<?php
/**
 * Request.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Http_Request
 *
 * Encapsulate the HTTP request in a class to access information and values
 *
 * @package A_Http
 */
class A_Http_Request
{

	public $data = array();

	protected $method = false;
	protected $filters = array();
	protected $getOverPost = false;

	public function __construct()
	{
		$this->method = strtoupper($_SERVER['REQUEST_METHOD']);
		if ($this->method == 'POST') {
			$this->data =& $_POST;
		} elseif ($this->method == 'PUT') {
			$this->data = parse_str(file_get_contents('php://input'));
		} else {
			$this->data =& $_GET;
		}
		if (isset($_SERVER['PATH_INFO'])) {
			$this->data['PATH_INFO'] = trim($_SERVER['PATH_INFO'], '/');
		}
	}

	/**
	 * Turn GET over POST mode on/off.  GET over POST mode allows you to receive both POST and GET params on a POST request.  Does not apply to GET requests.  If true then on POST requests, if $_POST[$name] is not set it will return $_GET[$name]
	 *
	 * @param bool $allow
	 * @return $this
	 */
	public function allowGetOverPost($allow=true)
	{
		$this->getOverPost = $allow;
		return $this;
	}

	public function removeSlashes()
	{
		if (get_magic_quotes_gpc()) {
			$input = array(&$_GET, &$_POST, &$_COOKIE, &$_ENV, &$_SERVER);
			while (list($k,$v) = each($input)) {
				foreach ($v as $key => $val) {
					if (!is_array($val)) {
						$input[$k][$key] = stripslashes($val);
						continue;
					}
					$input[] =& $input[$k][$key];
				}
			}
			unset($input);
		}
		return $this;
	}

	public function setPathInfo($path_info)
	{
		$this->data['PATH_INFO'] = trim($path_info, '/');
		return $this;
	}

	public function getFilters()
	{
		return $this->filters;
	}

	public function setFilters($filters)
	{
		$this->filters = is_array($filters) ? $filters : array($filters);
		return $this;
	}

	/**
	 * Get the protocol this request was made on (either HTTP or HTTPS).
	 *
	 * @return string
	 */
	public function getProtocol()
	{
		if (isset($_SERVER["HTTPS"]) && ($_SERVER["HTTPS"] == 'on')) {
			return 'https';
		} else {
			return 'http';
		}
	}

	public function getMethod()
	{
		return $this->method;
	}

	public function isPost()
	{
		return $this->method == 'POST';
	}

	public function isAjax()
	{
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest');
	}

	protected function _get(&$data, $name, $filters=null, $default=null)
	{
		if (isset($data[$name])) {
			if ($filters || $this->filters) {
				// allow single filter to be passed - convert to array
				if (!is_array($filters)) {
					$filters = array($filters);
				}
				// if global filters - merge
				if ($this->filters) {
					$filters = array_merge($this->filters, $filters);
				}
				// allow parameter that is array
				$d = is_array($data[$name]) ? $data[$name] : array($data[$name]);
				foreach (array_keys($d) as $key) {
					foreach ($filters as $filter) {
						if (is_string($filter)) {
							if (substr($filter, 0, 1) == '/') {
								$d[$key] = preg_replace($filter, '', $d[$key]);
							} else {
								$d[$key] = $filter($d[$key]);
							}
						} elseif (is_object($filter)) {
							$d[$key] = $filter->doFilter($d[$key]);
						} elseif (is_array($filter)) {
							$d[$key] = call_user_func($filter, $d[$key]);
						}
					}
				}
				// if orignial param was array return array else revert to scalar
				if (is_array($data[$name])) {
					return $d;
				} else {
					return $d[0];
				}
			}
			return $data[$name];
		} elseif ($default !== null) {
			return $default;
		}
	}

	public function get($name, $filter=null, $default=null)
	{
		// GET over POST mode only checks for $_GET[$name] if method is POST and no $_POST[$name]
		if ($this->getOverPost && !isset($this->data[$name]) && $this->method == 'POST') {
			return $this->_get($_GET, $name, $filter, $default);
		} else {
			return $this->_get($this->data, $name, $filter, $default);
		}
	}

	public function getPost($name, $filter=null, $default=null)
	{
		return $this->_get($_POST, $name, $filter, $default);
	}

	public function getQuery($name, $filter=null, $default=null)
	{
		return $this->_get($_GET, $name, $filter, $default);
	}

	public function getCookie($name, $filter=null, $default=null)
	{
		return $this->_get($_COOKIE, $name, $filter, $default);
	}

	public function getHeader($name, $filter=null, $default=null)
	{
        if (isset($_SERVER[$name])) {
            return $this->_get($_SERVER, $name, $filter, $default);
        } elseif (function_exists('apache_request_headers')) {
            $headers = apache_request_headers();
            if (isset($headers[$name])) {
                return $this->_get($headers, $name, $filter, $default);
            }
        }
	}

	public function export($filter=null, $pattern=null)
	{
		if ($filter || $pattern) {
			$export = array();
			foreach (array_keys($this->data) as $key) {
				if (preg_match($pattern, $key)) {
					$export[$key] = $this->_get($this->data, $key, $filter);
				}
			}
			return $export;
		} else {
			return $this->data;
		}
	}

	public function set($name, $value, $default=null)
	{
		if ($value !== null) {
			$this->data[$name] = $value;
		} elseif ($default !== null) {
			$this->data[$name] = $default;
		} else {
			unset($this->data[$name]);
		}
		return $this;
	}

	public function has($name)
	{
		return isset($this->data[$name]);
	}

}
