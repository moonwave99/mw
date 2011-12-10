<?php

namespace MWCore\Component;

use MWCore\Interfaces\MWSingleton;

class MWRequest implements MWSingleton
{
	
	private static $instance = null;
	
	protected $values;
	
	protected $method;
	
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
		
		$this -> method = $_SERVER['REQUEST_METHOD'];
		
		$this -> cleanRequest();
		
	}
	
	public function __get($key)
	{
		
		return isset( $this -> values[$key] ) ? $this -> values[$key] : NULL;		
		
	}
	
	public function get($key)
	{
		
		return isset( $this -> values[$key] ) ? $this -> values[$key] : NULL;
		
	}
	
	public function toArray(){ return $this -> values; }
	
	public function getMethod(){ return $this -> method; }
	
	protected function cleanRequest()
	{		
		
		foreach(array($_GET, $_POST, $_COOKIE) as $req)
		{

			if (get_magic_quotes_gpc())
			{
			
			    $strip_slashes_deep = function ($value) use (&$strip_slashes_deep) {
			        return is_array($value) ? array_map($strip_slashes_deep, $value) : stripslashes($value);
			    };
			
			    $req = array_map($strip_slashes_deep, $req);
			
			}

			foreach($req as $key => $val)
			{

			    $entities = function ($value) use (&$entities) {
			        return is_array($value) ? array_map($entities, $value) : htmlentities($value, ENT_QUOTES, 'UTF-8');
			    };
			
				$this -> values[$key] = $entities($val);

			}
			
		}
		
		unset($_GET);
		unset($_POST);
		unset($_COOKIE);
				
	}

	
}