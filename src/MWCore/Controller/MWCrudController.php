<?php

namespace App\Controller\Crud;

use MWCore\Controller\MWController;
use MWCore\Entity\MWEntity;
use MWCore\Component\MWCollection;

class CrudController extends MWController
{

	protected $entityname;
	protected $entitylabel;	

	public function __construct($session, $context, $request, $settings)
	{
		
		parent::__construct($session, $context, $request, $settings, );
		
		$this -> entityname = $entityname;
		$this -> entitylabel = $entitylabel;
		
	}
	
	public function indexAction()
	{

		$this -> requestView("App\View\Admin\listitems", array(
			'pageTitle'	=> sprintf('Sostanze Records. | Manage %ss', ucwords($this -> entitylabel)),
			'title'		=> 'Admin Area',
			'entity'	=> $this -> entitylabel,
			'fields'	=> call_user_func(array($this -> entityname, 'getCRUDFields'))
		));
		
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

		$data = array('aaData' => array());
		
		$repName = MWEntity::getRepositoryNameFromClass($this -> entityname);
		
		$rep = new $repName;
		
		foreach($rep -> findAll() -> toArray() as $r)
		{
			
			$data['aaData'][] =	array_merge(
				(array)sprintf(
					'<input name="%s-list-single" type="checkbox" data-entity="%s" value="%s"/>',
					strtolower($this -> entitylabel),
					strtolower($this -> entitylabel),
					$r -> id
				),
				$r -> toDataTable(),
				(array)sprintf('
				<ul class="item-actions">
					<li>
						<button title="Edit" data-icon="pencil" data-controller="%s" data-action="edit" data-id="%s"></button>
					</li>
					<li>
						<button title="Delete" data-icon="trash" data-controller="common" data-entity="%s" data-action="delete" data-id="%s"></button>
					</li>
				</ul>',
					strtolower($this -> entitylabel),
					$r -> id,
					strtolower($this -> entitylabel),
					$r -> id					
				)
			);
			
		}
		
		$this -> json($data);
		
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