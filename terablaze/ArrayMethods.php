<?php
/**
 * Created by TeraBoxX.
 * User: tommy
 * Date: 11/27/2016
 * Time: 11:57 AM
 */

namespace TeraBlaze;

/**
 * Class ArrayMethods
 * @package TeraBlaze
 */
class ArrayMethods {
	private function __construct()
	{
		// do nothing
	}

	private function __clone()
	{
		// do nothing
	}

	public static function clean($array)
	{
		return array_filter($array, function($item) {
			return !empty($item);
		});
	}

	public static function trim($array)
	{
		return array_map(function($item) {
			return trim($item);
		}, $array);
	}

	public static function toObject($array)
	{
		$result = new \stdClass();

		foreach ($array as $key => $value)
		{
			if (is_array($value))
			{
				$result->{$key} = self::toObject($value);
			}
			else
			{
				$result->{$key} = $value;
			}
		}

		return $result;
	}
	
	public static function toQueryString($array)
	{
		return http_build_query(
			self::clean(
				$array
			)
		);
	}

	public static function flatten($array, $return = array())
	{
		foreach ($array as $key => $value)
		{
			if (is_array($value) || is_object($value))
			{
				$return = self::flatten($value, $return);
			}
			else
			{
				$return[] = $value;
			}
		}

		return $return;
	}

	public static function first($array)
	{
		if (sizeof($array) == 0)
		{
			return null;
		}

		$keys = array_keys($array);
		return $array[$keys[0]];
	}

	public static function last($array)
	{
		if (sizeof($array) == 0)
		{
			return null;
		}

		$keys = array_keys($array);
		return $array[$keys[sizeof($keys) - 1]];
	}
}
