<?php

use Plugins\Logger\Logger;
use TeraBlaze\Events\Events;
use TeraBlaze\Ripana\Database\Drivers\Mysqli\Connector;

// initialize logger

$container = \TeraBlaze\Container\Container::getContainer();
/** @var \App\AppKernel $kernel */
$kernel = $container->get('app.kernel');

if ($kernel->isDebug()) {
	include("Logger.php");

	$logger = new Logger(array(
		"file" => $kernel->getLogsDir() . date("Y-F-d") . ".log"
	));

// log configuration events
	
	Events::add("terablaze.configuration.initialize.before", function ($type, $options) use ($logger) {
		$logger->log("terablaze.configuration.initialize.before: " . $type);
	});
	
	Events::add("terablaze.configuration.initialize.after", function ($type, $options) use ($logger) {
		$logger->log("terablaze.configuration.initialize.after: " . $type);
	});

// log controller events
	
	Events::add("terablaze.controller.construct.before", function ($name) use ($logger) {
		$logger->log("terablaze.controller.construct.before: " . $name);
	});
	
	Events::add("terablaze.controller.construct.after", function ($name) use ($logger) {
		$logger->log("terablaze.controller.construct.after: " . $name);
	});
	
	Events::add("terablaze.controller.render.before", function ($name) use ($logger) {
		$logger->log("terablaze.controller.render.before: " . $name);
	});
	
	Events::add("terablaze.controller.render.after", function ($name) use ($logger) {
		$logger->log("terablaze.controller.render.after: " . $name);
	});
	
	Events::add("terablaze.controller.destruct.before", function ($name) use ($logger) {
		$logger->log("terablaze.controller.destruct.before: " . $name);
	});
	
	Events::add("terablaze.controller.destruct.after", function ($name) use ($logger) {
		$logger->log("terablaze.controller.destruct.after: " . $name);
	});

// log request events
	
	Events::add("terablaze.request.request.before", function ($method, $url, $parameters) use ($logger) {
		$logger->log("terablaze.request.request.before: " . $method . ", " . $url);
	});
	
	Events::add("terablaze.request.request.after", function ($method, $url, $parameters, $response) use ($logger) {
		$logger->log("terablaze.request.request.after: " . $method . ", " . $url);
	});

// log router events
	
	Events::add("terablaze.router.dispatch.before", function ($url) use ($logger) {
		$logger->log("terablaze.router.dispatch.before: " . $url);
	});
	
	Events::add("terablaze.router.dispatch.after", function ($url, $controller, $action, $parameters) use ($logger) {
		$logger->log("terablaze.router.dispatch.after: " . $url . ", " . $controller . ", " . $action);
	});

// log view events
	
	Events::add("terablaze.view.load.before", function ($file, $vars) use ($logger) {
		$logger->log("terablaze.view.load.before: " . $file);
	});
	
	Events::add("terablaze.view.load.after", function ($file, $vars) use ($logger) {
		$logger->log("terablaze.view.load.after: " . $file);
	});
	
	
	/**
	 *
	 * the following log libraries events
	 *
	 */


// log cache events
	
	Events::add("terablaze.libraries.cache.initialize.before", function ($type, $options) use ($logger) {
		$logger->log("terablaze.libraries.cache.initialize.before: " . $type);
	});
	
	Events::add("terablaze.libraries.cache.initialize.after", function ($type, $options) use ($logger) {
		$logger->log("terablaze.libraries.cache.initialize.after: " . $type);
	});

// log database events
	
	Events::add("terablaze.libraries.database.initialize.before", function ($type, $options) use ($logger) {
		$logger->log("terablaze.libraries.database.initialize.before: " . $type);
	});
	
	Events::add("terablaze.libraries.database.initialize.after", function ($type, $options) use ($logger) {
		$logger->log("terablaze.libraries.database.initialize.after: " . $type);
	});

// log database query
    Events::add(Connector::QUERY_BEFORE_EVENT, function ($type, $options) use ($logger) {
        $logger->log(Connector::QUERY_BEFORE_EVENT . ": " . $type);
    });
    Events::add(Connector::QUERY_AFTER_EVENT, function ($type, $result) use ($logger) {
        $logger->log(Connector::QUERY_AFTER_EVENT . ": " . $type . json_encode($result, JSON_PRETTY_PRINT));
    });

// log session events
	
	Events::add("terablaze.libraries.session.initialize.before", function ($type, $options) use ($logger) {
		$logger->log("terablaze.libraries.session.initialize.before: " . $type);
	});
	
	Events::add("terablaze.libraries.session.initialize.after", function ($type, $options) use ($logger) {
		$logger->log("terablaze.libraries.session.initialize.after: " . $type);
	});

// log cookie events
	
	Events::add("terablaze.libraries.cookie.initialize.before", function ($type, $options) use ($logger) {
		$logger->log("terablaze.libraries.cookie.initialize.before: " . $type);
	});
	
	Events::add("terablaze.libraries.cookie.initialize.after", function ($type, $options) use ($logger) {
		$logger->log("terablaze.libraries.cookie.initialize.after: " . $type);
	});
}