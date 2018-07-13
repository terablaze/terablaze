<?php
/**
 * Created by TeraBoxX.
 * User: tommy
 * Date: 1/29/2017
 * Time: 5:29 PM
 */

namespace TeraBlaze;

use TeraBlaze\ArrayMethods as ArrayMethods;
use TeraBlaze\StringMethods as StringMethods;
use TeraBlaze\Inspector as Inspector;
use TeraBlaze\Core\Exception as Exception;

/**
 * Class Base
 * @package TeraBlaze
 *
 * this is the base class that most of the other framework classes inherit
 *
 * all application classes including models, controllers and libraries
 * also inherit the base class
 */
class Base
{
	private $_inspector;

	/**
	 * Base constructor.
	 * @param array $options
	 */
	public function __construct($options = array())
	{
		$this->_inspector = new Inspector($this);

		if (is_array($options) || is_object($options)) {
			foreach ($options as $key => $value) {
				$key = ucfirst($key);
				$method = "set{$key}";
				$this->$method($value);
			}
		}
	}

	/**
	 * @param $name
	 * @param $arguments
	 * @return $this|null
	 * @throws Exception
	 * @throws Exception\ReadOnly
	 * @throws Exception\WriteOnly
	 */
	public function __call($name, $arguments)
	{
		if (empty($this->_inspector)) {
			throw new Exception("Call parent::__construct!");
		}

		$getMatches = StringMethods::match($name, "^get([a-zA-Z0-9]+)$");
		if (sizeof($getMatches) > 0) {
			$normalized = lcfirst($getMatches[0]);
			$property = "_{$normalized}";

			if (property_exists($this, $property)) {
				$meta = $this->_inspector->getPropertyMeta($property);

				if (empty($meta["@readwrite"]) && empty($meta["@read"])) {
					throw $this->_getExceptionForWriteonly($normalized);
				}

				if (isset($this->$property)) {
					return $this->$property;
				}

				return null;
			}
		}

		$setMatches = StringMethods::match($name, "^set([a-zA-Z0-9]+)$");
		if (sizeof($setMatches) > 0) {
			$normalized = lcfirst($setMatches[0]);
			$property = "_{$normalized}";

			if (property_exists($this, $property)) {
				$meta = $this->_inspector->getPropertyMeta($property);

				if (empty($meta["@readwrite"]) && empty($meta["@write"])) {
					throw $this->_getExceptionForReadonly($normalized);
				}

				$this->$property = $arguments[0];
				return $this;
			}
		}

		//throw $this->_getExceptionForImplementation($name);
	}

	/**
	 * @param $name
	 * @return mixed
	 */
	public function __get($name)
	{
		$function = "get" . ucfirst($name);
		return $this->$function();
	}

	/**
	 * @param $name
	 * @param $value
	 * @return mixed
	 */
	public function __set($name, $value)
	{
		$function = "set" . ucfirst($name);
		return $this->$function($value);
	}

	/**
	 * @param $property
	 * @return Exception\ReadOnly
	 */
	protected function _getExceptionForReadonly($property)
	{
		return new Exception\ReadOnly("{$property} is read-only");
	}

	/**
	 * @param $property
	 * @return Exception\WriteOnly
	 */
	protected function _getExceptionForWriteonly($property)
	{
		return new Exception\WriteOnly("{$property} is write-only");
	}

	/**
	 * @return Exception\Property
	 */
	protected function _getExceptionForProperty()
	{
		return new Exception\Property("Invalid property");
	}

	/**
	 * @param $method
	 * @return Exception\Argument
	 */
	protected function _getExceptionForImplementation($method)
	{
		return new Exception\Argument("{$method} method not implemented");
	}

	/**
	 * @param $model
	 * @param array $parameters
	 * @return object|\ReflectionClass
	 *
	 * includes and instantiates the specified $model
	 * parsing the values in the $parameters array to its constructor if not empty
	 */
	public function load_model($model, $parameters = array())
	{
		Events::fire("terablaze.loader.model.before", array($model, $parameters));
		$model_class = explode("/", $model);
		$model_class = $model_class[(sizeof($model_class)) - 1];

		$model_file = $model . ".php";
		$filename = APPLICATION_DIR . 'models/' . $model_file;
		$filename = str_replace("\\", "/", $filename);
		if (file_exists($filename)) {
			include_once $filename;

			if(empty($parameters)) {
				$initialized_model = new $model_class();
			} else {
				if(version_compare(phpversion(), '5.6.0', '>')){
					$initialized_model = new $model_class(...$parameters);
				} else {
					$initialized_model = new \ReflectionClass($model_class);
					$initialized_model = $initialized_model->newInstanceArgs($parameters);
				}
			}
			Events::fire("terablaze.loader.model.after", array($model, $parameters));
			return $initialized_model;
		} else {
			die("Trying to Load Non Existing Model: {$model}");
		}
	}

