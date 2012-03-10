<?php
	
namespace Backstage\Component;

use MWCore\Kernel\MWProvider;
use Backstage\Component\BackstageHelper;


class BackstageProvider extends MWProvider
{
	
	static $helper;
	
	public static function initHelper()
	{
		
		self::$helper = new BackstageHelper(self::$classInspector);
		
	}
	
	public static function getHelper()
	{

		self::$helper == NULL & self::initHelper();
		return self::$helper;
		
	}
	
	public static function makeCrudController($controllerName)
	{

		$controller = self::makeController($controllerName);
		if($controller === false) return false;

		$controller -> setHelper(self::getHelper());
		
		return $controller;
		
	}
	
}