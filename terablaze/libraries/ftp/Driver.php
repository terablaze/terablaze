<?php
/**
 * Created by TeraBoxX.
 * User: tommy
 * Date: 2/4/2017
 * Time: 9:19 AM
 */

namespace TeraBlaze\Libraries\Ftp;

use TeraBlaze\Base as Base;
use TeraBlaze\Libraries\Ftp\Exception as Exception;

class Driver extends Base
{
	/**
	 * FTP Server type
	 *
	 * @var	string
	 *
	 * @readwrite
	 */
	protected $_type;

	/**
	 * FTP Server host
	 *
	 * @var	string
	 *
	 * @readwrite
	 */
	protected $_host;

	/**
	 * FTP Username
	 *
	 * @var	string
	 *
	 * @readwrite
	 */
	protected $_username;

	/**
	 * FTP Password
	 *
	 * @var	string
	 *
	 * @readwrite
	 */
	protected $_password;

	/**
	 * FTP Server base directory
	 *
	 * @var	string
	 *
	 * @readwrite
	 */
	protected $_basedir = "./";

	/**
	 * FTP Server current directory
	 *
	 * @var	string
	 *
	 * @readwrite
	 */
	protected $_currdir = "./";

	/**
	 * FTP Server port
	 *
	 * @var	int
	 *
	 * @readwrite
	 */
	protected $_port = "21";

	/**
	 * Passive mode flag
	 *
	 * @var	bool
	 *
	 * @readwrite
	 */
	protected $_passive = TRUE;

	// --------------------------------------------------------------------

	/**
	 * Connection ID
	 *
	 * @var	resource
	 *
	 * @protected
	 */
	protected $conn_id;

	// --------------------------------------------------------------------

	protected function _is_conn() {

	}


	/**
	 * Check if directory exists
	 *
	 * @param	string	$path
	 * @return	bool
	 */
	public function is_dir($path)
	{
		if ( ! $this->_is_conn())
		{
			return FALSE;
		}

		$result = @ftp_chdir($this->conn_id, $path);

		if ($result === FALSE)
		{
			return FALSE;
		}

		return TRUE;
	}

	// --------------------------------------------------------------------

	/**
	 * Change directory
	 *
	 * @param	string	$path
	 * @return	bool
	 */
	public function chdir($path)
	{
		if ( ! $this->_is_conn())
		{
			return FALSE;
		}

		$result = @ftp_chdir($this->conn_id, $path);

		if ($result === FALSE)
		{
			return FALSE;
		}

		$this->_currdir = $path;
		return TRUE;
	}

	// --------------------------------------------------------------------

