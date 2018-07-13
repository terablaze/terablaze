<?php
/**
 * Created by TeraBoxX.
 * User: tommy
 * Date: 2/1/2017
 * Time: 3:52 AM
 */

namespace TeraBlaze\Libraries;

use TeraBlaze\Base as Base;
use TeraBlaze\Events as Events;
use TeraBlaze\Registry as Registry;
use TeraBlaze\Libraries\Ftp\Exception as Exception;


class Ftp extends Base
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

	public function initialize($ftp_conf = "default")
	{
		if(\TeraBlaze\Registry::get(get_config('app_id').'ftp_'.$ftp_conf, FALSE)){
			return \TeraBlaze\Registry::get(get_config('app_id').'ftp_'.$ftp_conf, FALSE);
		}
		Events::fire("terablaze.libraries.ftp.initialize.before", array($this->type, $this->options));
		$type = $this->getType();
		if (!$this->type)
		{
			$configuration = Registry::get("configuration");

			if ($configuration)
			{
				$configuration = $configuration->initialize();
				$parsed = $configuration->parse("configuration/ftp");

				if (!empty($parsed->ftp->{$ftp_conf}) && !empty($parsed->ftp->{$ftp_conf}->type))
				{
					$this->type = $parsed->ftp->{$ftp_conf}->type;
					//unset($parsed->ftp->{$ftp_conf}->type);
					$this->options = (array) $parsed->ftp->{$ftp_conf};
				}
			}
		}

		//if (!$this->type)
		//{
		//	throw new Exception\Argument("Invalid type");
		//}

		Events::fire("terablaze.libraries.ftp.initialize.after", array($this->type, $this->options));

		switch ($this->type)
		{
			case "ftp":
			{
				$ftp = new Ftp\Driver\Ftp($this->options);
				\TeraBlaze\Registry::set(get_config('app_id').'ftp_'.$ftp_conf, $ftp);
				return $ftp;
				break;
			}
			default:
			{
				throw new Exception\Argument("Invalid ftp type or ftp configuration not properly set in APPLICATION_DIR/configuration/ftp.php");
				break;
			}
		}
	}
}