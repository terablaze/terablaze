<?php
/**
 * Created by TeraBoxX.
 * User: tommy
 * Date: 2/1/2017
 * Time: 9:22 AM
 */

namespace TeraBlaze\Libraries\Database;

use TeraBlaze\Base as Base;
use TeraBlaze\ArrayMethods as ArrayMethods;
use TeraBlaze\Libraries\Database\Exception as Exception;

class Query extends Base
{
	/**
	 * @readwrite
	 */
	protected $_connector;

	/**
	 * @read
	 */
	protected $_table;

	/**
	 * @read
	 */
	protected $_fields;

	/**
	 * @read
	 */
	protected $_limit;

	/**
	 * @read
	 */
	protected $_offset;

	/**
	 * @read
	 */
	protected $_order;

	/**
	 * @read
	 */
	protected $_direction;

	/**
	 * @read
	 */
	protected $_join = array();

	/**
	 * @read
	 */
	protected $_where = array();

	protected function _getExceptionForImplementation($method)
	{
		return new Exception\Implementation("{$method} method not implemented");
	}

	protected function _quote($value)
	{
		if (is_string($value))
		{
			$escaped = $this->connector->escape($value);
			return "'{$escaped}'";
		}

		if (is_array($value))
		{
			$buffer = array();

			foreach ($value as $i)
			{
				array_push($buffer, $this->_quote($i));
			}

			$buffer = join(", ", $buffer);
			return "({$buffer})";
		}

		if (is_null($value))
		{
			return "NULL";
		}

		if (is_bool($value))
		{
			return (int) $value;
		}

		return $this->connector->escape($value);
	}

	protected function _buildSelect()
	{
		$fields = array();
		$where = $order = $limit = $join = "";
		$template = "SELECT %s FROM %s %s %s %s %s";

		foreach ($this->fields as $table => $_fields)
		{
			foreach ($_fields as $field => $alias)
			{
				if (is_string($field))
				{
					$fields[] = "{$field} AS {$alias}";
				}
				else
				{
					$fields[] = $alias;
				}
			}
		}

		$fields = join(", ", $fields);

		$_join = $this->join;
		if (!empty($_join))
		{
			$join = join(" ", $_join);
		}

		$_where = $this->where;
		if (!empty($_where))
		{
			$joined = join(" AND ", $_where);
			$where = "WHERE {$joined}";
		}

		$_order = $this->order;
		if (!empty($_order))
		{
			$_direction = $this->direction;
			$order = "ORDER BY {$_order} {$_direction}";
		}

		$_limit = $this->limit;
		if (!empty($_limit))
		{
			$_offset = $this->offset;

			if ($_offset)
			{
				$limit = "LIMIT  {$_offset}, {$_limit}";
			}
			else
			{
				$limit = "LIMIT {$_limit}";
			}
		}

		return sprintf($template, $fields, $this->table, $join, $where, $order, $limit);
	}

	protected function _buildInsert($data)
	{
		$fields = array();
		$values = array();
		$template = "INSERT INTO `%s` (`%s`) VALUES (%s)";

		foreach ($data as $field => $value)
		{
			$fields[] = $field;
			$values[] = $this->_quote($value);
		}

		$fields = join("`, `", $fields);
		$values = join(", ", $values);

		return sprintf($template, $this->table, $fields, $values);
	}

	protected function _buildUpdate($data)
	{
		$parts = array();
		$where = $limit = "";
		$template = "UPDATE %s SET %s %s %s";

		foreach ($data as $field => $value)
		{
			$parts[] = "{$field} = ".$this->_quote($value);
		}

		$parts = join(", ", $parts);

		$_where = $this->where;
		if (!empty($_where))
		{
			$joined = join(" AND ", $_where);
			$where = "WHERE {$joined}";
		}

		$_limit = $this->limit;
		if (!empty($_limit))
		{
			$_offset = $this->offset;
			$limit = "LIMIT {$_offset}, {$_limit}";
		}

		return sprintf($template, $this->table, $parts, $where, $limit);
	}

