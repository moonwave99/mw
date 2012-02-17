<?php

namespace MWCore\Kernel;

use MWCore\Interfaces\MWSingleton;
use MWCore\Kernel\MWSingleRoute;

class MWPackageManager implements MWSingleton
{

	private static $instance = null;

	protected $packets;
	
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
		
		$this -> packets = array();
		
	}
	
	public function registerPacket($packetName)
	{
		
		$path = SRC_PATH.$packetName;
		
		if(!file_exists($path."/Resources/loader.php")) throw new \MWCore\Exception\MWPackageLoadException($packetName);
		
		include($path."/Resources/loader.php");
		
		MWRouter::getInstance() -> setRoutes($routes);
		MWFirewall::getInstance() -> setRules($rules);
		
		foreach($constants as $key => $value)
		{
		
			define($key, $value);
			
		}
		
		$this -> packets[$signature] = $packetName;
		
	}
	
}