<?php
/**
 * Values.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Sql_Columns
 *
 * Generate SQL values list
 *
 * @package A_Sql
 */
class A_Sql_Values extends A_Sql_Expression
{

	public function render($logic='')
	{
		if ($this->data) {
			// is it an array or rows or just one row? Check if first element is array.
			if (is_array(current($this->data))) {
				$columns = implode(', ', array_keys(current($this->data)));
			} else {
				$columns = implode(', ', array_keys($this->data));
				$this->data = array($this->data);
			}
			$values = array();
			foreach ($this->data as $row) {
				$values[] = '(' . implode(', ', array_map(array($this, 'quoteEscape'), array_values($row))) . ')';
			}
			return "(". $columns .") VALUES ". implode(', ', $values);
		}
	}

}
