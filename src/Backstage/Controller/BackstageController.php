<?php

namespace Backstage\Controller;

use MWCore\Controller\MWController;

class BackstageController extends MWController
{
	
	public function indexAction()
	{

		$this -> requestView("Backstage\View\index", array(
			'pageTitle'	=> 'MW | Backstage',
			'title'		=> 'This is the backstage.'
		));		

	}	
	
}