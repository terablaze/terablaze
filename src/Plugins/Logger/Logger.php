<?php

namespace Plugins\Logger;

use Exception;

/**
 * Class Logger
 */
class Logger
{
	protected $_file;
	protected $_entries;
	protected $_start;
	protected $_end;
	
	protected function _sum($values)
	{
		$count = 0;
		
		foreach ($values as $value) {
			$count += $value;
		}
		
		return $count;
	}
	
	protected function _average($values)
	{
		return $this->_sum($values) / sizeof($values);
	}
	
	/**
	 * Logger constructor.
	 * @param $options
	 * @throws Exception
	 */
	public function __construct($options)
	{
		if (!isset($options["file"])) {
			throw new Exception("Log file invalid.");
		}
		
		$this->_file = $options["file"];
		$this->_entries = array();
		$this->_start = microtime(true);
	}
	
	/**
	 * @param $message
	 */
	public function log($message)
	{
		$this->_entries[] = array(
			"message" => "[" . date("r") . "]" . $message,
			"time" => microtime(true)
		);
	}
	
	/**
	 *
	 */
	public function __destruct()
	{
		$messages = "";
		$last = $this->_start;
		$times = array();
		
		foreach ($this->_entries as $entry) {
			$time = $entry["time"] - $last;
			$times[] = $time;
			$messages .= $entry["message"] . "\t\t\t Used " . ($time) . " seconds" . "\n";
			$last = $entry["time"];
		}
		
		$messages .= "Average: " . $this->_average($times);
		$messages .= ", Longest: " . max($times);
		$messages .= ", Shortest: " . min($times);
		$messages .= ", Total: " . round((microtime(true) - $this->_start), 5) . ' seconds';
		$messages .= "\n\n";
		
		file_put_contents($this->_file, $messages, FILE_APPEND);
	}
}
