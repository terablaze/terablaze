<?php
/**
 * Created by TeraBoxX.
 * User: tommy
 * Date: 11/27/2016
 * Time: 11:58 AM
 */

namespace TeraBlaze;

/**
 * Class StringMethods
 * @package TeraBlaze
 *
 * contains various methods for string manipulation
 */
class StringMethods
{
	private static $_delimiter = "#";

	private static $_singular = array(
		"(matr)ices$" => "\\1ix",
		"(vert|ind)ices$" => "\\1ex",
		"^(ox)en" => "\\1",
		"(alias)es$" => "\\1",
		"([octop|vir])i$" => "\\1us",
		"(cris|ax|test)es$" => "\\1is",
		"(shoe)s$" => "\\1",
		"(o)es$" => "\\1",
		"(bus|campus)es$" => "\\1",
		"([m|l])ice$" => "\\1ouse",
		"(x|ch|ss|sh)es$" => "\\1",
		"(m)ovies$" => "\\1\\2ovie",
		"(s)eries$" => "\\1\\2eries",
		"([^aeiouy]|qu)ies$" => "\\1y",
		"([lr])ves$" => "\\1f",
		"(tive)s$" => "\\1",
		"(hive)s$" => "\\1",
		"([^f])ves$" => "\\1fe",
		"(^analy)ses$" => "\\1sis",
		"((a)naly|(b)a|(d)iagno|(p)arenthe|(p)rogno|(s)ynop|(t)he)ses$" => "\\1\\2sis",
		"([ti])a$" => "\\1um",
		"(p)eople$" => "\\1\\2erson",
		"(m)en$" => "\\1an",
		"(s)tatuses$" => "\\1\\2tatus",
		"(c)hildren$" => "\\1\\2hild",
		"(n)ews$" => "\\1\\2ews",
		"([^u])s$" => "\\1"
	);

	private static $_plural = array(
		"^(ox)$" => "\\1\\2en",
		"([m|l])ouse$" => "\\1ice",
		"(matr|vert|ind)ix|ex$" => "\\1ices",
		"(x|ch|ss|sh)$" => "\\1es",
		"([^aeiouy]|qu)y$" => "\\1ies",
		"(hive)$" => "\\1s",
		"(?:([^f])fe|([lr])f)$" => "\\1\\2ves",
		"sis$" => "ses",
		"([ti])um$" => "\\1a",
		"(p)erson$" => "\\1eople",
		"(m)an$" => "\\1en",
		"(c)hild$" => "\\1hildren",
		"(buffal|tomat)o$" => "\\1\\2oes",
		"(bu|campu)s$" => "\\1\\2ses",
		"(alias|status|virus)" => "\\1es",
		"(octop)us$" => "\\1i",
		"(ax|cris|test)is$" => "\\1es",
		"s$" => "s",
		"$" => "s"
	);

	/**
	 * StringMethods constructor.
	 * helps to prevent the creation of a StringMethods instance
	 */
	private function __construct()
	{
		// do nothing
	}

	/**
	 * helps to prevent the creation of a StringMethods clone
	 */
	private function __clone()
	{
		// do nothing
	}

	/**
	 * @param $pattern
	 * @return string
	 */
	private static function _normalize($pattern)
	{
		return self::$_delimiter . trim($pattern, self::$_delimiter) . self::$_delimiter;
	}

	/**
	 * @return string
	 */
	public static function getDelimiter()
	{
		return self::$_delimiter;
	}

	/**
	 * @param $delimiter
	 */
	public static function setDelimiter($delimiter)
	{
		self::$_delimiter = $delimiter;
	}

	/**
	 * @param $string
	 * @param $pattern
	 * @return null
	 */
	public static function match($string, $pattern)
	{
		preg_match_all(self::_normalize($pattern), $string, $matches, PREG_PATTERN_ORDER);

		if (!empty($matches[1])) {
			return (array)$matches[1];
		}

		if (!empty($matches[0])) {
			return (array)$matches[0];
		}

		return (array)null;
	}

	/**
	 * @param $string
	 * @param $pattern
	 * @param null $limit
	 * @return array
	 */
	public static function split($string, $pattern, $limit = null)
	{
		$flags = PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE;
		return preg_split(self::_normalize($pattern), $string, $limit, $flags);
	}

	/**
	 * @param $string
	 * @param $mask
	 * @return mixed
	 */
	public static function sanitize($string, $mask)
	{
		if (is_array($mask)) {
			$parts = $mask;
		} else if (is_string($mask)) {
			$parts = str_split($mask);
		} else {
			return $string;
		}

		foreach ($parts as $part) {
			$normalized = self::_normalize("\\{$part}");
			$string = preg_replace(
				"{$normalized}m",
				"\\{$part}",
				$string
			);
		}

		return $string;
	}

	/**
	 * @param $string
	 * @return string
	 */
	public static function unique($string)
	{
		$unique = "";
		$parts = str_split($string);

		foreach ($parts as $part) {
			if (!strstr($unique, $part)) {
				$unique .= $part;
			}
		}

		return $unique;
	}

	/**
	 * @param $string
	 * @param $substring
	 * @param null $offset
	 * @return bool|int
	 */
	public static function indexOf($string, $substring, $offset = null)
	{
		$position = strpos($string, $substring, $offset);
		if (!is_int($position)) {
			return -1;
		}
		return $position;
	}

	/**
	 * @param $string
	 * @return mixed
	 *
	 * gets the singular version of $string
	 */
	public static function singular($string)
	{
		$result = $string;

		foreach (self::$_singular as $rule => $replacement) {
			$rule = self::_normalize($rule);

			if (preg_match($rule, $string)) {
				$result = preg_replace($rule, $replacement, $string);
				break;
			}
		}

		return $result;
	}

	/**
	 * @param $string
	 * @return mixed
	 *
	 * gets the plural form of $string
	 */
	public static function plural($string)
	{
		$result = $string;

		foreach (self::$_plural as $rule => $replacement) {
			$rule = self::_normalize($rule);

			if (preg_match($rule, $string)) {
				$result = preg_replace($rule, $replacement, $string);
				break;
			}
		}

		return $result;
	}

}

