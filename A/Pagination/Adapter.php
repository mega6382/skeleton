<?php
/**
 * Interface.php
 *
 * @package  A_Pagination
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Pagination_Adapter_Interface
 * 
 * Interface for pagination adapters
 */
interface A_Pagination_Adapter
{

	public function getItems($offset, $length);
	public function getNumItems();
	public function setOrderBy($field, $descending=false);

}