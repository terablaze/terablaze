<?php
/**
 * Created by TeraBoxX.
 * User: tommy
 * Date: 2/4/2017
 * Time: 9:18 AM
 */

namespace TeraBlaze\Libraries\Cookie\Driver;

use TeraBlaze\Libraries\Cookie as Cookie;

class DefaultCookie extends Cookie\Driver
{
	/**
	 * @readwrite
	 */
	protected $_prefix;
	/**
	 * @readwrite
	 */
	protected $_expire;
	/**
	 * @readwrite
	 */
	protected $_path;
	/**
	 * @readwrite
	 */
	protected $_domain;
	/**
	 * @readwrite
	 */
	protected $_secure;
	/**
	 * @readwrite
	 */
	protected $_httponly;

	public function set($name, $value = '', $expire = '', $path = '', $domain = '', $secure = '', $httponly = ''){
		if($expire === ''){
			$expire = $this->expire;
		}
		if($path === ''){
			$path = $this->path;
		}
		if($domain === ''){
			$domain = $this->domain;
		}
		if($secure === ''){
			$secure = $this->secure;
		}
		if($httponly === ''){
			$httponly = $this->httponly;
		}
		setcookie($this->prefix.$name, $value, time()+$expire, $path, $domain, $secure, $httponly);

		return $this;
	}

	public function get($name, $default = NULL){
		if (isset($_COOKIE[$this->prefix.$name]))
		{
			return $_COOKIE[$this->prefix.$name];
		}
		return $default;
	}

	public function erase($name){
		setcookie($this->prefix.$name, NULL, time()-3600);
		return $this;
	}

}