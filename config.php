<?php

	// Gets page excecution start time
	$startTime = microtime(true);

	// There should be a bootstrap anyhow
	require_once( __DIR__."/src/MWCore/bootstrap.php" );

	// Require basic components
	use MWCore\Kernel\MWSession;
	use MWCore\Kernel\MWRouter;
	use MWCore\Kernel\MWSingleRoute;
	use MWCore\Kernel\MWFirewall;
	use MWCore\Kernel\MWFirewallRule;	
	use MWCore\Kernel\MWLog;

	// Sets debug environment
	define('DEBUG', true);
	if(DEBUG === true){

		MWLog::getInstance() -> setStartTime( $startTime );
		
	}

	// Paths and Site Config
	define("DOMAIN",		"http://localhost/");	
	define("BASE_PATH",		DOMAIN."_projects/mw/");
	define("ASSET_PATH",	DOMAIN."_projects/mw/web/");
	define("SRC_PATH", 		__DIR__."/src/");
	define("MW_CORE",		__DIR__."/src/MWCore/");	
	define("MW_VIEWS",		__DIR__."/src/MWCore/View/");
	define("MW_RESOURCES",	__DIR__."/src/MWCore/Resources");		
	define("APP_VIEWS",		__DIR__."/src/App/View/");
	define("APP_RESOURCES",	__DIR__."/src/App/Resources/");
	
	define("GOOGLE_ANALYTICS", "");
	
	// Server Config
	define('REWRITE_RULE', '^(.*)$ index.php');
	
	// DB Config
	define("DB_HOST", "localhost");
	define("DB_NAME", "mw");
	define("DB_USER", "root");
	define("DB_PASS", "");

	// Login Config
	define("LOGIN_PATH", 		"login");
	define("LOGOUT_PATH",		"logout");
	
	// Session Start Baby
	$session = MWSession::getInstance();
	$session -> setName('mw');
	$session -> start();	
		
	// Routes Config - Backend
	$backRoutes = array(

	);
	
	// Routes Config - Frontend	
	$frontRoutes = array(
		
		// Test Routes				
		new MWSingleRoute("test",				"App\Controller\TestController", 	"test"),	
		
		// Basic Routes				
		new MWSingleRoute("{page}",				"App\Controller\PagesController",	"showPage"),		
		new MWSingleRoute("",					"App\Controller\PagesController",	"index"),
		
	);
	
	// Set Routes
	$router = MWRouter::getInstance();	
	$router -> setRoutes(array_merge($backRoutes, $frontRoutes));	
	
	// Firewall Config
	$firewall = MWFirewall::getInstance() -> setRules(array(
		new MWFirewallRule('admin', 'ROLE_ADMIN', ""),
	));
	