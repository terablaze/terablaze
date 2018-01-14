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
use TeraBlaze\Libraries\Database\Exception as Exception;


class Database extends Base
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

	public function initialize($db_conf = "default")
	{
		if(\TeraBlaze\Registry::get(get_config('app_id').'db_'.$db_conf, FALSE)){
			return \TeraBlaze\Registry::get(get_config('app_id').'db_'.$db_conf, FALSE);
		}
		Events::fire("terablaze.libraries.database.initialize.before", array($this->type, $this->options));
		$type = $this->getType();
		if (!$this->type)
		{
			$configuration = Registry::get("configuration");

			if ($configuration)
			{
				$configuration = $configuration->initialize();
				$parsed = $configuration->parse("configuration/database");

				if (!empty($parsed->database->{$db_conf}) && !empty($parsed->database->{$db_conf}->type))
				{
					$this->type = $parsed->database->{$db_conf}->type;
					//unset($parsed->database->{$db_conf}->type);
					$this->options = (array) $parsed->database->{$db_conf};
				}
			}
		}

		if (!$this->type)
		{
			throw new Exception\Argument("Invalid type");
		}

		Events::fire("terablaze.libraries.database.initialize.after", array($this->type, $this->options));

		switch ($this->type)
		{
			case "mysql":
			case "mysqli":
			{
				$db = new Database\Connector\Mysql($this->options);
				\TeraBlaze\Registry::set(get_config('app_id').'db_'.$db_conf, $db);
				return $db;
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