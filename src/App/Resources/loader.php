<?php

use MWCore\Kernel\MWPackage;
use MWCore\Kernel\MWSingleRoute;
use MWCore\Kernel\MWFirewallRule;

$package = new MWPackage("App", "APP");

$package -> addRoutes(
	array(
		
		// Security Routes
		new MWSingleRoute("login",			"App\Controller\SecurityController", 	"login"),	
		new MWSingleRoute("login_check",	"App\Controller\SecurityController", 	"loginCheck"),	
		new MWSingleRoute("logout",			"App\Controller\SecurityController", 	"logout"),					
	
		// Test Routes				
		new MWSingleRoute("test",			"App\Controller\TestController", 		"test"),	
	
		// Basic Routes				
		new MWSingleRoute("{page}",			"App\Controller\PagesController",		"showPage"),		
		new MWSingleRoute("",				"App\Controller\PagesController",		"index"),
		
	)
);

$package -> addRules(
	array(
		new MWFirewallRule('admin', 'ROLE_ADMIN', "")
	)
);