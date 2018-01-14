<?php

/**
 * Created by TeraBoxX.
 * User: tommy
 * Date: 2/1/2017
 * Time: 4:47 AM
 */
namespace TeraBlaze\Libraries\Database\Connector;

use TeraBlaze\Libraries\Database as Database;
use TeraBlaze\Libraries\Database\Exception as Exception;

class Mysql extends Database\Connector
{
	protected $_service;

	/**
	 * @readwrite
	 */
	protected $_host;

	/**
	 * @readwrite
	 */
	protected $_username;

	/**
	 * @readwrite
	 */
	protected $_password;

	/**
	 * @readwrite
	 */
	protected $_schema;

	/**
	 * @readwrite
	 */
	protected $_port = "3306";

	/**
	 * @readwrite
	 */
	protected $_charset = "utf8";

	/**
	 * @readwrite
	 */
	protected $_engine = "InnoDB";

	/**
	 * @readwrite
	 */
	protected $_isConnected = false;

	/**
	 * checks if connected to the database
	 */
	protected function _isValidService()
	{
		$isEmpty = empty($this->_service);
		$isInstance = $this->_service instanceof \MySQLi;

		if ($this->isConnected && $isInstance && !$isEmpty)
		{
			return true;
		}

		return false;
	}

	/**
	 * connects to the database
	 */
	public function connect()
	{
		if (!$this->_isValidService())
		{
			$this->_service = new \MySQLi(
				$this->_host,
				$this->_username,
				$this->_password,
				$this->_schema,
				$this->_port
			);

			if ($this->_service->connect_error)
			{
				throw new Exception\Service("Unable to connect to service");
			}

			$this->isConnected = true;
		}

		return $this;
	}

	/**
	 * disconnects from the database
	 */
	public function disconnect()
	{
		if ($this->_isValidService())
		{
			$this->isConnected = false;
			$this->_service->close();
		}

		return $this;
	}

	/**
	 * returns a corresponding query instance
	 */
	public function query()
	{
		return new Database\Query\Mysql(array(
			"connector" => $this
		));
	}

	/**
	 * executes the provided SQL statement
	 */
	public function execute($sql)
	{
		\TeraBlaze\Events::fire("terablaze.libraries.database.query", array($sql, ""));
		if (!$this->_isValidService())
		{
			throw new Exception\Service("Not connected to a valid service");
		}

		return $this->_service->query($sql);
	}

	/**
	 * escapes the provided value to make it safe for queries
	 */
	public function escape($value)
	{
		if (!$this->_isValidService())
		{
			throw new Exception\Service("Not connected to a valid service");
		}

		return $this->_service->real_escape_string($value);
	}

	/**
	 * returns the ID of the last row
	 * to be inserted
	 */
	public function getLastInsertId()
	{
		if (!$this->_isValidService())
		{
			throw new Exception\Service("Not connected to a valid service");
		}

		return $this->_service->insert_id;
	}

	/**
	 * returns the number of rows affected
	 * by the last SQL query executed
	 */
	public function getAffectedRows()
	{
		if (!$this->_isValidService())
		{
			throw new Exception\Service("Not connected to a valid service");
		}

		return $this->_service->affected_rows;
	}

	/**
	 * returns the last error that occured
	 */
	public function getLastError()
	{
		if (!$this->_isValidService())
		{
			throw new Exception\Service("Not connected to a valid service");
		}

		return $this->_service->error;
	}

}