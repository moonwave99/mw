<?php

namespace App\Controller;

use MWCore\Controller\MWController;

class PagesController extends MWController
{
	
	public function indexAction()
	{
		
		$this -> requestView("index", array(
			'pageTitle'	=> 'MW. | Hello, World!',
			'title'		=> 'Hello World!'			
		));		
		
	}
	
	public function showPageAction($page)
	{

		$this -> requestView("Pages\\".$page, array(
			'pageTitle'	=> 'MW. | '.ucwords($page),
			'title'		=> ucwords($page)			
		));

	}	
	
}