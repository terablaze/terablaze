<?php
/**
 * Created by TeraBoxX.
 * User: tommy
 * Date: 1/30/2017
 * Time: 6:06 PM
 */

namespace TeraBlaze\Router;

use TeraBlaze\Base as Base;
use TeraBlaze\Router\Exception as Exception;

/**
 * Class Route
 * @package TeraBlaze\Router
 */
class Route extends Base
{
	/**
	 * @readwrite
	 */
	protected $_method;
	/**
	 * @readwrite
	 */
	protected $_pattern;

	/**
	 * @readwrite
	 */
	protected $_controller;

	/**
	 * @readwrite
	 */
	protected $_action;

	/**
	 * @readwrite
	 */
	protected $_parameters = array();

	/**
	 * @param $method
	 * @return Exception\Implementation
	 */
	public function _getExceptionForImplementation($method)
	{
		return new Exception\Implementation("{$method} method not implemented");
	}
}
