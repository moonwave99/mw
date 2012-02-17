<?php

namespace MWCore\Kernel;

use MWCore\Interfaces\MWSingleton;
use MWCore\Kernel\MWSingleRoute;

class MWPackageManager implements MWSingleton
{

	private static $instance = null;

	protected $packages;
	
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
		
		$this -> packages = array();
		
	}
	
	public function getPackage($packageName)
	{
		
		if($this -> packages[$packageName] === NULL) throw new \MWCore\Exception\MWPackageLoadException($packageName);
		
		return $this -> packages[$packageName];
		
	}
	
	public function registerPackage($packageName)
	{
		
		$path = SRC_PATH.$packageName;
		
		if(!file_exists($path."/Resources/loader.php")) throw new \MWCore\Exception\MWPackageLoadException($packageName);
		
		include($path."/Resources/loader.php");
		
		MWRouter::getInstance() -> setRoutes($package -> getRoutes());
		MWFirewall::getInstance() -> setRules($package -> getRules());
		
		foreach($package -> getConstants() as $key => $value)
		{
		
			define($key, $value);
			
		}
		
		$this -> packages[$packageName] = $package;
		
	}
	
}