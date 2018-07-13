<?php
/**
 * Created by TeraBoxX.
 * User: tommy
 * Date: 2/4/2017
 * Time: 9:18 AM
 */

namespace TeraBlaze\Libraries\Ftp\Driver;

use TeraBlaze\Libraries\Ftp as Ftp_driver;
use TeraBlaze\Libraries\Ftp\Exception as Exception;

class Ftp extends Ftp_driver\Driver
{
	/**
	 * FTP Connect
	 *
	 * @return	bool
	 */
	public function connect()
	{

		$this->conn_id = @ftp_connect($this->_host, $this->_port);

		if(!$this->_is_conn()){
			throw new Exception\Service("Unable to connect to FTP server");
		}

		if ( ! $this->_login())
		{
			throw new Exception\Service("Unable to login into FTP server");
		}

		// Set passive mode if needed
		if ($this->_passive === TRUE)
		{
			@ftp_pasv($this->conn_id, TRUE);
		}

		$this->chdir($this->_basedir);

		$this->_currdir = $this->_basedir;

		return TRUE;
	}

	// --------------------------------------------------------------------

	/**
	 * FTP Login
	 *
	 * @return	bool
	 */
	protected function _login()
	{
		return @ftp_login($this->conn_id, $this->_username, $this->_password);
	}

	// --------------------------------------------------------------------

	/**
	 * Validates the connection ID
	 *
	 * @return	bool
	 */
	protected function _is_conn()
	{
		return is_resource($this->conn_id);
	}

	// --------------------------------------------------------------------

	// --------------------------------------------------------------------

	/**
	 * Close the connection
	 *
	 * @return	bool
	 */
	public function close()
	{
		return $this->_is_conn()
			? @ftp_close($this->conn_id)
			: FALSE;
	}

	// --------------------------------------------------------------------

	// --------------------------------------------------------------------

	/**
	 * Close the connection
	 *
	 * @return	bool
	 */
	public function disconnect()
	{
		return $this->close();
	}

	// --------------------------------------------------------------------
}