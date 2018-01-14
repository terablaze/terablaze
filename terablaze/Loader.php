<?php
/**
 * Created by TeraBoxX.
 * User: tommy
 * Date: 2/13/2017
 * Time: 8:41 PM
 */

namespace TeraBlaze;

use TeraBlaze\ArrayMethods as ArrayMethods;
use TeraBlaze\StringMethods as StringMethods;
use TeraBlaze\Inspector as Inspector;
use TeraBlaze\Core\Exception as Exception;

/**
 * Class Loader
 * @package TeraBlaze
 *
 * used to load models, views, libraries
 */
class Loader
{
	/**
	 * @param $model
	 * @param array $parameters
	 * @return object|\ReflectionClass
	 *
	 * includes and instantiates the specified $model
	 * parsing the values in the $parameters array to its constructor if not empty
	 */
	public function model($model, $parameters = array())
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
				if(version_compare(phpversion(), '5.6.0', '>=')){
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
	 * @return Exception\Argument
	 *
	 * includes the specified $view_file and extracts the $view_vars
	 * for use in the view
	 */
	public function view($view_file, $view_vars = array())
	{
		Events::fire("terablaze.loader.view.before", array($view_file, $view_vars));
		@extract($view_vars);

		$ext = pathinfo($view_file, PATHINFO_EXTENSION);
		$view_file = ($ext === '') ? $view_file.'.php' : $view_file;
		$view_file = str_replace("::", "/", $view_file);
		$filename = APPLICATION_DIR . 'views/' . $view_file;
		if (file_exists($filename)) {
			include $filename;
		} else {
			Events::fire("terablaze.loader.view.error", array($view_file, $view_vars));
			return new Exception\Argument("Trying to Load Non Existing View: {$view_file}");
		}
		Events::fire("terablaze.loader.view.after", array($view_file, $view_vars));
	}

	/**
	 * @param $library
	 * @param array $parameters
	 * @return mixed|null|object|\ReflectionClass
	 *
	 * includes and instantiated the specified $library
	 * parsing the values in the $parameters array to its constructor if not empty
	 */
	public function library($library, $parameters = array())
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

}