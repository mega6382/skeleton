<?php
/**
 * Tolower.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Filter_Tolower
 *
 * Filter a string to leave only alpha-numeric characters
 *
 * @package A_Filter
 */
class A_Filter_Tolower extends A_Filter_Base
{

	public function filter()
	{
		return strtolower($this->getValue());
	}

}
