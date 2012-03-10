<?php
	
namespace MWCore\Kernel;

class MWProvider
{

	public static $session;
	
	public static $context;
	
	public static $firewall;	
	
	public static $router;	
	
	public static $packageManager;
	
	public static $request;
	
	public static $settings;
	
	public static $log;
	
	public static $classInspector;

	public static function initSession($sessionName)
	{
		
		self::$session = new \MWCore\Kernel\MWSession();
		self::$session -> setName($sessionName);
		self::$session -> start();		
		
	}
	
	public static function initSettingsManager()
	{

		self::$settings = new \MWCore\Kernel\MWSettingsManager();		
		
	}	
	
	public static function initContext()
	{
		
		self::$context = new \MWCore\Kernel\MWContext(self::$session);
		
	}
	
	public static function initFirewall()
	{
		
		self::$firewall = new \MWCore\Kernel\MWFirewall(self::$context);		
		
	}
	
	public static function initRouter()
	{
		
		self::$router = new \MWCore\Kernel\MWRouter(self::$firewall);
		
	}
	
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
	
	public static function initRequest()
	{
		
		self::$request = new \MWCore\Component\MWRequest();
		
	}
	
	public static function initLog()
	{

		self::$log = \MWCore\Kernel\MWLog::getInstance();
		
	}
	
	public static function initClassInspector()
	{
		
		self::$classInspector = \MWCore\Kernel\MWClassInspector::getInstance();
		
	}
	
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