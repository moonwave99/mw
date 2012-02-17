<?php

use MWCore\Kernel\MWSingleRoute;
use MWCore\Kernel\MWFirewallRule;

$signature = "APP";

$constants = array();

$routes = array(
	
	// Test Routes				
	new MWSingleRoute("test",				"App\Controller\TestController", 	"test"),	
	
	// Basic Routes				
	new MWSingleRoute("{page}",				"App\Controller\PagesController",	"showPage"),		
	new MWSingleRoute("",					"App\Controller\PagesController",	"index"),
	
);	

$rules = array(
	new MWFirewallRule('admin', 'ROLE_ADMIN', "")
);