<?php

// initialize logger

include("logger.php");

$logger = new Logger(array(
    "file" => APPLICATION_DIR . 'logs/' . date("Y-F-d") . ".log"
));

// log configuration events

TeraBlaze\Events::add("terablaze.configuration.initialize.before", function($type, $options) use ($logger)
{
    $logger->log("terablaze.configuration.initialize.before: " . $type);
});

TeraBlaze\Events::add("terablaze.configuration.initialize.after", function($type, $options) use ($logger)
{
    $logger->log("terablaze.configuration.initialize.after: " . $type);
});

// log controller events

TeraBlaze\Events::add("terablaze.controller.construct.before", function($name) use ($logger)
{
    $logger->log("terablaze.controller.construct.before: " . $name);
});

TeraBlaze\Events::add("terablaze.controller.construct.after", function($name) use ($logger)
{
    $logger->log("terablaze.controller.construct.after: " . $name);
});

TeraBlaze\Events::add("terablaze.controller.render.before", function($name) use ($logger)
{
    $logger->log("terablaze.controller.render.before: " . $name);
});

TeraBlaze\Events::add("terablaze.controller.render.after", function($name) use ($logger)
{
    $logger->log("terablaze.controller.render.after: " . $name);
});

TeraBlaze\Events::add("terablaze.controller.destruct.before", function($name) use ($logger)
{
    $logger->log("terablaze.controller.destruct.before: " . $name);
});

TeraBlaze\Events::add("terablaze.controller.destruct.after", function($name) use ($logger)
{
    $logger->log("terablaze.controller.destruct.after: " . $name);
});

// log request events

TeraBlaze\Events::add("terablaze.request.request.before", function($method, $url, $parameters) use ($logger)
{
    $logger->log("terablaze.request.request.before: " . $method . ", " . $url);
});

TeraBlaze\Events::add("terablaze.request.request.after", function($method, $url, $parameters, $response) use ($logger)
{
    $logger->log("terablaze.request.request.after: " . $method . ", " . $url);
});

// log router events

TeraBlaze\Events::add("terablaze.router.dispatch.before", function($url) use ($logger)
{
    $logger->log("terablaze.router.dispatch.before: " . $url);
});

TeraBlaze\Events::add("terablaze.router.dispatch.after", function($url, $controller, $action, $parameters) use ($logger)
{
    $logger->log("terablaze.router.dispatch.after: " . $url . ", " . $controller . ", " . $action);
});

// log view events

TeraBlaze\Events::add("terablaze.view.load.before", function($file, $vars) use ($logger)
{
	$logger->log("terablaze.view.load.before: " . $file);
});

TeraBlaze\Events::add("terablaze.view.load.after", function($file, $vars) use ($logger)
{
	$logger->log("terablaze.view.load.after: " . $file);
});


/**
 *
 * the following log libraries events
 *
*/


// log cache events

TeraBlaze\Events::add("terablaze.libraries.cache.initialize.before", function($type, $options) use ($logger)
{
	$logger->log("terablaze.libraries.cache.initialize.before: " . $type);
});

TeraBlaze\Events::add("terablaze.libraries.cache.initialize.after", function($type, $options) use ($logger)
{
	$logger->log("terablaze.libraries.cache.initialize.after: " . $type);
});

// log database events

TeraBlaze\Events::add("terablaze.libraries.database.initialize.before", function($type, $options) use ($logger)
{
	$logger->log("terablaze.libraries.database.initialize.before: " . $type);
});

TeraBlaze\Events::add("terablaze.libraries.database.initialize.after", function($type, $options) use ($logger)
{
	$logger->log("terablaze.libraries.database.initialize.after: " . $type);
});

// log database query
TeraBlaze\Events::add("terablaze.libraries.database.query", function($type, $options) use ($logger)
{
	$logger->log("terablaze.libraries.database.query: " . $type);
});

// log session events

TeraBlaze\Events::add("terablaze.libraries.session.initialize.before", function($type, $options) use ($logger)
{
	$logger->log("terablaze.libraries.session.initialize.before: " . $type);
});

TeraBlaze\Events::add("terablaze.libraries.session.initialize.after", function($type, $options) use ($logger)
{
	$logger->log("terablaze.libraries.session.initialize.after: " . $type);
});

// log cookie events

TeraBlaze\Events::add("terablaze.libraries.cookie.initialize.before", function($type, $options) use ($logger)
{
	$logger->log("terablaze.libraries.cookie.initialize.before: " . $type);
});

TeraBlaze\Events::add("terablaze.libraries.cookie.initialize.after", function($type, $options) use ($logger)
{
	$logger->log("terablaze.libraries.cookie.initialize.after: " . $type);
});
