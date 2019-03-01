<?php
/**
 * Created by TeraBoxX.
 * User: tommy
 * Date: 3/20/2017
 * Time: 11:22 AM
 */


function redirect($url = ""){
	header('Location: ' . $url);
	exit();
}

function base_url($uri = '', $protocol = '', $port = ''){
	if (!empty(get_config('base_url'))) {
		$link = get_config('base_url') . get_config('virtual_location') . $uri;
	} else {
		if (empty($protocol)) {
			$protocol = get_config('default_protocol');
		}
		if (empty($port)) {
			$port = ($_SERVER['SERVER_PORT'] == 80) ? '' : ':' . $_SERVER['SERVER_PORT'];
		} else {
			$port = ':' . $port;
		}
		$link = $protocol . '://' . $_SERVER['SERVER_NAME'] . $port . get_config('virtual_location') . $uri;
	}
	// Escape html
	return htmlspecialchars($link, ENT_QUOTES);
}

function site_url($uri = '', $protocol = '', $port = ''){
	$post_server = '';
	if (empty(get_config('index_script'))) {
		$post_server = $uri;
	} else {
		$post_server = get_config('index_script') . '/' . $uri;
	}
	if (!empty(get_config('base_url'))) {
		$link = get_config('base_url') . get_config('virtual_location') . $post_server;
	} else {
		if (empty($protocol)) {
			$protocol = get_config('default_protocol');
		}
		if (empty($port)) {
			$port = ($_SERVER['SERVER_PORT'] == 80) ? '' : ':' . $_SERVER['SERVER_PORT'];
		} else {
			$port = ':' . $port;
		}
		$link = $protocol . '://' . $_SERVER['SERVER_NAME'] . $port . get_config('virtual_location') . $post_server;
	}
	// Escape html
	return htmlspecialchars($link, ENT_QUOTES);
}

function make_dir($dir, $recursive = TRUE){
	if (!is_dir($dir)) {
		return mkdir($dir, 0777, $recursive);
	} else {
		return $dir;
	}
}

function time_elapsed_string($datetime, $full = false){
	$now = new \DateTime;
	$ago = new \DateTime($datetime);
	$diff = $now->diff($ago);
	
	$diff->w = floor($diff->d / 7);
	$diff->d -= $diff->w * 7;
	
	$string = array(
		'y' => __('year'),
		'm' => __('month'),
		'w' => __('week'),
		'd' => __('day'),
		'h' => __('hour'),
		'i' => __('minute'),
		's' => __('second'),
	);
	foreach ($string as $k => &$v) {
		if ($diff->$k) {
			$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
		} else {
			unset($string[$k]);
		}
	}
	
	if (!$full) $string = array_slice($string, 0, 1);
	return $string ? implode(', ', $string) . ' ' . __('ago') : __('just_now');
}

function slug($text){
	
	// replace non letter or digits by -
	$text = preg_replace('~[^\\pL\d]+~u', '-', $text);
	
	// trim
	$text = trim($text, '-');
	
	// transliterate
	if(function_exists('iconv')) {
		$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
	}
	
	// lowercase
	$text = strtolower($text);
	
	// remove unwanted characters
	$text = preg_replace('~[^-\w]+~', '', $text);
	
	if (empty($text)) {
		return 'n-a';
	}
	
	return $text;
}

function get_config($key){
	global $config;
	return $config->$key;
}

if (!function_exists('get_include_contents')) {
	function get_include_contents($filename, $vars)
	{
		if (is_file($filename)) {
			ob_start();
			@extract($vars);
			include $filename;
			return ob_get_clean();
		}
		return false;
	}
}

if(!function_exists('bytes_convert')) {
	/**
	 * @param $byte
	 * @param $unit
	 * @return float|int
	 */
	function bytes_convert($byte, $unit)
	{
		$kb = 1024;
		$mb = 1024 * 1024;
		$gb = 1024 * 1024 * 1024;
		$tb = 1024 * 1024 * 1024 * 1024;
		
		return ($byte / $$unit);
	}
}

if ( ! function_exists('is_https')){
	/**
	 * Is HTTPS?
	 *
	 * Determines if the application is accessed via an encrypted
	 * (HTTPS) connection.
	 *
	 * @return	bool
	 */
	function is_https(){
		if ( ! empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off')
		{
			return TRUE;
		}
		elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https')
		{
			return TRUE;
		}
		elseif ( ! empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off')
		{
			return TRUE;
		}
		
		return FALSE;
	}
}


if ( ! function_exists('log_error')){
	/**
	 * @param $message
	 * @param bool $tofile
	 */
	function log_error($message, $tofile = true){
		if(is_array($message) || is_object($message)){
			$message = json_encode($message);
		}
		
		if (ini_get('display_errors') == 1){
			echo '<pre style="color: #FF0000">';
			print_r($message);
			echo '</pre>';
		} else {
			echo ('<code style="color: #FF0000">An error occurred.</code>');
		}
		if($tofile) {
			if (!is_dir(APPLICATION_DIR . 'logs/errors')) {
				mkdir(APPLICATION_DIR . 'logs/errors/', 0777, true);
			}
			file_put_contents(APPLICATION_DIR . 'logs/errors/' . date("Y-F-d") . ".log", $message."\n\n", FILE_APPEND);
		}
	}
}

if(file_exists(APPLICATION_DIR.'configuration/functions.php')) {
	include_once APPLICATION_DIR . 'configuration/functions.php';
}
