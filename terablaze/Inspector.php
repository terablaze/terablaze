<?php
/**
 * Created by TeraBoxX.
 * User: tommy
 * Date: 11/27/2016
 * Time: 11:53 AM
 */

namespace TeraBlaze;

use TeraBlaze\ArrayMethods as ArrayMethods;
use TeraBlaze\StringMethods as StringMethods;

/**
 * Class Inspector
 * @package TeraBlaze
 *
 * handles inspection of class, method and property metas
 */
class Inspector
{
	protected $_class;

	protected $_meta = array(
		"class" => array(),
		"properties" => array(),
		"methods" => array()
	);

	protected $_properties = array();
	protected $_methods = array();

	/**
	 * Inspector constructor.
	 * @param $class
	 */
	public function __construct($class)
	{
		$this->_class = $class;
	}

	/**
	 * @return string
	 *
	 * gets the comments of a class
	 */
	protected function _getClassComment()
	{
		$reflection = new \ReflectionClass($this->_class);
		return $reflection->getDocComment();
	}

	/**
	 * @return \ReflectionProperty[]
	 *
	 * gets the properties of a class
	 */
	protected function _getClassProperties()
	{
		$reflection = new \ReflectionClass($this->_class);
		return $reflection->getProperties();
	}

	/**
	 * @return \ReflectionMethod[]
	 *
	 * gets the methods of a class
	 */
	protected function _getClassMethods()
	{
		$reflection = new \ReflectionClass($this->_class);
		return $reflection->getMethods();
	}

	/**
	 * @param $property
	 * @return string
	 *
	 * gets the comments of a property
	 */
	protected function _getPropertyComment($property)
	{
		$reflection = new \ReflectionProperty($this->_class, $property);
		return $reflection->getDocComment();
	}

	/**
	 * @param $method
	 * @return string
	 *
	 * gets the comments of a method
	 */
	protected function _getMethodComment($method)
	{
		$reflection = new \ReflectionMethod($this->_class, $method);
		return $reflection->getDocComment();
	}

	/**
	 * @param $comment
	 * @return array
	 *
	 * detects and passing the metas in a DocComment for further processing
	 */
	protected function _parse($comment)
	{
		$meta = array();
		$pattern = "(@[a-zA-Z]+\s*[a-zA-Z0-9, ()_]*)";
		$matches = StringMethods::match($comment, $pattern);

		if ($matches != null) {
			foreach ($matches as $match) {
				$parts = ArrayMethods::clean(
					ArrayMethods::trim(
						StringMethods::split($match, "[\s]", 2)
					)
				);

				$meta[$parts[0]] = true;

				if (sizeof($parts) > 1) {
					$meta[$parts[0]] = ArrayMethods::clean(
						ArrayMethods::trim(
							StringMethods::split($parts[1], ",")
						)
					);
				}
			}
		}

		return $meta;
	}

	/**
	 * @return array|null
	 *
	 * gets the metas in the DocComment of a class
	 * by wrapping around the _getClassComment() method
	 */
	public function getClassMeta()
	{
		if (!isset($_meta["class"])) {
			$comment = $this->_getClassComment();

			if (!empty($comment)) {
				$_meta["class"] = $this->_parse($comment);
			} else {
				$_meta["class"] = null;
			}
		}

		return $_meta["class"];
	}

	/**
	 * @return array
	 *
	 * gets the properties of a class
	 * by wrapping around the _getClassProperties() method
	 */
	public function getClassProperties()
	{
		if (!isset($_properties)) {
			$properties = $this->_getClassProperties();

			foreach ($properties as $property) {
				$_properties[] = $property->getName();
			}
		}

		return $_properties;
	}

	/**
	 * @return array
	 *
	 * gets the methods of a class
	 * by wrapping around the _getClassMethods() method
	 */
	public function getClassMethods()
	{
		if (!isset($_methods)) {
			$methods = $this->_getClassMethods();

			foreach ($methods as $method) {
				$_methods[] = $method->getName();
			}
		}

		return $_methods;
	}

	/**
	 * @param $property
	 * @return mixed
	 *
	 * gets the metas in the DocComment of a property
	 * by wrapping around the _getPropertyComment() method
	 */
	public function getPropertyMeta($property)
	{
		if (!isset($_meta["properties"][$property])) {
			$comment = $this->_getPropertyComment($property);

			if (!empty($comment)) {
				$_meta["properties"][$property] = $this->_parse($comment);
			} else {
				$_meta["properties"][$property] = null;
			}
		}

		return $_meta["properties"][$property];
	}

	/**
	 * @param $method
	 * @return mixed
	 *
	 * gets the metas in the DocComment of a method
	 * by wrapping around the _getMethodComment() method
	 */
	public function getMethodMeta($method)
	{
		if (!isset($_meta["actions"][$method])) {
			$comment = $this->_getMethodComment($method);

			if (!empty($comment)) {
				$_meta["methods"][$method] = $this->_parse($comment);
			} else {
				$_meta["methods"][$method] = null;
			}
		}

		return $_meta["methods"][$method];
	}
}