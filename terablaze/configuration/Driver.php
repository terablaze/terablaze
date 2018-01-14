<?php

namespace TeraBlaze\Configuration;

use TeraBlaze\Base as Base;
use TeraBlaze\Configuration\Exception as Exception;

/**
 * Class Driver
 * @package TeraBlaze\Configuration
 */
class Driver extends Base
{
    protected $_parsed = array();

	/**
	 * @return $this
	 */
    public function initialize()
    {
        return $this;
    }

	/**
	 * @param $method
	 * @return Exception\Implementation
	 */
    protected function _getExceptionForImplementation($method)
    {
        return new Exception\Implementation("{$method} method not implemented");
    }
}
