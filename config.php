<?php

// Gets page excecution start time
$startTime = microtime(true);

// There should be a bootstrap anyhow
define("SRC_PATH", 		__DIR__."/src/");
include( SRC_PATH."MWCore/Resources/bootstrap.php" );

// Require basic components
use MWCore\Kernel\MWSession;
use MWCore\Kernel\MWLog;
use MWCore\Kernel\MWPackageManager;

// Sets debug environment
define('DEBUG', true);
if(DEBUG === true){

	MWLog::getInstance() -> setStartTime( $startTime );
	
}

// Paths and Site Config
define("DOMAIN",		"http://localhost/");	
define("BASE_PATH",		DOMAIN."_projects/mw/");
define("ASSET_PATH",	DOMAIN."_projects/mw/web/");

// Server Config
define('REWRITE_RULE',	'^(.*)$ index.php');
define("GOOGLE_ANALYTICS", "");

// DB Config
define("DB_HOST", "localhost");
define("DB_NAME", "mw");
define("DB_USER", "root");
define("DB_PASS", "");

// Session Start Baby
$session = MWSession::getInstance();
$session -> setName('mw');
$session -> start();

$packetManager = MWPackageManager::getInstance();

try{
	
	$packetManager -> registerPackage("MWCore");
	$packetManager -> registerPackage("App");
	
}catch(\MWcore\Exception\MWPackageLoadException $e){
	
	MWLog::getInstance() -> add($e);
	
}
