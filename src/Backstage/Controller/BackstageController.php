<?php

namespace Backstage\Controller;

use MWCore\Controller\MWController;
use MWCore\Kernel\MWProvider;

class BackstageController extends MWController
{
	
	public function indexAction()
	{

		$this -> requestView("Backstage\View\index", array(
			'pageTitle'	=> 'MW | Backstage.',
			'title'		=> 'This is the backstage.',
			'nav'		=> $this -> getNavigationEntries($this -> context -> getUser() -> role)
		));		

	}
	
	public function switchAction($section, $action = 'singleEntity')
	{
		
		$controller = MWProvider::makeCrudController(sprintf("Backstage\Controller\%sController", ucwords($section)));
		
		if($controller === false || !method_exists($controller, $action."Action")) \MWCore\Kernel\MWRouter::requestNotFound();
		
		call_user_func(
			array($controller,$action."Action")
		);		
		
	}
	
	public function singleEntityAction()
	{

		$this -> requestView("Backstage\View\item-list", array(
			'pageTitle'	=> sprintf('MW. | Manage %ss', ucwords($this -> entitylabel)),
			'title'		=> sprintf('Manage %ss', ucwords($this -> entitylabel)),
			'nav'		=> $this -> getNavigationEntries($this -> context -> getUser() -> role),			
			'entity'	=> $this -> entitylabel,
			'fields'	=> $this -> getDataTableInfo()
		));
		
	}	
	
	protected function getNavigationEntries($roleName)
	{
		
		$entries = array(
			"entities" => array("label" => "Manage Your Content", "entries" => array()),
			"common" => array("label" => "Very Important Things", "entries" => array()),
		);
		
		$note = NULL;
		
		foreach(glob(SRC_PATH.'App/Entity/*.php') as $e)
		{
			
			$note = $this -> inspector -> getSingleAnnotationForEntity(
				str_replace('/' , '\\', substr($e, strlen(SRC_PATH), -4)),
				'Backstage\Annotation\EntitySetup'
			);
			
			$note -> isRoleGranted($roleName) === true && array_push($entries['entities']['entries'], $note);
			
		}
		
		$userNote = $this -> inspector -> getSingleAnnotationForEntity(
			MW_LOGIN_ENTITY,
			'Backstage\Annotation\EntitySetup'
		);
		
		$userNote -> isRoleGranted($roleName) === true && array_push($entries['common']['entries'], $userNote);
		
		return $entries;	
		
	}
	
	protected function getDataTableInfo()
	{

		$info = array();
		$fields = $this -> inspector -> getAnnotationsForEntity($this -> entityname);

		foreach($fields as $field)
		{

			count($field['annotations']['Backstage\Annotation\TableField']) > 0 && $info[] = array(
			
				"name" => $field['name'],
				"label" => $field['annotations']['Backstage\Annotation\TableField'][0] -> label,
				"size" => $field['annotations']['Backstage\Annotation\TableField'][0] -> size
				
			);
			
		}
		
		return $info;
		
	}	
	
}