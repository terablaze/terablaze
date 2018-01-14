<?php
/**
 * Created by TeraBoxX.
 * User: tommy
 * Date: 2/25/2017
 * Time: 8:16 PM
 */

namespace TeraBlaze\Libraries;

use TeraBlaze\Base as Base;

class Data_validator
{
	public function custom($match, $input_string)
	{
		return (bool) preg_match('/^'.$match.'$/i', $input_string);
	}

	public function required($input_string)
	{
		return !empty($input_string);
	}

	public function alpha($input_string)
	{
		return ctype_alpha($input_string);
	}

	public function alpha_numeric($input_string)
	{
		return ctype_alnum((string) $input_string);
	}

	public function alnum_spaces($input_string)
	{
		return (bool) preg_match('/^[a-zA-Z0-9 ]+$/i', $input_string);
	}
	
	public function alnum_dash($input_string)
	{
		return (bool) preg_match('/^[a-zA-Z0-9_-]+$/i', $input_string);
	}

	public function string($input_string){
		return (bool) preg_match('/^[a-zA-Z0-9_- ]+$/i', $input_string);
	}
	
	public function integer($input_string)
	{
		return (bool) preg_match('/^[\-+]?[0-9]+$/', $input_string);
	}

	public function decimal($input_string)
	{
		return (bool) preg_match('/^[\-+]?[0-9]+\.[0-9]+$/', $input_string);
	}

	public function min_length($input_string, $value)
	{
		if ( ! is_numeric($value))
		{
			return FALSE;
		}

		return ($value <= mb_strlen($input_string));
	}


	public function max_length($input_string, $value)
	{
		if ( ! is_numeric($value))
		{
			return FALSE;
		}

		return ($value >= mb_strlen($input_string));
	}

	public function exact_length($input_string, $value)
	{
		if ( ! is_numeric($value))
		{
			return FALSE;
		}

		return (mb_strlen($input_string) === (int) $value);
	}

	public function valid_url($input_string)
	{
		if(filter_var('http://'.$input_string, FILTER_VALIDATE_URL))
		{
			return TRUE;
		}

		return FALSE;
	}

	public function email($email = "")
	{
		if(filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			return TRUE;
		}

		return FALSE;
	}

	public function ip($ip)
	{
		if(filter_var($ip, FILTER_VALIDATE_IP))
		{
			return TRUE;
		}

		return FALSE;
	}

}