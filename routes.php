<?php

// define routes

$routes = array(
	/****************************
	 ******* Sample route *******
	array(
	"pattern" => "home-page",
	"controller" => "Home",
	"action" => "index"
	),
	 ****************************/


	/****************************
	 ******* With request method *******
	array(
	"pattern" => "home-page",
	"controller" => "Home",
	"action" => "index",
	"method" => "post"
	),
	 ***********************************/


	/****************************
	 ******* With multiple request method *******
	array(
	"pattern" => "home-page",
	"controller" => "Home",
	"action" => "index",
	"method" => ["options", "post"]
	),
	 ***********************************/
);

// add defined routes
if(!empty($routes) && is_array($routes)) {
	foreach ($routes as $route) {
		$router->addRoute(new TeraBlaze\Router\Route\Simple($route));
	}
}

// unset globals

unset($routes);
