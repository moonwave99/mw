<?php

namespace Backstage\Controller;

use Backstage\Controller\BackstageController;
use MWCore\Entity\MWEntity;
use MWCore\Component\MWCollection;
use Backstage\Library\UploadHandler;

class CrudController extends BackstageController
{

	protected $entityname;
	protected $entitylabel;	

	public function __construct($session, $context, $request, $settings, $entityname, $entitylabel)
	{
		
		parent::__construct($session, $context, $request, $settings);
		
		$this -> entityname = $entityname;
		$this -> entitylabel = $entitylabel;
		
	}
	
	public function getAction()
	{

		$repName = MWEntity::getRepositoryNameFromClass($this -> entityname);

		$rep = new $repName;

		$entity = $rep -> findOneById($this -> request -> id);
		
		$tempRow = array();
		
		$info = $this -> getEntityInfo($this -> entityname);

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

		$info = $this -> getEntityInfo($this -> entityname);
		
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

		$entity = $this -> _bindRequest();

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
	
	protected function _bindRequest()
	{
		
		if($this -> request -> id != 0){

			$repName = MWEntity::getRepositoryNameFromClass($this -> entityname);
			$rep = new $repName;
			$entity = $rep -> findOneById($this -> request -> id);

		}else{
			
			$entity = new $this -> entityname;			
			
		}

		$fieldInfo = $this -> inspector -> getAnnotationsForEntity($entity);

		$tmpEntity = null;
		$tmpEntityName = null;
		$tmpList = null;
		$tmpAnnotationName = null;

		foreach($fieldInfo as $field)
		{
			
			if($this -> request -> $field['name'] != ''){

				$tmpAnnotationName = array_shift(array_keys($field['annotations']));
		
				switch($tmpAnnotationName){

					case "MWCore\Annotation\Field":

						$entity -> $field['name'] = $this -> request -> $field['name'];
					
						break;

					case "MWCore\Annotation\OneToOne":
					case "MWCore\Annotation\ManyToOne":					

						$entity -> $field['name'] -> id = $this -> request -> $field['name'];

						break;

					case "MWCore\Annotation\ManyToMany":		

						$entity -> $field['name'] = new MWCollection();	

						foreach($this -> request -> $field['name'] as $v){

							$tmpEntityName = $field['annotations'][$tmpAnnotationName][0] -> entity;
							$tmpEntity = new $tmpEntityName;
							$tmpEntity -> id = $v;

							$entity -> $field['name'] -> add($tmpEntity);

						}

						break;

					default:
						
						break;

				}			
				
			}		
			
		}
		
		return $entity;		
		
	}
	
}