<?php
/**
 * Query.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 * @author	Cory Kaufman
 */

/**
 * A_Sql_Query
 *
 * @package A_Sql
 */
class A_Sql_Query
{

	public function select()
	{
		return new A_Sql_Select();
	}

	public function insert($table=null, $bind=array())
	{
		return new A_Sql_Insert($table, $bind);
	}

	public function update($table=null, $bind=array(), $where=array())
	{
		return new A_Sql_Update($table, $bind, $where);
	}

	public function delete($table = null, $where = array())
	{
		return new A_Sql_Delete($table, $where);
	}

}
