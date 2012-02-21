<?php

require(SRC_PATH."MWCore/Libraries/addendum/annotations.php");
require(SRC_PATH."MWCore/Libraries/mw/useful.inc.php");

class MWAutoloader
{

	public function __construct()
	{
		
		spl_autoload_extensions(".php");
		spl_autoload_register(array($this, 'loader'));
		
	}

	protected function loader($className)
	{
		
		$className = str_replace("\\", DIRECTORY_SEPARATOR, $className);

		file_exists(SRC_PATH.DIRECTORY_SEPARATOR.$className.'.php') && require(SRC_PATH.DIRECTORY_SEPARATOR.$className.'.php');
			
	}

}

$autoloader = new MWAutoloader();

// Sets debug environment
DEBUG === true && \MWCore\Kernel\MWLog::getInstance() -> setStartTime($startTime);

// Session Start Baby
$session = new \MWCore\Kernel\MWSession();
$session -> setName(SESSION_NAME);
$session -> start();

$context = new \MWCore\Kernel\MWContext($session);
$firewall = new \MWCore\Kernel\MWFirewall($context);
$router = new \MWCore\Kernel\MWRouter($session, $context, $firewall);

$packageManager = new \MWCore\Kernel\MWPackageManager($router, $firewall);

foreach($packages as $package)
{
	
	try{

		$packageManager -> registerPackage($package);

	}catch(\MWcore\Exception\MWPackageLoadException $e){

		\MWCore\Kernel\MWLog::getInstance() -> add($e);

	}	
	
}
