<?php	

// Gets page excecution start time
$startTime = microtime(true);

// Requires basic configuration
require "config.php";

// There should be a bootstrap anyhow
define("SRC_PATH", __DIR__."/src/");
include( SRC_PATH."MWCore/Resources/bootstrap.php" );

// Let's go baby!
\MWCore\Kernel\MWRouter::getInstance() -> routeRequest();