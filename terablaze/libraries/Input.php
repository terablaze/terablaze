<?php
/**
 * Created by TeraBoxX.
 * User: tommy
 * Date: 2/6/2017
 * Time: 6:03 PM
 */

namespace TeraBlaze\Libraries;

use TeraBlaze\Base as Base;

/**
 * Class Input
 * @package TeraBlaze\Libraries
 */
class Input extends Base
{
	/**
	 * @param $key
	 * @param string $default
	 * @return string
	 *
	 * accesses the $_GET superglobal
	 */
	public function get($key, $default = "")
	{
		if (!empty($_GET[$key]))
		{
			return $_GET[$key];
		}
		return $default;
	}

	/**
	 * @param $key
	 * @param string $default
	 * @return string
	 *
	 * accesses the $_POST superglobal
	 */
	public function post($key, $default = "")
	{
		if (!empty($_POST[$key]))
		{
			return $_POST[$key];
		}
		return $default;
	}

	/**
	 * @param $key
	 * @param string $default
	 * @return string
	 *
	 * accesses the $_FILES superglobal
	 */
	public function files($key, $default = "")
	{
		if (!empty($_FILES[$key]))
		{
			return $_FILES[$key];
		}
		return $default;
	}

	/**
	 * @param $key
	 * @param string $default
	 * @return string
	 *
	 * accesses the $_SERVER superglobal
	 */
	public function server($key, $default = "")
	{
		if (!empty($_SERVER[$key]))
		{
			return $_SERVER[$key];
		}
		return $default;
	}

	/**
	 * @param $key
	 * @param string $default
	 * @return string
	 *
	 * accesses the $_COOKIE superglobal
	 */
	public function cookie($key, $default = "")
	{
		if (!empty($_COOKIE[$key]))
		{
			return $_COOKIE[$key];
		}
		return $default;
	}
}