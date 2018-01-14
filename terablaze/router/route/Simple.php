<?php

namespace TeraBlaze\Router\Route;

use TeraBlaze\Router as Router;
use TeraBlaze\ArrayMethods as ArrayMethods;

/**
 * Class Simple
 * @package TeraBlaze\Router\Route
 */
class Simple extends Router\Route
{
	/**
	 * @param $url
	 * @return bool
	 */
	public function matches($url)
	{
		$url = rtrim($url, '/');
		$pattern = $this->pattern;

		// get keys
		preg_match_all("#:([a-zA-Z0-9]+)#", $pattern, $keys);

		if (sizeof($keys) && sizeof($keys[0]) && sizeof($keys[1])) {
			$keys = $keys[1];
		} else {
			// no keys in the pattern, return a simple match
			return preg_match("#^{$pattern}$#", $url);
		}

		// normalize route pattern
		$pattern_keys = array("#(:any)#", "#(:alpha)#", "#(:alphabet)#", "#(:num)#", "#(:numeric)#", "#(:mention)#");
		$replacements = array("([^/]+)", "([a-zA-Z]+)", "([a-zA-Z]+)", "([0-9]+)", "([0-9]+)", "(@[a-zA-Z0-9-_]+)");
		$pattern = preg_replace($pattern_keys, $replacements, $pattern);

		// check values
		preg_match_all("#^{$pattern}$#", $url, $values);

		if (sizeof($values) && sizeof($values[0]) && sizeof($values[1])) {
			// unset the matched url
			unset($values[0]);

			// values found, modify parameters and return
			//$derived = array_combine($keys, ArrayMethods::flatten($values));
			$derived = ArrayMethods::flatten($values);
			$this->parameters = array_merge($this->parameters, $derived);

			return true;
		}

		return false;
	}
}