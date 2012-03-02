<?php

namespace Backstage\Controller;

use Backstage\Controller\BackstageController;
use MWCore\Entity\MWEntity;
use MWCore\Component\MWCollection;

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
		
		$this -> json(array('entity' => $entity === false ? new $this -> entityname : $entity));
		
	}
	
	public function listAction()
	{	
		
		$rep = MWEntity::createRepository($this -> entityname);

		$this -> json($rep -> findAll() -> toArray());
		
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
		
		if($this -> request -> getMethod() == 'POST'){
			
			$allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');

			$sizeLimit = 10 * 1024 * 1024;

			$uploader = new \App\Library\qqFileUploader($allowedExtensions, $sizeLimit, $this -> request);
			$this -> json( $result = $uploader -> handleUpload(APP_PICTURES_PATH.'tmp/') );
			
		}
		
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

						$tmpEntityName = $field['annotations'][$tmpAnnotationName][0] -> entity;
						$tmpEntity = new $tmpEntityName;
						$tmpEntity -> id = $this -> request -> $field['name'];

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