	protected function _buildDelete()
	{
		$where = $limit ="";
		$template = "DELETE FROM %s %s %s";

		$_where = $this->where;
		if (!empty($_where))
		{
			$joined = join(" AND ", $_where);
			$where = "WHERE {$joined}";
		}

		$_limit = $this->limit;
		if (!empty($_limit))
		{
			$_offset = $this->offset;
			$limit = "LIMIT {$_offset}, {$_limit}";
		}

		return sprintf($template, $this->table, $where, $limit);
	}

	public function save($data)
	{
		$isInsert = sizeof($this->_where) == 0;

		if ($isInsert)
		{
			$sql = $this->_buildInsert($data);
		}
		else
		{
			$sql = $this->_buildUpdate($data);
		}

		$result = $this->_connector->execute($sql);

		if ($result === false)
		{
			throw new Exception\Sql();
		}

		if ($isInsert)
		{
			return $this->_connector->lastInsertId;
		}

		return 1;
	}

	public function delete()
	{
		$sql = $this->_buildDelete();
		$result = $this->_connector->execute($sql);

		if ($result === false)
		{
			throw new Exception\Sql();
		}

		return $this->_connector->affectedRows;
	}

	public function table($table, $fields = array("*"))
	{
		if (empty($table))
		{
			throw new Exception\Argument("Invalid argument");
		}

		$this->_table = $table;

		if ($fields)
		{
			$this->_fields[$table] = $fields;
		}

		return $this;
	}

	public function join($join, $on, $fields = array())
	{
		if (empty($join))
		{
			throw new Exception\Argument("Invalid argument");
		}

		if (empty($on))
		{
			throw new Exception\Argument("Invalid argument");
		}

		$this->_fields += array($join => $fields);
		$this->_join[] = "JOIN {$join} ON {$on}";

		return $this;
	}

	public function left_join($join, $on, $fields = array())
	{
		if (empty($join))
		{
			throw new Exception\Argument("Invalid argument");
		}

		if (empty($on))
		{
			throw new Exception\Argument("Invalid argument");
		}

		$this->_fields += array($join => $fields);
		$this->_join[] = "LEFT JOIN {$join} ON {$on}";

		return $this;
	}

	public function right_join($join, $on, $fields = array())
	{
		if (empty($join))
		{
			throw new Exception\Argument("Invalid argument");
		}

		if (empty($on))
		{
			throw new Exception\Argument("Invalid argument");
		}

		$this->_fields += array($join => $fields);
		$this->_join[] = "RIGHT JOIN {$join} ON {$on}";

		return $this;
	}

	public function limit($limit, $page = 1)
	{
		if (empty($limit))
		{
			throw new Exception\Argument("Invalid argument");
		}

		$this->_limit = $limit;
		$this->_offset = $limit * ($page - 1);

		return $this;
	}

	public function order($order, $direction = "asc")
	{
		if (empty($order))
		{
			throw new Exception\Argument("Invalid argument");
		}

		$this->_order = $order;
		$this->_direction = $direction;

		return $this;
	}

	public function where()
	{
		$arguments = func_get_args();
		if (sizeof($arguments) == 1){
			$this->_where[] = $arguments[0];
			return $this;
		}

		if (sizeof($arguments) < 1)
		{
			throw new Exception\Argument("Invalid argument");
		}

		$arguments[0] = preg_replace("#\?#", "%s", $arguments[0]);

		foreach (array_slice($arguments, 1, null, true) as $i => $parameter)
		{
			$arguments[$i] = $this->_quote($arguments[$i]);
		}

		$this->_where[] = call_user_func_array("sprintf", $arguments);

		return $this;
	}

	public function first()
	{
		$limit = $this->_limit;
		$offset = $this->_offset;

		$this->limit(1);

		$all = $this->all();
		$first = ArrayMethods::first($all);

		if ($limit)
		{
			$this->_limit = $limit;
		}
		if ($offset)
		{
			$this->_offset = $offset;
		}

		return $first;
	}

	public function count()
	{
		$limit = $this->limit;
		$offset = $this->offset;
		$fields = $this->fields;

		$this->_fields = array($this->table => array("COUNT(1)" => "rows"));

		$this->limit(1);
		$row = $this->first();

		$this->_fields = $fields;

		if ($fields)
		{
			$this->_fields = $fields;
		}
		if ($limit)
		{
			$this->_limit = $limit;
		}
		if ($offset)
		{
			$this->_offset = $offset;
		}

		return $row["rows"];
	}
}