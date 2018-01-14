<?php
/**
 * Created by TeraBoxX.
 * User: tommy
 * Date: 2/1/2017
 * Time: 9:19 AM
 */

namespace TeraBlaze\Libraries\Database\Query;

use TeraBlaze\Libraries\Database as Database;
use TeraBlaze\Libraries\Database\Exception as Exception;

/**
 * Class Mysql
 * @package TeraBlaze\Libraries\Database\Query
 */
class Mysql extends Database\Query
{

	/**
	 * @return array    returns all matching fields from the database
	 * @throws Exception\Sql
	 */
	public function all()
	{
		$sql = $this->_buildSelect();
		$result = $this->connector->execute($sql);

		if ($result === false)
		{
			$error = $this->connector->lastError;
			throw new Exception\Sql("There was an error with your SQL query: {$error}");
		}

		$rows = array();

		for ($i = 0; $i < $result->num_rows; $i++)
		{
			$rows[] = $result->fetch_array(MYSQLI_ASSOC);
		}

		return $rows;
	}
}