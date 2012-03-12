<?php

// Framework Config
define("SRC_PATH", __DIR__."/src/");
define('DEBUG', true);
define("SESSION_NAME", "mw");
$packages = array("MWCore", "Backstage", "App");
date_default_timezone_set("Europe/Berlin");
error_reporting(-1);

// Paths and Site Config
define("DOMAIN",		"http://localhost/");	
define("BASE_PATH",		DOMAIN."_projects/mw/index.php?");
define("ASSET_PATH",	DOMAIN."_projects/mw/web/");

// Server Config
define('REWRITE_RULE',	'^(.*)$ index.php');

// DB Config
define("DB_HOST", "localhost");
define("DB_NAME", "mw");
define("DB_USER", "root");
define("DB_PASS", "");