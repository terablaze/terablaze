<?php
/**
 * Created by TeraBoxX.
 * User: tommy
 * Date: 2/28/2017
 * Time: 6:09 PM
 */

namespace TeraBlaze\Libraries;

use TeraBlaze\Base as Base;
use TeraBlaze\Events as Events;
use TeraBlaze\Registry as Registry;
use TeraBlaze\Libraries\Cookie\Exception as Exception;


class Cookie extends Base
{
	/**
	 * @readwrite
	 */
	protected $_type;

	/**
	 * @readwrite
	 */
	protected $_options;
	
	public function initialize($cookie_conf = "default")
	{
		if(\TeraBlaze\Registry::get(get_config('app_id').'cookie_'.$cookie_conf, FALSE)){
			return \TeraBlaze\Registry::get(get_config('app_id').'cookie_'.$cookie_conf, FALSE);
		}
		Events::fire("terablaze.libraries.cookie.initialize.before", array($this->type, $this->options));

		$configuration = Registry::get("configuration");

		if ($configuration) {
			$configuration = $configuration->initialize();
			$parsed = $configuration->parse("configuration/cookie");

			if (!empty($parsed->cookie->{$cookie_conf})) {
				$this->type = $parsed->cookie->{$cookie_conf}->type;
				$this->options = (array)$parsed->cookie->{$cookie_conf};
			}
		}
		Events::fire("terablaze.libraries.cookie.initialize.after", array($this->type, $this->options));

		switch ($this->type)
		{
			case "default":
			{
				$cookie = new Cookie\Driver\DefaultCookie($this->options);
				\TeraBlaze\Registry::set(get_config('app_id').'cookie_'.$cookie_conf, $cookie);
				return $cookie;
				break;
			}
			default:
			{
				throw new Exception\Argument("Invalid type");
				break;
			}
		}
	}
}