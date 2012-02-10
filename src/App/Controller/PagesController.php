<?php

namespace App\Controller;

use MWCore\Controller\MWController;

class PagesController extends MWController
{
	
	public function indexAction()
	{
		
		$this -> requestView("App\View\index", array(
			'pageTitle'	=> 'MW | Hello World!',
			'title'		=> 'Hello World!'
		));		
		
	}
	
	public function showPageAction($page)
	{

		$this -> requestView("App\View\Pages\\".$page, array(
			'pageTitle'	=> 'MW. | '.ucwords($page),
			'title'		=> ucwords($page)			
		));

	}	
	
}