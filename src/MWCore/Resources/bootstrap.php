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
\MWCore\Kernel\MWProvider::initSession(SESSION_NAME);
\MWCore\Kernel\MWProvider::initContext();
\MWCore\Kernel\MWProvider::initFirewall();
\MWCore\Kernel\MWProvider::initRouter();
\MWCore\Kernel\MWProvider::initRequest();
\MWCore\Kernel\MWProvider::initSettings();
\MWCore\Kernel\MWProvider::initPackageManager();
\MWCore\Kernel\MWProvider::initLog();

foreach($packages as $package)
{
	
	try{

		\MWCore\Kernel\MWProvider::$packageManager -> registerPackage($package);

	}catch(\MWcore\Exception\MWPackageLoadException $e){

		\MWCore\Kernel\MWLog::getInstance() -> add($e);

	}	
	
}