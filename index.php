<?php	

// Gets page excecution start time
$startTime = microtime(true);

require "config.php";

// There should be a bootstrap anyhow
define("SRC_PATH", __DIR__."/src/");
include( SRC_PATH."MWCore/Resources/bootstrap.php" );

// Sets debug environment
DEBUG === true && \MWCore\Kernel\MWLog::getInstance() -> setStartTime($startTime);

// Session Start Baby
$session = \MWCore\Kernel\MWSession::getInstance();
$session -> setName(SESSION_NAME);
$session -> start();

$packetManager = \MWCore\Kernel\MWPackageManager::getInstance();

foreach($packages as $package)
{
	
	try{

		$packetManager -> registerPackage($package);

	}catch(\MWcore\Exception\MWPackageLoadException $e){

		\MWCore\Kernel\MWLog::getInstance() -> add($e);

	}	
	
}

\MWCore\Kernel\MWRouter::getInstance() -> routeRequest();