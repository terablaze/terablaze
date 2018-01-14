<?php
/**
 * Created by TeraBoxX.
 * User: tommy
 * Date: 2/4/2017
 * Time: 9:15 AM
 */

namespace TeraBlaze\Libraries;

use TeraBlaze\Base as Base;
use TeraBlaze\Events as Events;
use TeraBlaze\Registry as Registry;
use TeraBlaze\Libraries\Session\Exception as Exception;

class Session extends Base
{
	/**
	 * @readwrite
	 */
	protected $_type;

	/**
	 * @readwrite
	 */
	protected $_options;

	protected function _getExceptionForImplementation($method)
	{
		return new Exception\Implementation("{$method} method not implemented");
	}

	public function initialize($session_conf = "default")
	{
		if(\TeraBlaze\Registry::get(get_config('app_id').'session_'.$session_conf, FALSE)){
			return \TeraBlaze\Registry::get(get_config('app_id').'session_'.$session_conf, FALSE);
		}
		Events::fire("terablaze.libraries.session.initialize.before", array($this->type, $this->options));
		
		if (!$this->type)
		{
			$configuration = Registry::get("configuration");

			if ($configuration)
			{
				$configuration = $configuration->initialize();
				$parsed = $configuration->parse("configuration/session");

				if (!empty($parsed->session->{$session_conf}) && !empty($parsed->session->{$session_conf}->type))
				{
					$this->type = $parsed->session->{$session_conf}->type;
					//unset($parsed->session->{$session_conf}->type);
					$this->options = (array) $parsed->session->{$session_conf};
				}
			}
		}

		if (!$this->type)
		{
			throw new Exception\Argument("Invalid type");
		}
		
		Events::fire("terablaze.libraries.session.initialize.after", array($this->type, $this->options));

		switch (strtolower($this->type))
		{
			case "server":
			{
				$session = new Session\Driver\Server($this->options);
				\TeraBlaze\Registry::set(get_config('app_id').'session_'.$session_conf, $session);
				return $session;
				break;
			}
			case "file":
			{
				$session = new Session\Driver\File($this->options);
				\TeraBlaze\Registry::set(get_config('app_id').'session_'.$session_conf, $session);
				return $session;
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