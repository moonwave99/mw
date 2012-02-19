<?php

// Framework Config
define('DEBUG', true);
define("SESSION_NAME", "mw");
$packages = array("MWCore", "App");

// Paths and Site Config
define("DOMAIN",		"http://localhost/");	
define("BASE_PATH",		DOMAIN."_projects/mw/");
define("ASSET_PATH",	DOMAIN."_projects/mw/web/");

// Server Config
define('REWRITE_RULE',	'^(.*)$ index.php');

// DB Config
define("DB_HOST", "localhost");
define("DB_NAME", "mw");
define("DB_USER", "root");
define("DB_PASS", "");