<?php
/**
 * Created by TeraBoxX.
 * User: tommy
 * Date: 1/30/2017
 * Time: 3:03 PM
 */

namespace TeraBlaze\Configuration\Driver;

use TeraBlaze\ArrayMethods as ArrayMethods;
use TeraBlaze\Configuration as Configuration;
use TeraBlaze\Configuration\Exception as Exception;

/**
 * Class Ini
 * @package TeraBlaze\Configuration\Driver
 *
 * handles loading of .ini configuration files
 * and passing it's values
 *
 */
class Ini extends Configuration\Driver
{
	/**
	 * @param $config
	 * @param $key
	 * @param $value
	 * @return mixed
	 */
	protected function _pair($config, $key, $value)
	{
		if (strstr($key, "."))
		{
			$parts = explode(".", $key, 2);

			if (empty($config[$parts[0]]))
			{
				$config[$parts[0]] = array();
			}

			$config[$parts[0]] = $this->_pair($config[$parts[0]], $parts[1], $value);
		}
		else
		{
			$config[$key] = $value;
		}

		return $config;
	}

	/**
	 * @param $path
	 * @return mixed
	 * @throws Exception\Argument
	 * @throws Exception\Syntax
	 *
	 * includes the .ini configuration files
	 * and creates an object from it's key/value pairs
	 *
	 */
	public function parse($path)
	{
		if (empty($path))
		{
			throw new Exception\Argument("\$path argument is not valid");
		}

		if (!isset($this->_parsed[$path]))
		{
			$config = array();

			ob_start();
			include("{$path}.ini");
			$string = ob_get_contents();
			ob_end_clean();

			$pairs = parse_ini_string($string);

			if ($pairs == false)
			{
				throw new Exception\Syntax("Could not parse configuration file");
			}

			foreach ($pairs as $key => $value)
			{
				$config = $this->_pair($config, $key, $value);
			}

			$this->_parsed[$path] = ArrayMethods::toObject($config);
		}


		return $this->_parsed[$path];
	}
}