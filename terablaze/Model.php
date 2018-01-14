<?php
/**
 * Created by TeraBoxX.
 * User: tommy
 * Date: 2/18/2017
 * Time: 8:48 PM
 */

namespace TeraBlaze;


use TeraBlaze\Base as Base;
use TeraBlaze\View as View;
use TeraBlaze\Registry as Registry;
use TeraBlaze\Libraries\Template as Template;
use TeraBlaze\Model\Exception as Exception;

/**
 * Class Model
 * @package TeraBlaze
 */
class Model extends Base
{
	/**
	 * @read
	 */
	protected $name;

	/**
	 *
	 */
	protected function setName()
	{
		$this->name = get_class($this);
	}

	/**
	 * @param $method
	 * @return Exception\Implementation
	 */
	protected function _getExceptionForImplementation($method)
	{
		return new Exception\Implementation("{$method} method not implemented");
	}

	/**
	 * @return Exception\Argument
	 */
	protected function _getExceptionForArgument()
	{
		return new Exception\Argument("Invalid argument");
	}

	/**
	 * Model constructor.
	 * @param array $options
	 */
	public function __construct($options = array())
	{
		parent::__construct($options);

		$this->setName();

		Events::fire("terablaze.model.construct.before", array($this->name));

		Events::fire("terablaze.model.construct.after", array($this->name));
	}

	/**
	 * Model destructor.
	 */
	public function __destruct()
	{
		Events::fire("terablaze.model.destruct.before", array($this->name));

		Events::fire("terablaze.model.destruct.after", array($this->name));
	}

}