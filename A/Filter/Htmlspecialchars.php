<?php
/**
 * Alnum.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Filter_Alnum
 *
 * Filter value with htmlspecialchars() function with provided quote style and character set
 *
 * @package A_Filter
 */
class A_Filter_Htmlspecialchars extends A_Filter_Base
{

	public $character_set;
	public $escape_quote_style;

	public function __construct($escape_quote_style=ENT_QUOTES, $character_set='UTF-8')
	{
		$this->escape_quote_style = $escape_quote_style;
		$this->character_set = $character_set;
	}

	public function filter()
	{
		return htmlspecialchars($this->getValue(), $this->escape_quote_style, $this->character_set);
	}

}
