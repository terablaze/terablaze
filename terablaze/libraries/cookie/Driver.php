<?php
/**
 * Created by TeraBoxX.
 * User: tommy
 * Date: 2/4/2017
 * Time: 9:19 AM
 */

namespace TeraBlaze\Libraries\Cookie;

use TeraBlaze\Base as Base;
use TeraBlaze\Libraries\Cookie\Exception as Exception;

class Driver extends Base
{
	public function initialize()
	{
		return $this;
	}

	protected function _getExceptionForImplementation($method)
	{
		return new Exception\Implementation("{$method} method not implemented");
	}
}