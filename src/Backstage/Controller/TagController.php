<?php

namespace Backstage\Controller;

use Backstage\Controller\CrudController;

class TagController extends CrudController
{

	public function __construct($session, $context, $request, $settings)
	{
		
		parent::__construct($session, $context, $request, $settings, "App\Entity\Tag", "tag");

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