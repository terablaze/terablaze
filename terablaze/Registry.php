<?php
/**
 * Created by TeraBoxX.
 * User: tommy
 * Date: 1/30/2017
 * Time: 5:01 PM
 */

namespace TeraBlaze;

/**
 * Class Registry
 * @package TeraBlaze
 *
 * serves as a store for class instances for faster operation
 */
class Registry
{
	private static $_instances = array();

	/**
	 * Registry constructor.
	 *
	 * helps to prevent the creation of a Registry instance
	 */
	private function __construct()
	{
		// do nothing
	}

	/**
	 * helps to prevent the creation of a Registry clone
	 */
	private function __clone()
	{
		// do nothing
	}

	/**
	 * @param $key
	 * @param null $default
	 * @return mixed|null
	 *
	 * gets a class instance stored in the specified $key
	 */
	public static function get($key, $default = null)
	{
		if (isset(self::$_instances[$key]))
		{
			return self::$_instances[$key];
		}
		return $default;
	}

	/**
	 * @param $key
	 * @param null $instance
	 *
	 * stores a new class instance to the specified $key
	 */
	public static function set($key, $instance = null)
	{
		self::$_instances[$key] = $instance;
	}

	/**
	 * @param $key
	 *
	 * erases a class instance stored in the specified $key
	 */
	public static function erase($key)
	{
		unset(self::$_instances[$key]);
	}

	/**
	 * gets all the stored instances
	 */
	public static function getInstances(){
		foreach (self::$_instances as $key => $item) {
			echo "\n" . $key . " = " . get_class($item)."\n";
		}
	}
}