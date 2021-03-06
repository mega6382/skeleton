<?php
/**
 * Groupby.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Sql_Groupby
 *
 * Generate SQL GROUP BY clause
 *
 * @package A_Sql
 */
class A_Sql_Groupby extends A_Sql_Columns
{

	protected $columns;

	public function render()
	{
		if ($this->columns) {
			return ' GROUP BY '. parent::render();
		}
	}

}
