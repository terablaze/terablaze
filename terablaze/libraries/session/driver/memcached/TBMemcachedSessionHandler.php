<?php

/**
 * Created by TeraBoxX.
 * User: tommy
 * Date: 9/1/18
 * Time: 9:54 PM
 */
namespace TeraBlaze\Libraries\Session\Driver\Memcached;

class TBMemcachedSessionHandler extends \SessionHandler {
	public function read($id)
	{
		$data = parent::read($id);
		if(empty($data)) {
			return '';
		} else {
			return $data;
		}
	}
}