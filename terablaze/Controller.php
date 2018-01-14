<?php
/**
 * Created by TeraBoxX.
 * User: tommy
 * Date: 2/4/2017
 * Time: 4:09 PM
 */

namespace TeraBlaze;

use TeraBlaze\Base as Base;
use TeraBlaze\View as View;
use TeraBlaze\Registry as Registry;
use TeraBlaze\Libraries\Template as Template;
use TeraBlaze\Controller\Exception as Exception;

/**
 * Class Controller
 * @package TeraBlaze
 */
class Controller extends Base
{
	/**
	* @read
	*/
	protected $_name;

	/**
	 * @readwrite
	 */
	protected $_parameters;

	/**
	 * @return string
	 */
    protected function getName()
    {
        if (empty($this->_name))
        {
            $this->_name = get_class($this);
        }
        return $this->_name;
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
	 * Controller constructor.
	 * @param array $options
	 */
	public function __construct($options = array())
	{
		Events::fire("terablaze.controller.construct.before", array($this->name));

		parent::__construct($options);

		Events::fire("terablaze.controller.construct.after", array($this->name));
	}

	/**
	 * Controller destructor
	 */
	public function __destruct()
	{
		Events::fire("terablaze.controller.destruct.before", array($this->name));

		//$this->render();

		Events::fire("terablaze.controller.destruct.after", array($this->name));
	}


	/**
	 * serves as the default index method
	 * in case it is not defined in inheriting controllers
	 */
	public function index()
	{

	}
}