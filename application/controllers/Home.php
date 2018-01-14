<?php

/**
 * Created by TeraBoxX.
 * User: tommy
 * Date: 5/28/2017
 * Time: 1:39 AM
 */

class Home extends \TeraBlaze\Controller
{
	public function index()
	{

		$data['page_title'] = 'Welcome to '.get_config('app_name');

		$this->load_view('welcome', $data);
	}

}
