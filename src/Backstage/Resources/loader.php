<?php

use MWCore\Kernel\MWPackage;
use MWCore\Kernel\MWSingleRoute;
use MWCore\Kernel\MWFirewallRule;

$package = new MWPackage("Backstage", "BACK");

$package -> addConstants(
	array(
		'TMP_UPLOAD_FOLDER'	=> SRC_PATH.'../tmp/',
		'THUMBNAIL_FOLDER'	=> SRC_PATH.'../thumbnails/',		
		'UPLOAD_FOLDER'		=> SRC_PATH.'../web/img/uploads/',
	)
);

$package -> addRoutes(
	array(

		new MWSingleRoute("backstage/settings/save",		"Backstage\Controller\BackstageController", 	"saveSettings"),
		new MWSingleRoute("backstage/profile",				"Backstage\Controller\BackstageController", 	"profile"),
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