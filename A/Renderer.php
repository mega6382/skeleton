<?php
/**
 * Renderer.php
 *
 * @package  A
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Renderer
 *
 * Interface representing an object capable of rendering data.
 */
interface A_Renderer extends A_Renderable
{

	/**
	 * Set internal data to be rendered
	 * 
	 * @param string $key Key of the data
	 * @param mixed $value Value to be stored under $key
	 * @return $this Should return self for fluency
	 */
	public function set($key, $value);
	
	/**
	 * Get internal data at index
	 * 
	 * @param string $key
	 * @return mixed Value stored under $key
	 */
	public function get($key);
	
	/**
	 * Merge an array of data with the data already stored internally
	 * 
	 * @param array $data
	 * @return $this Should return self for fluency
	 */
	public function import($data);

}
