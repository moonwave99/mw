<?php

namespace Backstage\Controller;

use Backstage\Controller\CrudController;

class UserController extends CrudController
{

	public function __construct($session, $context, $request, $settings)
	{
		
		parent::__construct($session, $context, $request, $settings, "MWCore\Entity\MWUser", "user");

	}	

	public function indexAction()
	{

		parent::indexAction();
		
	}
	
	public function getAction()
	{

		parent::getAction();
		
	}
	
	public function listAction()
	{	

		parent::listAction();
		
	}
	
	public function saveAction()
	{

		parent::saveAction();
		pre($this -> request);
		pre($this -> log -> flush());

	}
	
	public function deleteAction()
	{
		
		parent::deleteAction();	
		
	}
	
	public function uploadAction()
	{
		
		parent::uploadAction();
		
	}	
		
}