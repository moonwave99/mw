<?php

namespace Backstage\Controller;

use Backstage\Controller\CrudController;

class %2$sController extends CrudController
{

	public function __construct($session, $context, $request, $settings)
	{
		
		parent::__construct($session, $context, $request, $settings, "%1$s", "%2$s");

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
		
		parent::uploadActionAction();
		
	}	
		
}