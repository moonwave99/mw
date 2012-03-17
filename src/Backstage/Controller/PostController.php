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
	
	public function deletepicsAction()
	{
		
		$repName = \MWCore\Entity\MWEntity::getRepositoryNameFromClass($this -> entityname);
		$rep = new $repName;
		
		$entity = $rep -> findOneById($this -> request -> id);

		foreach($this -> request -> pics as $p)
		{
			
			foreach($entity -> pictureList -> toArray() as $i => $pic)
			{
				
				if($pic -> id == $p) $entity -> pictureList -> set($i, NULL);
				
			}
			
		}
		
		$entity -> update();
		
		return $this -> json(array(
			'status'	=> 'OK',
			'message'	=> 'Removed succesfully!',
		));		
		
	}
		
}