	/**
	 * Create a directory
	 *
	 * @param	string	$path
	 * @param	int	$permissions
	 * @return	bool
	 */
	public function mkdir($path, $permissions = NULL)
	{
		$currdir = $this->_currdir;

		if ($path === '' OR ! $this->_is_conn())
		{
			return FALSE;
		}

		$path = rtrim($path, '/');
		$parts = explode('/', $path);

		foreach($parts as $part){
			if(!$this->chdir($part)){
				@ftp_mkdir($this->conn_id, $part);
				@ftp_chdir($this->conn_id, $part);

				// Set file permissions if needed
				if ($permissions !== NULL)
				{
					$this->chmod($part, (int) $permissions);
				}
			}
		}

		$this->chdir($currdir);
		if($this->chdir($path)){
			$this->_currdir = $path;
			return TRUE;
		} else {
			return FALSE;
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Upload a file to the server
	 *
	 * @param	string	$locpath
	 * @param	string	$rempath
	 * @param	string	$mode
	 * @param	int	$permissions
	 * @return	bool
	 */
	public function upload($locpath, $rempath, $mode = 'auto', $permissions = NULL)
	{
		if ( ! $this->_is_conn())
		{
			return FALSE;
		}

		if ( ! file_exists($locpath))
		{
			throw new Exception\Argument("Source file not found");
		}

		// Set the mode if not specified
		if ($mode === 'auto')
		{
			// Get the file extension so we can set the upload type
			$ext = $this->_getext($locpath);
			$mode = $this->_settype($ext);
		}

		$mode = ($mode === 'ascii') ? FTP_ASCII : FTP_BINARY;

		$result = @ftp_put($this->conn_id, $rempath, $locpath, $mode);

		if ($result === FALSE)
		{
			throw new Exception\Service("Unable to upload file");
		}

		// Set file permissions if needed
		if ($permissions !== NULL)
		{
			$this->chmod($rempath, (int) $permissions);
		}

		return TRUE;
	}

	// --------------------------------------------------------------------

	/**
	 * Download a file from a remote server to the local server
	 *
	 * @param	string	$rempath
	 * @param	string	$locpath
	 * @param	string	$mode
	 * @return	bool
	 */
	public function download($rempath, $locpath, $mode = 'auto')
	{
		if ( ! $this->_is_conn())
		{
			return FALSE;
		}

		// Set the mode if not specified
		if ($mode === 'auto')
		{
			// Get the file extension so we can set the upload type
			$ext = $this->_getext($rempath);
			$mode = $this->_settype($ext);
		}

		$mode = ($mode === 'ascii') ? FTP_ASCII : FTP_BINARY;

		$result = @ftp_get($this->conn_id, $locpath, $rempath, $mode);

		if ($result === FALSE)
		{
			throw new Exception\Service("Unable to download file");
		}

		return TRUE;
	}

	// --------------------------------------------------------------------

	/**
	 * Rename (or move) a file
	 *
	 * @param	string	$old_file
	 * @param	string	$new_file
	 * @param	bool	$move
	 * @return	bool
	 */
	public function rename($old_file, $new_file, $move = FALSE)
	{
		if ( ! $this->_is_conn())
		{
			return FALSE;
		}

		$result = @ftp_rename($this->conn_id, $old_file, $new_file);

		if ($result === FALSE)
		{
			throw new Exception\Service("Unable to ".(($move === FALSE) ? 'rename' : 'move')." file");
		}

		return TRUE;
	}

	// --------------------------------------------------------------------

	/**
	 * Move a file
	 *
	 * @param	string	$old_file
	 * @param	string	$new_file
	 * @return	bool
	 */
	public function move($old_file, $new_file)
	{
		return $this->rename($old_file, $new_file, TRUE);
	}

	// --------------------------------------------------------------------

	/**
	 * Rename (or move) a file
	 *
	 * @param	string	$filepath
	 * @return	bool
	 */
	public function delete_file($filepath)
	{
		if ( ! $this->_is_conn())
		{
			return FALSE;
		}

		$result = @ftp_delete($this->conn_id, $filepath);

		if ($result === FALSE)
		{
			throw new Exception\Service("Unable to delete file");
		}

		return TRUE;
	}

	// --------------------------------------------------------------------

	/**
	 * Delete a folder and recursively delete everything (including sub-folders)
	 * contained within it.
	 *
	 * @param	string	$filepath
	 * @return	bool
	 */
	public function delete_dir($filepath)
	{
		if ( ! $this->_is_conn())
		{
			return FALSE;
		}

		// Add a trailing slash to the file path if needed
		$filepath = preg_replace('/(.+?)\/*$/', '\\1/', $filepath);

		$list = $this->list_files($filepath);
		if ( ! empty($list))
		{
			for ($i = 0, $c = count($list); $i < $c; $i++)
			{
				// If we can't delete the item it's probaly a directory,
				// so we'll recursively call delete_dir()
				if ( ! preg_match('#/\.\.?$#', $list[$i]) && ! @ftp_delete($this->conn_id, $list[$i]))
				{
					$this->delete_dir($filepath.$list[$i]);
				}
			}
		}

		if (@ftp_rmdir($this->conn_id, $filepath) === FALSE)
		{
			throw new Exception\Service("Unable to download directory");
		}

		return TRUE;
	}

	// --------------------------------------------------------------------

	/**
	 * Set file permissions
	 *
	 * @param	string	$path	File path
	 * @param	int	$perm	Permissions
	 * @return	bool
	 */
	public function chmod($path, $perm)
	{
		if ( ! $this->_is_conn())
		{
			return FALSE;
		}

		if (@ftp_chmod($this->conn_id, $perm, $path) === FALSE)
		{
			throw new Exception\Service("Unable to set permissions");
		}

		return TRUE;
	}

	// --------------------------------------------------------------------

	/**
	 * FTP List files in the specified directory
	 *
	 * @param	string	$path
	 * @return	array|bool
	 */
	public function list_files($path = '.')
	{
		return $this->_is_conn()
			? ftp_nlist($this->conn_id, $path)
			: FALSE;
	}

	// ------------------------------------------------------------------------

	/**
	 * Read a directory and recreate it remotely
	 *
	 * This function recursively reads a folder and everything it contains
	 * (including sub-folders) and creates a mirror via FTP based on it.
	 * Whatever the directory structure of the original file path will be
	 * recreated on the server.
	 *
	 * @param	string	$locpath	Path to source with trailing slash
	 * @param	string	$rempath	Path to destination - include the base folder with trailing slash
	 * @return	bool
	 */
	public function mirror($locpath, $rempath)
	{
		if ( ! $this->_is_conn())
		{
			return FALSE;
		}

		// Open the local file path
		if ($fp = @opendir($locpath))
		{
			// Attempt to open the remote file path and try to create it, if it doesn't exist
			if ( ! $this->chdir($rempath) && ( ! $this->mkdir($rempath) OR ! $this->chdir($rempath)))
			{
				return FALSE;
			}

			// Recursively read the local directory
			while (FALSE !== ($file = readdir($fp)))
			{
				if (is_dir($locpath.$file) && $file[0] !== '.')
				{
					$this->mirror($locpath.$file.'/', $rempath.$file.'/');
				}
				elseif ($file[0] !== '.')
				{
					// Get the file extension so we can se the upload type
					$ext = $this->_getext($file);
					$mode = $this->_settype($ext);

					$this->upload($locpath.$file, $rempath.$file, $mode);
				}
			}

			return TRUE;
		}

		return FALSE;
	}

	// --------------------------------------------------------------------

	/**
	 * Extract the file extension
	 *
	 * @param	string	$filename
	 * @return	string
	 */
	protected function _getext($filename)
	{
		return (($dot = strrpos($filename, '.')) === FALSE)
			? 'txt'
			: substr($filename, $dot + 1);
	}

	// --------------------------------------------------------------------

	/**
	 * Set the upload type
	 *
	 * @param	string	$ext	Filename extension
	 * @return	string
	 */
	protected function _settype($ext)
	{
		return in_array($ext, array('txt', 'text', 'php', 'phps', 'php4', 'js', 'css', 'htm', 'html', 'phtml', 'shtml', 'log', 'xml'), TRUE)
			? 'ascii'
			: 'binary';
	}

	// --------------------------------------------------------------------

	protected function _getExceptionForImplementation($method)
	{
		return new Exception\Implementation("{$method} method not implemented");
	}
}