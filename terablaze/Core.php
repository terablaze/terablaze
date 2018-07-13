<?php

namespace TeraBlaze;

use TeraBlaze\Core\Exception as Exception;

/**
 * Class Core
 * @package TeraBlaze
 *
 * handles autoloading of classes
 */
class Core
{
	private static $_loaded = array();

	private static $_paths = array(
		"controllers",
		"libraries",
		"models",
		""
	);

	/**
	 * @throws Exception
	 */
	public static function initialize()
	{
		if (!defined("APPLICATION_DIR") || !defined("SYSTEM_DIR"))
		{
			throw new \Exception("System and Application paths not properly defined");
		}

		require_once(SYSTEM_DIR . 'core/compat/mbstring.php');
		require_once(SYSTEM_DIR . 'core/compat/hash.php');
		require_once(SYSTEM_DIR . 'core/compat/standard.php');

		// fix extra backslashes in $_POST/$_GET

		if (get_magic_quotes_gpc())
		{
			$globals = array("_POST", "_GET", "_COOKIE", "_REQUEST", "_SESSION");

			foreach ($globals as $global)
			{
				if (isset($GLOBALS[$global]))
				{
					$GLOBALS[$global] = self::_clean($GLOBALS[$global]);
				}
			}
		}

		// start autoloading

		$paths1 = array_map(function($item) {
			return APPLICATION_DIR.$item;
		}, self::$_paths);
		$paths2 = array_map(function($item) {
			return SYSTEM_DIR.$item;
		}, self::$_paths);

		$paths = array_merge($paths1, $paths2);

		$paths[] = get_include_path();
		set_include_path(join(PATH_SEPARATOR, $paths));
		spl_autoload_register(__CLASS__."::_autoload");
	}

	/**
	 * @param $array
	 * @return array|string
	 */
	protected static function _clean($array)
	{
		if (is_array($array))
		{
			return array_map(__CLASS__."::_clean", $array);
		}
		return stripslashes($array);
	}

	/**
	 * @param $class
	 * @throws Exception
	 *
	 * does the actual class autoloading
	 */
	protected static function _autoload($class)
	{
		$paths = explode(PATH_SEPARATOR, get_include_path());
		$flags = PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE;
		$file = (str_replace("\\", DIRECTORY_SEPARATOR, trim($class, "\\"))).".php";
		$file = preg_replace('/^'.preg_quote("TeraBlaze", '/').'/', "", $file, 1);
		$file = explode("/", $file);
		$num_of_parts = sizeof($file);

		$file0 = implode("/", $file);
		foreach ($paths as $path)
		{
			$combined0 = $path.DIRECTORY_SEPARATOR.$file0;
			$combined0 = str_replace("\\", "/", $combined0);
			$combined0 = str_replace("//", "/", $combined0);
			$combined0 = str_replace("//", "/", $combined0);

			if (file_exists($combined0))
			{
				include_once($combined0);

				return;
			}
		}


		for($i = 0; $i < $num_of_parts-1; $i++){
			$file[$i] = strtolower($file[$i]);
			$file[$num_of_parts-1] = ucfirst($file[$num_of_parts-1]);
		}
		$file1 = implode("/", $file);
		foreach ($paths as $path)
		{
			$combined1 = $path.DIRECTORY_SEPARATOR.$file1;
			$combined1 = str_replace("\\", "/", $combined1);
			$combined1 = str_replace("//", "/", $combined1);
			$combined1 = str_replace("//", "/", $combined1);
			if (file_exists($combined1))
			{
				include_once($combined1);

				return;
			}
		}


		for($i = 0; $i < $num_of_parts; $i++){
			$file[$i] = strtolower($file[$i]);
		}
		$file2 = implode("/", $file);
		foreach ($paths as $path)
		{
			$combined2 = $path.DIRECTORY_SEPARATOR.$file2;
			$combined2 = str_replace("\\", "/", $combined2);
			$combined2 = str_replace("//", "/", $combined2);
			$combined2 = str_replace("//", "/", $combined2);

			if (file_exists($combined2))
			{
				include_once($combined2);

				return;
			}
		}

		throw new Exception("{$class} not found");
	}
}
