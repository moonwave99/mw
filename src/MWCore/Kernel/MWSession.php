<?php

namespace MWCore\Kernel;

use MWCore\Interfaces\MWSingleton;

class MWSession implements MWSingleton
{

	private static $instance = null;

	protected $name;
	
	protected $values;
	
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
	
		$this -> values = array();

	}
	
	public function setName($name){ $this -> name = $name; }
	public function getName(){ return $this -> name; }
	
	public function get($key)
	{
		
		return isset( $_SESSION[$this -> name][$key] ) ? $_SESSION[$this -> name][$key] : NULL;
		
	}
	
	public function set($key, $value)
	{

		$_SESSION[$this -> name][$key] = $value;
		$this -> values[$key] = $value;
		
	}
	
	public function remove($key)
	{
		
		$_SESSION[$this -> name][$key] = NULL;
		$this -> values[$key] = NULL;
		
	}
	
	public function start()
	{
		
		session_start($this -> name);

		$this -> values = $_SESSION[$this -> name];

		if( $this -> get('csrfToken') == NULL ){

			$this -> set('csrfToken', sha1(microtime()) );

		}
		
	}
	
	public function destroy()
	{
		
		$this -> values = array();
		$_SESSION = array();
	    session_destroy();
		
	}
	
}