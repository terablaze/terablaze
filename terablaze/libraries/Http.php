<?php

namespace TeraBlaze\Libraries;
use TeraBlaze\Events;
use TeraBlaze\Registry;
use TeraBlaze\RequestMethods;

/**
 * Class Http
 * @package TeraBlaze\Libraries
 *
 * Extends the core Http class
 * for easy loading in controllers and models
 */
class Http extends \TeraBlaze\Http
{
	public function initialize($http_conf = "default")
	{
		$configuration = Registry::get("configuration");

		if ($configuration) {
			$configuration = $configuration->initialize();
			$parsed = $configuration->parse("configuration/http");

			if (!empty($parsed->http->{$http_conf})) {
				$this->agent = empty($parsed->http->{$http_conf}->agent)? RequestMethods::server("HTTP_USER_AGENT", "Curl/PHP ".PHP_VERSION) : $parsed->http->{$http_conf}->agent;
				// $this->options = (array)$parsed->cookie->{$http_conf};
			}
		}

		return $this;

	}
}