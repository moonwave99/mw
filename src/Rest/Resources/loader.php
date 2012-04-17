<?php

use MWCore\Kernel\MWPackage;
use MWCore\Kernel\MWSingleRoute;
use MWCore\Kernel\MWFirewallRule;

$package = new MWPackage("Rest", "REST");

$package -> addConstants(
	array(
		"REST_BASEPATH" => "rest"
	)
);

$package -> addRoutes(
	array(

		new MWSingleRoute("rest/*",		"Rest\Controller\RestController", 	"index"),
		
	)
);