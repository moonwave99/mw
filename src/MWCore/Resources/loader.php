<?php

use MWCore\Kernel\MWPackage;
use MWCore\Kernel\MWSingleRoute;

$package = new MWPackage("MWCore", "MW");

$package -> addConstants(
	array(
		"MW_LOGIN_PATH"		=> "login",
		"MW_LOGOUT_PATH"	=> "logout"
	)
);