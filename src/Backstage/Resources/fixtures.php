<?php

// Creates basic user
$user = new \MWCore\Entity\MWUser();
$user -> username = "admin";
$user -> setPassword("admin");
$user -> role = ROLE_ADMIN;
$user -> email = "admin@yoursite.com";

$user -> create();	

// Loads site settings
$settings = array(
    array('SITE_OWNER',			'Site Owner',				'Diego Caponera',							'string'), 
    array('CONTACT_EMAIL', 		'Contact Email', 			'diego.caponera@gmail.com',					'email'),
    array('GOOGLE_ANALYTICS',	'Google Analytics Code',	'UA-5216615-7',								'string'),
    array('GITHUB',				'Github Page',				'http://www.github.com/moonwave99',			'url'),
    array('LINKEDIN',			'Linkedin Page',			'http://it.linkedin.com/in/diegocaponera',	'url'),
    array('TWITTER',			'Twitter Account',			'http://twitter.com/#!/moonwavelabs',		'url'),
);

foreach($settings as $s)
{
	
	\MWCore\Kernel\MWProvider::$settings -> saveSetting($s[0], $s[2], $s[1], $s[3]);
	
}