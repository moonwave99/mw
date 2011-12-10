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
	define("DOMAIN",		"");	
	define("BASE_PATH",		"");
	define("ASSET_PATH",	"");
	define("MW_CORE",		__DIR__."/src/MWCore/");	
	define("MW_VIEWS",		__DIR__."/src/MWCore/View/");
	define("MW_RESOURCES",	__DIR__."/src/MWCore/Resources");		
	define("APP_VIEWS",		__DIR__."/src/App/View/");
	define("APP_RESOURCES",	__DIR__."/src/App/Resources/");
	
	// Server Config
	define('REWRITE_RULE', '^(.*)$ index.php');
	
	// DB Config
	define("DB_HOST", "");
	define("DB_NAME", "");
	define("DB_USER", "");
	define("DB_PASS", "");
	
	// Login Config
	define("LOGIN_PATH", "login");
	define("LOGOUT_PATH", "logout");	
	define("LOGIN_ENTRANCE", "admin");
	
	// Session Start Baby
	$session = MWSession::getInstance();
	$session -> setName('mwlabs');
	$session -> start();	

	// Routes Config - Backend
	$backRoutes = array(

	);
	
	// Routes Config - Frontend	
	$frontRoutes = array(				
		new MWSingleRoute("{page}",					"App\Controller\PagesController",	"showPage"),		
		new MWSingleRoute("",						"App\Controller\PagesController",	"index"),
	);
	
	// Firewall Config
	$firewall = MWFirewall::getInstance() -> setRules(array(
		new MWFirewallRule(LOGIN_ENTRANCE, 'ROLE_ADMIN', LOGIN_PATH),
	));
	
	// Let's go!
	$router = MWRouter::getInstance();	
	$router -> setRoutes(array_merge($backRoutes, $frontRoutes));
	$router -> routeRequest();
