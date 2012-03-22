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

/**
*	MWPackageManager Class - handles Packages registration and so.
*/
class MWPackageManager
{

	/**
	*	@access protected
	*	@var array
	*/
	protected $packages;
	
	/**
	*	@access protected
	*	@var MWRouter
	*/	
	protected $router;
	
	/**
	*	@access protected
	*	@var MWFirewall
	*/	
	protected $firewall;
	
	/**
	*	Default constructor.
	*	@access public
	*	@param MWRouter $router MWRouter instance injected
	*	@param MWFirewall $firewall MWFirewall instance injected
	*/	
	public function __construct(&$router, &$firewall)
	{	
		
		$this -> packages = array();
		
		$this -> router = $router;
		
		$this -> firewall = $firewall;
		
	}
	
	/**
	*	Package getter
	*	@access public
	*	@param string $packageName The package being looked for
	*	@return MWPackage
	*/	
	public function getPackage($packageName)
	{
		
		if($this -> packages[$packageName] === NULL) throw new \MWCore\Exception\MWPackageLoadException($packageName);
		
		return $this -> packages[$packageName];
		
	}
	
	/**
	*	Registers package by executing corresponding loader script
	*	@access public
	*	@param string $packageName The package being looked for
	*/	
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