<?php

namespace Backstage\Controller;

use MWCore\Entity\MWEntity;
use MWCore\Component\MWCollection;
use MWCore\Controller\MWController;
use Backstage\Library\UploadHandler;

class CrudController extends MWController
{

	protected $entityname;
	protected $entitylabel;
	
	protected $helper;

	public function __construct($entityname, $entitylabel)
	{

		$this -> entityname = $entityname;
		$this -> entitylabel = $entitylabel;
		
	}
	
	public function setHelper($helper){$this -> helper = $helper;}
	
	public function indexAction()
	{

		$info = $this -> helper -> getEntityInfo($this -> entityname);		
		$newForm = $this -> helper -> createNewEntityForm($this -> entityname, $this -> entitylabel);			
		$nav = $this -> helper -> getNavigationEntries($this -> context -> getUser() -> role);
		
		$this -> requestView("Backstage\View\item-list", array(
			'pageTitle'			=> sprintf('MW | Manage %s', $nav['entities']['entries'][$this -> entitylabel] -> label ?: ucwords($this -> entitylabel)."s"),
			'title'				=> sprintf('Manage %s', $nav['entities']['entries'][$this -> entitylabel] -> label ?: ucwords($this -> entitylabel)."s"),
			'nav'				=> $nav,			
			'entity'			=> $this -> entitylabel,
			'fields'			=> $info,
			'newForm'			=> $newForm,
			'editForm'			=> $newForm,
			'editSettingsForm'	=> $this -> helper -> createEditSettingsForm($this -> settings)			
		));
		
	}	

	public function getAction()
	{

		$repName = MWEntity::getRepositoryNameFromClass($this -> entityname);
		$rep = new $repName;

		$entity = $rep -> findOneById($this -> request -> id);
	
		$encodedEntity = $this -> encodeSingleEntity($entity, 'form');
		
		$this -> json(array('entity' => $entity === false ? new $this -> entityname : $encodedEntity));
		
	}
	
	public function listAction()
	{	
		
		$rep = MWEntity::createRepository($this -> entityname);

		$results = array();
		
		foreach($rep -> findAll() -> toArray() as $r)
		{
			
			$results[] = $this -> encodeSingleEntity($r, 'table');
			
		}
		
		$this -> json($results);
		
	}
	
	public function saveAction()
	{

		if($this -> request -> getMethod() != 'POST' || $this -> csrfCheck() !== true)
			exit;
		
		$entity = $this -> bindRequest($this -> entityname);

		($this -> request -> id != 0) ? $entity -> update() : $entity -> create();

		return $this -> json(array(
			'status'	=> 'OK',
			'message'	=> 'Saved succesfully!',
			'entity'	=>  $this -> encodeSingleEntity($entity -> hydrate(), 'table')
		));

	}
	
	public function deleteAction()
	{
		
		if($this -> request -> getMethod() != 'POST' || $this -> csrfCheck() !== true)
			exit;

		$entity = null;

		foreach( (array)$this -> request -> id as $id )
		{

			$entity = new $this -> entityname;				
			$entity -> id = $id;
			$entity -> delete();
			
		}
		
		return $this -> json(array(
			'status'	=> 'OK',
			'message'	=> 'Deleted succesfully!'
		));		
		
	}
	
	public function uploadAction()
	{
		

		
	}
	
	protected function encodeSingleEntity($entity, $mode)
	{

		$info = $this -> helper -> getEntityInfo(get_class($entity));		
		$encodedEntity = array();
		$encodedEntity = array('id' => $entity -> id); 		
		
		$tempString = "";
		$tempArray = NULL;
		$tempObject = NULL;

		foreach($info as $i)
		{
			
			if($i -> target != 'both' && $i -> target != $mode) continue;

			switch($i -> inputMode)
			{
				
				case "select-multiple":
				
					if($mode == 'table'){

						$tempString = "";

						foreach($entity -> {$i -> name} -> toArray() as $key => $e)
						{

							$tempString .= $e -> __toString().", ";

						}

						$encodedEntity[$i -> name] = substr($tempString, 0, -2);
						
					}else{
						
						$tempArray = array();

						foreach($entity -> {$i -> name} -> toArray() as $key => $e)
						{

							$tempArray[] = $e -> id;

						}

						$encodedEntity[$i -> name] = $tempArray;						
						
					}

					break;
					
				case "select":
				
					if($mode == 'table'){
						
						$encodedEntity[$i -> name] = $entity -> {$i -> name} -> __toString();
						
					}else{
						
						$encodedEntity[$i -> name]  = array($entity -> {$i -> name} -> id);
						
					}

					break;
					
				case "date":
					$encodedEntity[$i -> name] = $entity -> {$i -> name} -> format('M d Y');
					break;
					
				case "checkbox":
					$encodedEntity[$i -> name] = $entity -> {$i -> name} == 1 ? "Yes" : "No";
					break;
					
				case "radio-boolean":
					$encodedEntity[$i -> name] = $entity -> {$i -> name} == 1 ? "Yes" : "No";
					break;						
					
				default:
					$encodedEntity[$i -> name] = html_entity_decode($entity -> {$i -> name}, ENT_QUOTES, 'UTF-8');
					break;
			
			}

		}

		return $encodedEntity;	
		
	}	
		
}