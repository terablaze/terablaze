<?php

namespace TeraBlaze;

use TeraBlaze\Base as Base;
use TeraBlaze\Events as Events;
use TeraBlaze\Registry as Registry;
use TeraBlaze\Inspector as Inspector;
use TeraBlaze\Router\Exception as Exception;

/**
 * Class Router
 * @package TeraBlaze
 *
 * handles url routing
 */
class Router extends Base
{
	/**
	 * @readwrite
	 */
	protected $_url;

	/**
	 * @readwrite
	 */
	protected $_extension;

	/**
	 * @read
	 */
	protected $_controller;

	/**
	 * @read
	 */
	protected $_action;

	protected $_routes = array();

	/**
	 * @param $method
	 * @return Exception\Implementation
	 */
	public function _getExceptionForImplementation($method)
	{
		return new Exception\Implementation("{$method} method not implemented");
	}

	/**
	 * @param $route
	 * @return $this
	 */
	public function addRoute($route)
	{
		$this->_routes[] = $route;
		return $this;
	}

	/**
	 * @param $route
	 * @return $this
	 */
	public function removeRoute($route)
	{
		foreach ($this->_routes as $i => $stored)
		{
			if ($stored == $route)
			{
				unset($this->_routes[$i]);
			}
		}
		return $this;
	}

	/**
	 * @return array
	 */
	public function getRoutes()
	{
		$list = array();

		foreach ($this->_routes as $route)
		{
			$list[$route->pattern] = get_class($route);
		}

		return $list;
	}

	/**
	 * @param $controller
	 * @param $action
	 * @param array $parameters
	 * @throws Exception\Action
	 * @throws Exception\Controller
	 */
	protected function _pass($controller, $action, $parameters = array(), $method='')
	{
		$name = ucfirst($controller);

		$this->_controller = $controller;
		$this->_action = $action;
		$this->_method = $method;

		Events::fire("terablaze.router.controller.before", array($controller, $parameters));

		try
		{
			if(class_exists($name)) {
				$instance = new $name(array(
					"parameters" => $parameters
				));
			}else{
				throw new Exception\Controller("Controller {$name} not found");
			}
			Registry::set("controller", $instance);
		}
		catch (\Exception $e)
		{
			throw new Exception\Controller("Controller {$name} not found");
		}

		Events::fire("terablaze.router.controller.after", array($controller, $parameters));

		if (!method_exists($instance, $action))
		{
			throw new Exception\Action("Action {$action} not found");
		}

		$inspector = new Inspector($instance);
		$methodMeta = $inspector->getMethodMeta($action);

		if (!empty($methodMeta["@protected"]) || !empty($methodMeta["@private"]))
		{
			throw new Exception\Action("Action {$action} not publicly accessible");
		}

		$hooks = function($meta, $type) use ($inspector, $instance)
		{
			if (isset($meta[$type]))
			{
				$run = array();

				foreach ($meta[$type] as $method)
				{
					$hookMeta = $inspector->getMethodMeta($method);

					if (in_array($method, $run) && !empty($hookMeta["@once"]))
					{
						continue;
					}

					$instance->$method();
					$run[] = $method;
				}
			}
		};

		Events::fire("terablaze.router.beforehooks.before", array($action, $parameters));

		$hooks($methodMeta, "@before");

		Events::fire("terablaze.router.beforehooks.after", array($action, $parameters));

		Events::fire("terablaze.router.action.before", array($action, $parameters));

		call_user_func_array(array(
			$instance,
			$action
		), is_array($parameters) ? $parameters : array());

		Events::fire("terablaze.router.action.after", array($action, $parameters));
		Events::fire("terablaze.router.afterhooks.before", array($action, $parameters));

		$hooks($methodMeta, "@after");

		Events::fire("terablaze.router.afterhooks.after", array($action, $parameters));

		// unset controller

		Registry::erase("controller");
	}

	/**
	 *
	 */
	public function dispatch()
	{
		$url= $this->url;
		$parameters = array();
		$controller = get_config('default_controller');
		$action = get_config('default_action');

		Events::fire("terablaze.router.dispatch.before", array($url));

		foreach ($this->_routes as $route)
		{
			$matches = $route->matches($url);
			if ($matches)
			{
				$controller = $route->controller;
				$action = $route->action;
				$parameters = $route->parameters;
				$method = $route->method;

				Events::fire("terablaze.router.dispatch.after", array($url, $controller, $action, $parameters, $method));

				if((strtolower($_SERVER['REQUEST_METHOD']) == $method) || empty($method) ) {
					$this->_pass($controller, $action, $parameters, $method);
					return;
				} else {
					http_response_code(405);
					throw new Exception\RequestMethod("Request method {$_SERVER['REQUEST_METHOD']} not implemented for this endpoint");
				}
			}
		}

		$parts = explode("/", trim($url, "/"));

		if(!empty($url) && $url != '/') {
			if (sizeof($parts) > 0) {
				$controller = $parts[0];

				if (sizeof($parts) >= 2) {
					$action = $parts[1];
					$parameters = array_slice($parts, 2);
				}
			}
		}

		Events::fire("terablaze.router.dispatch.after", array($url, $controller, $action, $parameters));
		$this->_pass($controller, $action, $parameters);
	}
}