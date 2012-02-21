<?php

use MWCore\Kernel\MWPackage;
use MWCore\Kernel\MWSingleRoute;

$package = new MWPackage("MWCore", "MW");

$package -> addConstants(
	array(
		"MW_LOGIN_PATH"		=> "login",
		"MW_LOGOUT_PATH"	=> "logout",
		"MW_LOGIN_ENTRANCE"	=> "backstage",		
		"MW_LOGIN_ENTITY"	=> "\MWCore\Entity\MWUser"
	)
);

$package -> addRoutes(
	array(
		
		// Test Routes				
		new MWSingleRoute("test",			"MWCore\Controller\MWTestController", 		"test"),		
		
		// Security Routes
		new MWSingleRoute("login",			"MWCore\Controller\MWSecurityController", 	"login"),	
		new MWSingleRoute("login_check",	"MWCore\Controller\MWSecurityController", 	"loginCheck"),	
		new MWSingleRoute("logout",			"MWCore\Controller\MWSecurityController", 	"logout"),
				
	)
);