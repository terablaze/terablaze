<?php

/**
 * Created by TeraBoxX.
 * User: tommy
 * Date: 1/30/2017
 * Time: 4:14 PM
 */

namespace TeraBlaze\Libraries\Cache;

use TeraBlaze\Base as Base;
use TeraBlaze\Libraries\Cache\Exception as Exception;

class Driver extends Base
{
	public function __construct(array $options = array())
	{
		parent::__construct($options);
		return $this->connect();
	}
	public function initialize()
	{
		return $this;
	}
	protected function _getExceptionForImplementation($method)
	{
		return new Exception\Implementation("{$method} method not implemented");
	}
}