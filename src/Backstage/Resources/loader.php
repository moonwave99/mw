<?php

use MWCore\Kernel\MWPackage;
use MWCore\Kernel\MWSingleRoute;
use MWCore\Kernel\MWFirewallRule;

$package = new MWPackage("Backstage", "BACK");

$package -> addRoutes(
	array(

		new MWSingleRoute("backstage/{section}/{action}",	"Backstage\Controller\BackstageController", 	"switch"),					
		new MWSingleRoute("backstage/{section}",			"Backstage\Controller\BackstageController", 	"switch"),			
		new MWSingleRoute("backstage",						"Backstage\Controller\BackstageController", 	"index"),
		
	)
);

$package -> addRules(
	array(
		new MWFirewallRule('backstage', ROLE_ADMIN, MW_LOGIN_PATH)
	)
);