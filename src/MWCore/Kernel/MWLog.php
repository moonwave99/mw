<?php

namespace MWCore\Kernel;

use MWCore\Interfaces\MWSingleton;
use MWCore\Kernel\MWDBManager;

class MWLog implements MWSingleton
{
	
	private static $instance = null;
	
	protected $list;
	
	protected $startTime;
	
	public static function getInstance()
	{

		if(self::$instance == null)
		{   
			$c = __CLASS__;			
			self::$instance = new $c;
		}

		return self::$instance;
		
	}	
	
	private function __construct()
	{
		$this -> list = array();
	}
	
	public function setStartTime($startTime){ $this -> startTime = $startTime; }
	public function getExectime(){ return microtime(true) - $this -> startTime; }	
	public function getQueryNumber(){ return MWDBManager::getInstance() -> getQueryNumber(); }
	public function getList(){ return $this -> list; }
	
	public function add($object)
	{
		
		$backtrace = debug_backtrace();
		
		$this -> list[] = array(
			'datetime'	=> Date("r"),
			'class'		=> $backtrace[1]['class'],			
			'function'	=> $backtrace[1]['function'],
			'line'		=> $backtrace[1]['line'],
			'file'		=> $backtrace[1]['file'],
			'object'	=> $object,			
		);
		
	}
	
	public function flush(){
		
		echo '<pre>';
		
		foreach($this -> list as $l){
			
			echo '[' . $l['datetime'] . '] ';
			echo '[' . $l['class'] . '] ';
			echo '[' . $l['function'] . '] ';
			echo '[' . $l['line'] . ']  <br/><br/>';
			print_r($l['object']);
			
		}
		
		echo '</pre>';
		
	}
	
}