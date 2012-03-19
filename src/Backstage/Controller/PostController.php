<?php

namespace Backstage\Controller;

use Backstage\Controller\CrudController;

class PostController extends CrudController
{

	public function __construct()
	{
		
		parent::__construct("App\Entity\Post", "post");

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
	
	public function galleryAction()
	{
		
		$repName = \MWCore\Entity\MWEntity::getRepositoryNameFromClass($this -> entityname);
		$rep = new $repName;
		
		$entity = $rep -> findOneById($this -> request -> id);
		
		$this -> json(array(
			'pictures' => $entity -> pictureList -> toArray()
		));		
		
	}
	
	public function savepicsAction()
	{
		
		$repName = \MWCore\Entity\MWEntity::getRepositoryNameFromClass($this -> entityname);
		$rep = new $repName;
		
		$entity = $rep -> findOneById($this -> request -> id);

		$entity -> pictureList -> clear();

		foreach($this -> request -> pics as $pic)
		{
			
			$entity -> pictureList -> add(new \App\Entity\Picture($pic));
			
		}
		
		$entity -> update();
		
		return $this -> json(array(
			'status'	=> 'OK',
			'message'	=> 'Removed succesfully!',
		));		
		
	}
		
}