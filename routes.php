<?php

// define routes

$routes = array(
	/****************************
	 ******* Sample route *******
	array(
		"pattern" => "home-page",
		"controller" => "Home",
		"action" => "index"
	)
	 ****************************/
);

// add defined routes
if(!empty($routes) && is_array($routes)) {
	foreach ($routes as $route) {
		$router->addRoute(new TeraBlaze\Router\Route\Simple($route));
	}
}

// unset globals

unset($routes);
