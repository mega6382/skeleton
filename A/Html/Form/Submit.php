<?php
/**
 * Submit.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Html_Form_Submit
 *
 * Generate HTML form submit button
 *
 * @package A_Html
 */
class A_Html_Form_Submit extends A_Html_Tag
{

	/*
	 * name=string, value=string
	 */
	public function render($attr=array())
	{
		parent::mergeAttr($attr);
		parent::defaultAttr($attr, array('type' => 'submit'));
		return parent::render('input', $attr);
	}

}
