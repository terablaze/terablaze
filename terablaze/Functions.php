<?php
/**
 * Created by TeraBoxX.
 * User: tommy
 * Date: 3/20/2017
 * Time: 11:22 AM
 */


function redirect($url = ""){
	header('Location: '.$url);
	exit();
}

function base_url($uri = '', $protocol = '', $port = '') {
	if(!empty(get_config('base_url'))){
		$link = get_config('base_url').get_config('virtual_location').$uri;
	} else {
		if(empty($protocol)){
			$protocol = get_config('default_protocol');
		}
		if(empty($port)) {
			$port = ($_SERVER['SERVER_PORT'] == 80) ? '' : ':' . $_SERVER['SERVER_PORT'];
		} else {
			$port = ':'.$port;
		}
		$link = $protocol.'://'.$_SERVER['SERVER_NAME'].$port.get_config('virtual_location').$uri;
	}
	// Escape html
	return htmlspecialchars($link, ENT_QUOTES);
}
function site_url($uri = '', $protocol = '', $port = '') {
	$post_server = '';
	if(empty(get_config('index_script'))){
		$post_server = $uri;
	} else {
		$post_server = get_config('index_script') . '/' . $uri;
	}
	if(!empty(get_config('base_url'))){
		$link = get_config('base_url').get_config('virtual_location').$post_server;
	} else {
		if(empty($protocol)){
			$protocol = get_config('default_protocol');
		}
		if(empty($port)) {
			$port = ($_SERVER['SERVER_PORT'] == 80) ? '' : ':' . $_SERVER['SERVER_PORT'];
		} else {
			$port = ':'.$port;
		}
		$link = $protocol.'://'.$_SERVER['SERVER_NAME'].$port.get_config('virtual_location').$post_server;
	}
	// Escape html
	return htmlspecialchars($link, ENT_QUOTES);
}

function make_dir($dir, $recursive = TRUE) {
	return mkdir($dir, 0777, $recursive);
}

function time_elapsed_string($datetime, $full = false) {
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
	return $string ? implode(', ', $string) . ' '.__('ago') : __('just_now');
}

function slug($text){

	// replace non letter or digits by -
	$text = preg_replace('~[^\\pL\d]+~u', '-', $text);

	// trim
	$text = trim($text, '-');

	// transliterate
	$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

	// lowercase
	$text = strtolower($text);

	// remove unwanted characters
	$text = preg_replace('~[^-\w]+~', '', $text);

	if (empty($text))
	{
		return 'n-a';
	}

	return $text;
}


function get_config($key){
	global $config;
	return $config->$key;
}

include_once APPLICATION_DIR.'configuration/functions.php';
