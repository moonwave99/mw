<?php

/**
*	Part of MW - lightweight MVC framework.
*	@author Diego Caponera <diego.caponera@gmail.com>
*	@link https://github.com/moonwave99/mw
*	@copyright Copyright 2011-2012 Diego Caponera
*	@license http://www.opensource.org/licenses/mit-license.php MIT License
*	@package MWCore/Kernel
*/

namespace MWCore\Kernel;

use MWCore\Interfaces\MWSingleton;
use MWCore\Kernel\MWDBManager;

/**
*	MWLog Class - general purpose logger.
*/
class MWLog implements MWSingleton
{
	
	/**
	*	Singleton instance field
	*	@access private
	*	@var MWLog
	*/
	private static $instance = null;
	
	/**
	*	@access protected
	*	@var array
	*/
	protected $list;
	
	/**
	*	@access protected
	*	@var int
	*/
	protected $startTime;
	
	/**
	*	Returns unique instance of current class
	*	@access public
	*	@return MWLog
	*/
	public static function getInstance()
	{

		if(self::$instance == null)
		{   
			$c = __CLASS__;			
			self::$instance = new $c;
		}

		return self::$instance;
		
	}	
	
	/**
	*	Default constructor, private for design purposes
	*	@access private
	*/
	private function __construct()
	{
		
		$this -> list = array();
		
	}
	
	/**
	*	Start time setter
	*	@access public
	*	@param int $startTime App execution start timestamp in ms
	*/
	public function setStartTime($startTime){ $this -> startTime = $startTime; }
	
	/**
	*	Returns the difference between start time and current moment
	*	@access public
	*	@return int
	*/	
	public function getExectime(){ return microtime(true) - $this -> startTime; }	
	
	/**
	*	Return the number of executed query at the moment
	*	@access public
	*	@return int
	*/	
	public function getQueryNumber(){ return MWDBManager::getInstance() -> getQueryNumber(); }
	
	/**
	*	List getter
	*	@access public
	*	@return array
	*/	
	public function getList(){ return $this -> list; }
	
	/**
	*	Adds and object to current list
	*	@access public
	*	@param mixed $object The object being added
	*/	
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
	
	/**
	*	Flushes logged stuff to screen
	*	@access public
	*/	
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