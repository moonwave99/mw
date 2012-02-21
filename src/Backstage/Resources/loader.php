<?php

use MWCore\Kernel\MWPackage;
use MWCore\Kernel\MWSingleRoute;
use MWCore\Kernel\MWFirewallRule;

$package = new MWPackage("Backstage", "BACK");

$package -> addRoutes(
	array(

		// Main Routes				
		new MWSingleRoute("backstage",		"Backstage\Controller\BackstageController", 	"index"),	
		
	)
);

$package -> addRules(
	array(
		new MWFirewallRule('backstage', 'IS_LOGGED', "")
	)
);