<?php

// Framework Config
define("SRC_PATH", __DIR__."/src/");
define('DEBUG', true);
define("SESSION_NAME", "mw");

// Packages to load
$packages = array(
	"MWCore",
	"Backstage",
	"Rest",
	"App"
);

// Solves a PHP 5.x warning
date_default_timezone_set("Europe/Berlin");

// Sets error reporting level
error_reporting(0);

// Paths and Site Config
define("DOMAIN",		'http://' . $_SERVER['SERVER_NAME']);
define("BASE_PATH",		DOMAIN . str_replace('index.php', '', $_SERVER['SCRIPT_NAME']));
define("ASSET_PATH",	BASE_PATH . "web/");

// Server Config
define('REWRITE_RULE',	'^(.*)$ index.php');

// DB Config
define("DB_HOST", "localhost");
define("DB_NAME", "mw");
define("DB_USER", "root");
define("DB_PASS", "");