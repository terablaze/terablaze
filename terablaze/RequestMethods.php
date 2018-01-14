<?php
/**
 * Created by TeraBoxX.
 * User: tommy
 * Date: 2/6/2017
 * Time: 6:03 PM
 */

namespace TeraBlaze;

/**
 * Class RequestMethods
 * @package TeraBlaze
 */
class RequestMethods
{
	/**
	 * RequestMethods constructor.
	 *
	 * helps to prevent the creation of a RequestMethods instance
	 */
	private function __construct()
	{
		// do nothing
	}

	/**
	 * helps to prevent the creation of a RequestMethods clone
	 */
	private function __clone()
	{
		// do nothing
	}

	/**
	 * @param $key
	 * @param string $default
	 * @return string
	 *
	 * accesses the $_GET superglobal
	 */
	public static function get($key, $default = "")
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
	public static function post($key, $default = "")
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
	 * accesses the $_SERVER superglobal
	 */
	public static function server($key, $default = "")
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
	public static function cookie($key, $default = "")
	{
		if (!empty($_COOKIE[$key]))
		{
			return $_COOKIE[$key];
		}
		return $default;
	}
}