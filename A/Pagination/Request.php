<?php
/**
 * Request.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 * @author	Cory Kaufman, Christopher Thompson
 */

/**
 * A_Pagination_Request
 *
 * Component to initialize the core object from the request
 *
 * @package A_Pagination
 */
class A_Pagination_Request extends A_Pagination_Core
{

	public function setRequest($request)
	{
		$this->request = $request;
	}

	public function setSession($session)
	{
		$this->session = $session;
	}

	public function process($request=null, $session=null)
	{
		if ($request !== null) {
			$this->request = $request;
		}
		if ($session !== null) {
			$this->session = $session;
		}

		if ($numItems = $this->get('num_items')) {
			$this->setNumItems(intval($numItems));
		}
		if ($pageSize = $this->get('page_size')) {
			$this->setPageSize(intval($pageSize));
		}
		if ($orderBy = $this->get('order_by')) {
			list ($field, $dir) = explode('|', $orderBy);
			$this->setOrderBy ($field, ($dir === 'desc' ? true : false));
		}
		$this->setcurrentPage(intval($this->get('page')), $this->getFirstPage());	// do after getting num_items
	}

	public function get($param, $default='')
	{
		$name = $this->getParamName($param);
		// first check if request object or array set, otherwise use superglobal
		if (isset($this->request)) {
			if (is_object($this->request)) {
				if ($this->request->has($name)) {
					return $this->request->get($name);
				}
			} elseif (is_array($this->request) && isset($this->request[$name])) {
				return $this->request[$name];
			}
		} elseif (isset($_REQUEST[$name])) {
			return $_REQUEST[$name];
		}
		// second use variable from session
		if (isset($this->session)) {
			if ($this->session->has($name)) return $this->session->get($name);
		}
		return $default;
	}

}
