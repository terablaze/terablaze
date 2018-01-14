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

class File extends Cache\Driver
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
	protected $_directory;

	/**
	 * @readwrite
	 */
	protected $_isConnected = false;

	public function __construct(array $options = array())
	{
		parent::__construct($options);
		$duration = $this->duration;

		$files = glob($this->directory . $this->prefix . 'cache.*');

		if ($files) {
			foreach ($files as $file) {
				$time = substr(strrchr($file, '.'), 1);

				if ($time < time()) {
					if (file_exists($file)) {
						unlink($file);
					}
				}
			}
		}
	}

	public function connect(){
		return $this;
	}

	public function get($key, $default = null)
	{
		$files = glob($this->directory . $this->prefix . 'cache.' . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '.*');

		if ($files) {
			$handle = fopen($files[0], 'r');

			flock($handle, LOCK_SH);

			$data = fread($handle, filesize($files[0]));

			flock($handle, LOCK_UN);

			fclose($handle);

			return unserialize($data, true);
		}

		return $default;
	}

	public function set($key, $value, $duration = "")
	{
		if(empty($duration)){
			$duration = $this->duration;
		}
		$this->erase($key);

		$file = $this->directory . $this->prefix. 'cache.' . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '.' . (time() + $duration);

		$handle = fopen($file, 'w');

		flock($handle, LOCK_EX);

		fwrite($handle, serialize($value));

		fflush($handle);

		flock($handle, LOCK_UN);

		fclose($handle);

		return $this;
	}

	public function erase($key)
	{
		$files = glob($this->directory . $this->prefix.'cache.' . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '.*');

		if ($files) {
			foreach ($files as $file) {
				if (file_exists($file)) {
					unlink($file);
				}
			}
		}
	}
}