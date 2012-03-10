<?php

namespace Backstage\Controller;

use Backstage\Controller\CrudController;

class %2$sController extends CrudController
{

	public function __construct()
	{
		
		parent::__construct("%1$s", "%2$s");

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