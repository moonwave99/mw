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
		
		$tempRow = array();
		
		$info = $this -> helper -> getEntityInfo($this -> entityname);

		foreach($info as $i)
		{
			
			if($i -> target != 'both' && $i -> target != 'form') continue;

			switch($i -> inputMode)
			{
				
				case "select-multiple":
					$tempRow[$i -> name] = $entity -> {$i -> name} -> toArray();					
					break;
					
				case "select":
					$tempRow[$i -> name] = $entity -> {$i -> name};
					break;
					
				case "date":
					$tempRow[$i -> name] = $entity -> {$i -> name} -> format('M d Y');
					break;
					
				case "checkbox":
					$tempRow[$i -> name] = $entity -> {$i -> name} == 1 ? "Yes" : "No";
					break;
					
				default:
					$tempRow[$i -> name] = html_entity_decode($entity -> {$i -> name}, ENT_QUOTES, 'UTF-8');
					break;
			
			}

		}		
		
		$this -> json(array('entity' => $entity === false ? new $this -> entityname : $tempRow));
		
	}
	
	public function listAction()
	{	
		
		$rep = MWEntity::createRepository($this -> entityname);

		$results = array();
		$tempRow = NULL;
		$tempString = NULL;
		
		$info = $this -> helper -> getEntityInfo($this -> entityname);
		
		foreach($rep -> findAll() -> toArray() as $r)
		{
			
			$tempRow = array('id' => $r -> id);
			
			foreach($info as $i)
			{
				
				if($i -> target != 'both' && $i -> target != 'table') continue;

				switch($i -> inputMode)
				{
					
					case "picture":
						
						$tempRow[$i -> name] = sprintf(
							'<img src="%s" alt=""/>',
							BASE_PATH."thumbnails/" . $r -> {$i -> name}
						);
						
						break;
					
					case "select-multiple":
					
						$tempString = "";
						
						foreach($r -> {$i -> name} -> toArray() as $e)
						{
							
							$tempString .= $e.", ";
							
						}
						
						$tempRow[$i -> name] = substr($tempString, 0, -2);
						
						break;
						
					case "select":
						$tempRow[$i -> name] = $r -> {$i -> name} -> __toString();
						break;
						
					case "date":
						$tempRow[$i -> name] = $r -> {$i -> name} -> format('M d Y');
						break;
						
					case "checkbox":
						$tempRow[$i -> name] = $r -> {$i -> name} == 1 ? "Yes" : "No";
						break;
						
					case "radio-boolean":
						$tempRow[$i -> name] = $r -> {$i -> name} == 1 ? "Yes" : "No";
						break;						
						
					default:
						$tempRow[$i -> name] = $r -> {$i -> name};
						break;
					
				}

			}
			
			$results[] = $tempRow;
			
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
			'message'	=> 'Saved succesfully!'
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
		
}