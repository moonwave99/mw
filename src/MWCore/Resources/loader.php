<?php

use MWCore\Kernel\MWPackage;
use MWCore\Kernel\MWSingleRoute;

$package = new MWPackage("MWCore", "MW");

$package -> addConstants(
	array(
		
		// Security Constants
		"MW_LOGIN_PATH"		=> "login",
		"MW_LOGOUT_PATH"	=> "logout",
		"MW_LOGIN_ENTRANCE"	=> "backstage",		
		"MW_LOGIN_ENTITY"	=> "\MWCore\Entity\MWUser",
		
		// Role Constants
		"IS_LOGGED"			=> 1,
		"ROLE_ADMIN"		=> 2,
		"ROLE_USER"			=> 3
		
	)
);

$package -> addRoutes(
	array(
		
		// Test Routes				
		new MWSingleRoute("test",					"MWCore\Controller\MWTestController", 		"test"),		
		
		// Security Routes
		new MWSingleRoute("login",					"MWCore\Controller\MWSecurityController", 	"login"),	
		new MWSingleRoute("login_check",			"MWCore\Controller\MWSecurityController", 	"loginCheck"),	
		new MWSingleRoute("logout",					"MWCore\Controller\MWSecurityController", 	"logout"),
		
		// Captcha Routes	
		new MWSingleRoute("captcha/image/{seed}",	"MWCore\Controller\MWSecurityController", "captchaImage"),
		new MWSingleRoute("captcha/seed",			"MWCore\Controller\MWSecurityController", "captchaSeed"),		
				
	)
);