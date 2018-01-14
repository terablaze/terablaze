<?php
/**
 * Created by TeraBoxX.
 * User: tommy
 * Date: 2/1/2017
 * Time: 4:39 AM
 */

namespace TeraBlaze\Libraries\Database;

use TeraBlaze\Base as Base;
use TeraBlaze\Libraries\Database\Exception as Exception;

class Connector extends Base
{

	public function __construct(array $options = array())
	{
		parent::__construct($options);
		return $this->connect();
	}

	public function connect(){

	}

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