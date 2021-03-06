<?php
/**
 * Digit.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Filter_Digit
 *
 * Filter a string to leave only digits
 *
 * @package A_Filter
 */
class A_Filter_Digit extends A_Filter_Base
{

	public function filter()
	{
		return preg_replace('/[^[:digit:]]/', '', $this->getValue());
	}

}
