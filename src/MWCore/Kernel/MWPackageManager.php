<?php

namespace MWCore\Kernel;

class MWPackageManager
{

	protected $packages;
	
	protected $router;
	
	protected $firewall;
	
	public function __construct(&$router, &$firewall)
	{	
		
		$this -> packages = array();
		
		$this -> router = $router;
		
		$this -> firewall = $firewall;
		
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
		
 		$this -> router -> setRoutes($package -> getRoutes());
		$this -> firewall -> setRules($package -> getRules());
		
		foreach($package -> getConstants() as $key => $value)
		{
		
			define($key, $value);
			
		}
		
		$this -> packages[$packageName] = $package;
		
	}
	
}