	/**
	 * @param $view_file
	 * @param array $view_vars
	 * @param $return
	 *
	 * @return bool|Exception\Argument
	 *
	 * includes the specified $view_file and extracts the $view_vars
	 * for use in the view
	 */
	public function load_view($view_file, $view_vars = array(), $return = FALSE)
	{
		Events::fire("terablaze.loader.view.before", array($view_file, $view_vars));

		$ext = pathinfo($view_file, PATHINFO_EXTENSION);
		$view_file = ($ext === '') ? $view_file.'.php' : $view_file;
		$view_file = str_replace("::", "/", $view_file);
		$filename = APPLICATION_DIR . 'views/' . $view_file;

		if (file_exists($filename)) {

			if((boolean) $return) {

				$string = get_include_contents($filename, $view_vars);
				return $string;

			} else {
				@extract($view_vars);
				include $filename;
			}
		} else {
			Events::fire("terablaze.loader.view.error", array($view_file, $view_vars));
			return new Exception\Argument("Trying to Load Non Existing View: {$view_file}");
		}
		Events::fire("terablaze.loader.view.after", array($view_file, $view_vars));

		return TRUE;
	}

	/**
	 * @param $library
	 * @param array $parameters
	 * @return mixed|null|object|\ReflectionClass
	 *
	 * includes and instantiated the specified $library
	 * parsing the values in the $parameters array to its constructor if not empty
	 */
	public function load_library($library, $parameters = array())
	{
		Events::fire("terablaze.loader.library.before", array($library, $parameters));
		if (Registry::get("{$library}")) {
			return Registry::get("{$library}");
		}

		$library_class = explode("/", $library);
		$library_class = $library_class[(sizeof($library_class)) - 1];

		$library_file = ucfirst($library) . ".php";

		if (file_exists(APPLICATION_DIR . 'libraries/' . $library_file)) {

			include_once APPLICATION_DIR . 'libraries/' . $library_file;

			$library_to_initialize = "$library_class";

			if(empty($parameters)) {
				$initialized_library = new $library_to_initialize;
			} else {
				if(version_compare(phpversion(), '5.6.0', '>=')){
					$initialized_library = new $library_to_initialize(...$parameters);
				} else {
					$initialized_library = new \ReflectionClass($library_to_initialize);
					$initialized_library = $initialized_library->newInstanceArgs($parameters);
				}
			}
			Events::fire("terablaze.loader.library.after", array($library, $parameters));

			return $initialized_library;

		} elseif (file_exists(SYSTEM_DIR . 'libraries/' . $library_file)) {

			include_once SYSTEM_DIR . 'libraries/' . $library_file;

			if (class_exists("\\TeraBlaze\\Libraries\\{$library_class}")) {
				$library_to_initialize = "\\TeraBlaze\\Libraries\\{$library_class}";
			} else {
				$library_to_initialize = "{$library_class}";
			}

			if(empty($parameters)) {
				$initialized_library = new $library_to_initialize;
			} else {
				if(version_compare(phpversion(), '5.6.0', '>=')){
					$initialized_library = new $library_to_initialize(...$parameters);
				} else {
					$initialized_library = new \ReflectionClass($library_to_initialize);
					$initialized_library = $initialized_library->newInstanceArgs($parameters);
				}
			}

			Events::fire("terablaze.loader.library.after", array($library, $parameters));
			return $initialized_library;

		} else {
			Events::fire("terablaze.loader.library.error", array($library, $parameters));
			die("Trying to Load Non Existing Libary: {$library}");
		}
	}


	/**
	 * @param $helper_file
	 * @return Exception\Argument
	 *
	 * includes the specified $view_file and extracts the $view_vars
	 * for use in the view
	 */
	public function load_helper($helper_file)
	{
		Events::fire("terablaze.loader.helper.before", array($helper_file));

		$ext = pathinfo($helper_file, PATHINFO_EXTENSION);
		$helper_file = ($ext === '') ? $helper_file.'.php' : $helper_file;
		$helper_file = str_replace("::", "/", $helper_file);
		if (file_exists(APPLICATION_DIR . 'helpers/' . $helper_file)) {
			include_once APPLICATION_DIR . 'helpers/' . $helper_file;
		} elseif(file_exists(SYSTEM_DIR . 'helpers/' . $helper_file)) {
			include_once SYSTEM_DIR . 'helpers/' . $helper_file;
		}else {
			Events::fire("terablaze.loader.helper.error", array($helper_file));
			return new Exception\Argument("Trying to Load Non Existing Helper: {$helper_file}");
		}
		Events::fire("terablaze.loader.helper.after", array($helper_file));
		return;
	}

}
