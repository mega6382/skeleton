<?php
/**
 * Radio.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Html_Form_Radio
 *
 * Generate HTML form radio button input
 *
 * @package A_Html
 */
class A_Html_Form_Radio extends A_Html_Form_Radiocheckbox
{

	/*
	 * name=string, values=array(), $labels=array(), $selected=array()
	 */
	public function render($attr=array(), $str='')
	{
		parent::mergeAttr($attr);
		if (!isset($attr['value'])) {
			$attr['value'] = $str;
		}
		$attr['type'] = 'radio';
		return parent::render($attr);
	}

}
