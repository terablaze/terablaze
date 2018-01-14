<?php


namespace TeraBlaze\Libraries\Session\Driver;

use TeraBlaze\Libraries\Session as Session;

class File extends Session\Driver
{
	/**
	 * @readwrite
	 */
	protected $_prefix;

	/**
	 * @readwrite
	 */
	protected $_directory;

	private function _get($key, $default = NULL)
	{
		$file = $this->directory . $key;

		if (is_file($file)) {
			$handle = fopen($file, 'r');

			flock($handle, LOCK_SH);

			$data = fread($handle, filesize($file));

			flock($handle, LOCK_UN);

			fclose($handle);

			return $data;
		}

		return $default;
	}

	private function _set($key, $value = NULL)
	{
		$file = $this->directory . $key;

		$handle = fopen($file, 'w');

		flock($handle, LOCK_EX);

		fwrite($handle, $value);

		fflush($handle);

		flock($handle, LOCK_UN);

		fclose($handle);

		return $this;
	}

	private function _erase($key)
	{
		$file = $this->directory .$key;

		if (is_file($file)) {
			unlink($file);
		}
		return $this;
	}

	public function get($key, $default = NULL){
		return $this->_get('file_sess_' .$this->_prefix. $key, $default);
	}

	public function set($key, $value = NULL)
	{
		if(is_array($key)){
			foreach ($key as $new_key => $new_value){
				$this->_set('file_sess_' .$this->_prefix. $new_key, $new_value);
			}
		}else {
			$this->_set('file_sess_' .$this->_prefix. $key, $value);
		}

		return $this;
	}


	public function get_flash($key, $default = NULL)
	{
		$data = $this->_get('file_sess_TB_flash_' .$this->_prefix. $key, $default);
		$this->_erase('file_sess_TB_flash_' .$this->_prefix.$key);
		return $data;
	}

	public function set_flash($key, $value = NULL)
	{
		if(is_array($key)){
			foreach ($key as $new_key => $new_value){
				$this->_set('file_sess_TB_flash_' .$this->_prefix. $new_key, $new_value);
			}
		}else {
			$this->_set('file_sess_TB_flash_' .$this->_prefix. $key, $value);
		}

		return $this;
	}

	public function erase($key)
	{
		if(is_array($key)){
			foreach ($key as $each_key){
				$this->_erase('file_sess_' .$this->_prefix. $each_key);
			}
		}else {
			$this->_erase('file_sess_' .$this->_prefix. $key);
		}

		return $this;
	}

}