<?php

use MWCore\Kernel\MWPackage;
use MWCore\Kernel\MWSingleRoute;
use MWCore\Kernel\MWFirewallRule;

$package = new MWPackage("App", "APP");

$package -> addRoutes(
	array(
	
		// Test Routes				
		new MWSingleRoute("test",				"App\Controller\TestController", 	"test"),	
	
		// Basic Routes				
		new MWSingleRoute("{page}",				"App\Controller\PagesController",	"showPage"),		
		new MWSingleRoute("",					"App\Controller\PagesController",	"index"),
		
	)
);

$package -> addRules(
	array(
		new MWFirewallRule('admin', 'ROLE_ADMIN', "")
	)
);