<?php

namespace Backstage\Controller;

use Backstage\Controller\CrudController;

class UserController extends CrudController
{

	public function __construct()
	{
		
		parent::__construct("MWCore\Entity\MWUser", "user");

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