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
*	MWProvider Class - dependency injection provider.
*/
class MWProvider
{

	/**
	*	@access protected
	*	@var MWSession
	*/
	public static $session;

	/**
	*	@access protected
	*	@var MWContext
	*/	
	public static $context;
	
	/**
	*	@access protected
	*	@var MWFirewall
	*/	
	public static $firewall;	
	
	/**
	*	@access protected
	*	@var MWRouter
	*/	
	public static $router;	
	
	/**
	*	@access protected
	*	@var MWPackageManager
	*/	
	public static $packageManager;
	
	/**
	*	@access protected
	*	@var MWRequest
	*/	
	public static $request;
	
	/**
	*	@access protected
	*	@var MWSettingsManager
	*/	
	public static $settings;
	
	/**
	*	@access protected
	*	@var MWLog
	*/	
	public static $log;
	
	/**
	*	@access protected
	*	@var MWClassInspector
	*/	
	public static $classInspector;

	/**
	*	Creates MWSessionManager and resolves its dependencies.
	*	@access public
	*	@param string $sessionName The session name
	*/
	public static function initSession($sessionName)
	{
		
		self::$session = new \MWCore\Kernel\MWSession();
		self::$session -> setName($sessionName);
		self::$session -> start();		
		
	}
	
	/**
	*	Creates MWSettingsManager and resolves its dependencies.
	*	@access public
	*/	
	public static function initSettingsManager()
	{

		self::$settings = new \MWCore\Kernel\MWSettingsManager();		
		
	}	
	
	/**
	*	Creates MWContext and resolves its dependencies.
	*	@access public
	*/	
	public static function initContext()
	{
		
		self::$context = new \MWCore\Kernel\MWContext(self::$session);
		
	}
	
	/**
	*	Creates MWFirewall and resolves its dependencies.
	*	@access public
	*/	
	public static function initFirewall()
	{
		
		self::$firewall = new \MWCore\Kernel\MWFirewall(self::$context);		
		
	}
	
	/**
	*	Creates MWRouter and resolves its dependencies.
	*	@access public
	*/	
	public static function initRouter()
	{
		
		self::$router = new \MWCore\Kernel\MWRouter(self::$firewall);
		
	}
	
	/**
	*	Creates MWPacketManager and resolves its dependencies.
	*	@access public
	*	@param array $packages The packages being loaded
	*/	
	public static function initPackageManager($packages)
	{
		
		self::$packageManager = new \MWCore\Kernel\MWPackageManager(self::$router, self::$firewall);
		
		foreach($packages as $package)
		{

			try{

				self::$packageManager -> registerPackage($package);

			}catch(\MWcore\Exception\MWPackageLoadException $e){

				\MWCore\Kernel\MWLog::getInstance() -> add($e);

			}	

		}		
		
	}
	
	/**
	*	Creates MWRequest and resolves its dependencies.
	*	@access public
	*/	
	public static function initRequest()
	{
		
		self::$request = new \MWCore\Component\MWRequest();
		
	}
	
	/**
	*	Creates MWLog and resolves its dependencies.
	*	@access public
	*/	
	public static function initLog()
	{

		self::$log = \MWCore\Kernel\MWLog::getInstance();
		
	}
	
	/**
	*	Creates MWClassInspector and resolves its dependencies.
	*	@access public
	*/	
	public static function initClassInspector()
	{
		
		self::$classInspector = \MWCore\Kernel\MWClassInspector::getInstance();
		
	}
	
	/**
	*	Creates a controller by given name, and resolves its dependencies.
	*	@access public
	*	@param string $controllerName The controller name
	*	@return MWController
	*/	
	public static function makeController($controllerName)
	{
		
		if(!class_exists($controllerName)) return false;

		$controller = new $controllerName;

		$controller -> setSession(self::$session);
		$controller -> setContext(self::$context);
		$controller -> setRequest(self::$request);
		$controller -> setSettings(self::$settings);
		$controller -> setInspector(self::$classInspector);
		$controller -> setLog(self::$log);
		
		return $controller;
		
	}
	
}