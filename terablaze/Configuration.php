<?php
/**
 * Created by TeraBoxX.
 * User: tommy
 * Date: 1/30/2017
 * Time: 2:34 PM
 */

namespace TeraBlaze;

use TeraBlaze\Base as Base;
use TeraBlaze\Configuration\Exception as Exception;

/**
 * Class Configuration
 * @package TeraBlaze
 *
 * loads the configuration to be used by the entire application
 */
class Configuration extends Base
{
	/**
	 * @readwrite
	 */
	protected $_type;

	/**
	 * @readwrite
	 */
	protected $_options;

	/**
	 * @param $method
	 * @return Exception\Implementation
	 */
	protected function _getExceptionForImplementation($method)
	{
		return new Exception\Implementation("{$method} method not implemented");
	}

	/**
	 * @return Configuration\Driver\Ini|Configuration\Driver\PHPArray
	 * @throws Exception\Argument
	 */
	public function initialize()
	{
		Events::fire("terablaze.configuration.initialize.before", array($this->type, $this->options));
           
		if (!$this->type)
		{
			throw new Exception\Argument("Configuration type not supplied");
		}
            
		Events::fire("terablaze.configuration.initialize.after", array($this->type, $this->options));
		
		switch ($this->type)
		{
			case "ini":
			{
				return new Configuration\Driver\Ini($this->options);
				break;
			}
			case "PhpArray":
			case "PHPArray":
			case "php_array":
			case "phparray":
			{
				return new Configuration\Driver\PHPArray($this->options);
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