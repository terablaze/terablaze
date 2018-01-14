<?php
/**
 * Created by TeraBoxX.
 * User: tommy
 * Date: 1/30/2017
 * Time: 4:17 PM
 */

namespace TeraBlaze\Libraries\Cache\Driver;

use TeraBlaze\Libraries\Cache as Cache;
use TeraBlaze\Libraries\Cache\Exception as Exception;

class Memcached extends Cache\Driver
{
	protected $_service;

	/**
	 * @readwrite
	 */
	protected $_host;

	/**
	 * @readwrite
	 */
	protected $_port;

	/**
	 * @readwrite
	 */
	protected $_prefix;

	/**
	 * @readwrite
	 */
	protected $_duration;

	/**
	 * @readwrite
	 */
	protected $_isConnected = false;

	protected function _isValidService()
	{
		$isEmpty = empty($this->_service);
		$isInstance = $this->_service instanceof \Memcache;

		if ($this->isConnected && $isInstance && !$isEmpty)
		{
			return true;
		}

		return false;
	}

	public function connect()
	{
		try
		{
			$this->_service = new \Memcache();
			$this->_service->connect(
				$this->host,
				$this->port
			);
			$this->isConnected = true;
		}
		catch (\Exception $e)
		{
			throw new Exception\Service("Unable to connect to service");
		}

		return $this;
	}

	public function disconnect()
	{
		if ($this->_isValidService())
		{
			$this->_service->close();
			$this->isConnected = false;
		}

		return $this;
	}

	public function get($key, $default = null)
	{
		if (!$this->_isValidService())
		{
			throw new Exception\Service("Not connected to a valid service");
		}

		$value = $this->_service->get($this->prefix.$key, MEMCACHE_COMPRESSED);

		if ($value)
		{
			return unserialize($value);
		}

		return $default;
	}

	public function set($key, $value, $duration = "")
	{
		if (!$this->_isValidService())
		{
			throw new Exception\Service("Not connected to a valid service");
		}

		if(empty($duration)){
			$duration = $this->duration;
		}
		$this->_service->set($this->prefix.$key, serialize($value), MEMCACHE_COMPRESSED, $duration);
		return $this;
	}

	public function erase($key)
	{
		if (!$this->_isValidService())
		{
			throw new Exception\Service("Not connected to a valid service");
		}

		$this->_service->delete($this->prefix.$key);
		return $this;
	}
}