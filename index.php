<?php

ob_start();

define("TERABLAZE_VERSION", '0.1.0-alpha1');

// update the paths below if you plan to rename or move these paths in your Operating system's filesystem'
define("SYSTEM_DIR", __DIR__.'/terablaze/');
define("APPLICATION_DIR", __DIR__ . '/application/');

include_once APPLICATION_DIR."configuration/configuration.php";


define("DEBUG", TRUE);

if(DEBUG){
	error_reporting(-1);
	ini_set('display_errors', 1);
} else {
	ini_set('display_errors', 0);
	if (version_compare(PHP_VERSION, '5.3', '>='))
	{
		error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
	}
	else
	{
		error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
	}
}


include_once SYSTEM_DIR."Functions.php";

include_once APPLICATION_DIR."configuration/constants.php";

try
{
	// core

	require_once(SYSTEM_DIR . "Core.php");

	TeraBlaze\Core::initialize();

	// plugins

	$path = APPLICATION_DIR."plugins";
	$iterator = new DirectoryIterator($path);

	foreach ($iterator as $item)
	{
		if (!$item->isDot() && $item->isDir())
		{
			include($path . "/" . $item->getFilename() . "/initialize.php");
		}
	}

// configuration

	$configuration = new TeraBlaze\Configuration(array(
		"type" => "phparray"
	));
	TeraBlaze\Registry::set("configuration", $configuration->initialize());
	require_once APPLICATION_DIR."configuration/autoload.php";

	$config = '';

	$conf = "default";

	if ($configuration)
	{
		$configuration = $configuration->initialize();
		$parsed = $configuration->parse("configuration/configuration");

		if (!empty($parsed->configuration->{$conf}))
		{
			$config = $parsed->configuration->{$conf};
		}
	}

// router
	$router = new TeraBlaze\Router(array(
		"url" => isset($_SERVER[$config->uri_protocol]) ? substr($_SERVER[$config->uri_protocol], 1) : $config->default_controller_action,
		"extension" => isset($_SERVER[$config->uri_protocol]) ? substr($_SERVER[$config->uri_protocol], 1) : $config->url_suffix
	));
	TeraBlaze\Registry::set("router", $router);

	// include custom routes

	include("routes.php");

	// dispatch + cleanup

	$router->dispatch();

	// unset globals

	unset($configuration);
}
catch (Exception $e)
{
	// list exceptions

	$exceptions = array(
		"500" => array(
			"TeraBlaze\Libraries\Cache\Exception",
			"TeraBlaze\Libraries\Cache\Exception\Argument",
			"TeraBlaze\Libraries\Cache\Exception\Implementation",
			"TeraBlaze\Libraries\Cache\Exception\Service",

			"TeraBlaze\Configuration\Exception",
			"TeraBlaze\Configuration\Exception\Argument",
			"TeraBlaze\Configuration\Exception\Implementation",
			"TeraBlaze\Configuration\Exception\Syntax",

			"TeraBlaze\Controller\Exception",
			"TeraBlaze\Controller\Exception\Argument",
			"TeraBlaze\Controller\Exception\Implementation",

			"TeraBlaze\Core\Exception",
			"TeraBlaze\Core\Exception\Argument",
			"TeraBlaze\Core\Exception\Implementation",
			"TeraBlaze\Core\Exception\Property",
			"TeraBlaze\Core\Exception\ReadOnly",
			"TeraBlaze\Core\Exception\WriteOnly",

			"TeraBlaze\Libraries\Database\Exception",
			"TeraBlaze\Libraries\Database\Exception\Argument",
			"TeraBlaze\Libraries\Database\Exception\Implementation",
			"TeraBlaze\Libraries\Database\Exception\Service",
			"TeraBlaze\Libraries\Database\Exception\Sql",

			"TeraBlaze\Model\Exception",
			"TeraBlaze\Model\Exception\Argument",
			"TeraBlaze\Model\Exception\Connector",
			"TeraBlaze\Model\Exception\Implementation",
			"TeraBlaze\Model\Exception\Primary",
			"TeraBlaze\Model\Exception\Type",
			"TeraBlaze\Model\Exception\Validation",

			"TeraBlaze\Request\Exception",
			"TeraBlaze\Request\Exception\Argument",
			"TeraBlaze\Request\Exception\Implementation",
			"TeraBlaze\Request\Exception\Response",

			"TeraBlaze\Router\Exception",
			"TeraBlaze\Router\Exception\Argument",
			"TeraBlaze\Router\Exception\Implementation",

			"TeraBlaze\Libraries\Session\Exception",
			"TeraBlaze\Libraries\Session\Exception\Argument",
			"TeraBlaze\Libraries\Session\Exception\Implementation",

			"TeraBlaze\Libraries\Template\Exception",
			"TeraBlaze\Libraries\Template\Exception\Argument",
			"TeraBlaze\Libraries\Template\Exception\Implementation",
			"TeraBlaze\Libraries\Template\Exception\Parser",

			"TeraBlaze\View\Exception",
			"TeraBlaze\View\Exception\Argument",
			"TeraBlaze\View\Exception\Data",
			"TeraBlaze\View\Exception\Implementation",
			"TeraBlaze\View\Exception\Renderer",
			"TeraBlaze\View\Exception\Syntax"
		),
		"404" => array(
			"TeraBlaze\Router\Exception\Action",
			"TeraBlaze\Router\Exception\Controller",
			"TeraBlaze\Router\Exception\RequestMethod"
		)
	);

	$exception = get_class($e);

	// attempt to find the approapriate template, and render

	foreach ($exceptions as $template => $classes)
	{
		foreach ($classes as $class)
		{
			if ($class == $exception)
			{
				header("Content-type: text/html");
				include(APPLICATION_DIR."views/errors/{$template}.php");
				exit;
			}
		}
	}

	// render fallback template

	header("Content-type: text/html");
	echo "An error occurred.";
	exit;
}

flush();
ob_flush();
ob_end_clean();