<?php
/**
 * Created by TeraBoxX.
 * User: tommy
 * Date: 1/30/2017
 * Time: 3:36 PM
 */

namespace TeraBlaze\Configuration\Driver;

use TeraBlaze\ArrayMethods as ArrayMethods;
use TeraBlaze\Configuration as Configuration;
use TeraBlaze\Configuration\Exception as Exception;

/**
 * Class PHPArray
 * @package TeraBlaze\Configuration\Driver
 *
 * handles loading of php array configuration files
 * and passing it's values
 *
 */
class PHPArray  extends Configuration\Driver
{
	/**
	 * @param $path
	 * @return \stdClass
	 *
	 * includes the php array configuration files
	 * and creates an object from it's key/value pairs
	 *
	 */
	public function parse($path)
	{
		$config = array();
		include("{$path}.php");
		return ArrayMethods::toObject($config);
	}
}