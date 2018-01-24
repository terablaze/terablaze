<?php

namespace TeraBlaze\Router\Route;

use TeraBlaze\Router as Router;

/**
 * Class Regex
 * @package TeraBlaze\Router\Route
 */
class Regex extends Router\Route
{
    /**
    * @readwrite
    */
    protected $_keys;

	/**
	 * @param $url
	 * @return bool
	 */
    public function matches($url)
    {
	    $url = trim($url, '/');
        $pattern = $this->pattern;

        // check values
        preg_match_all("#^{$pattern}$#", $url, $values);

        if (sizeof($values) && sizeof($values[0]) && sizeof($values[1]))
        {
            // values found, modify parameters and return
            $derived = array_combine($this->keys, $values[1]);
            $this->parameters = array_merge($this->parameters, $derived);

            return true;
        }

        return false;
    }
}