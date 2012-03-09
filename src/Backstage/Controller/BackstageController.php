<?php

namespace Backstage\Controller;

use MWCore\Controller\MWController;
use MWCore\Entity\MWEntity;
use MWCore\Kernel\MWProvider;
use Backstage\Component\Form\BackstageForm;

class BackstageController extends MWController
{

	public function switchAction($section, $action = 'singleEntity')
	{

		$controller = MWProvider::makeCrudController(sprintf("Backstage\Controller\%sController", ucwords($section)));
		
		if($controller === false || !method_exists($controller, $action."Action")) \MWCore\Kernel\MWRouter::requestNotFound();

		call_user_func(
			array($controller,$action."Action")
		);		
		
	}
	
	public function indexAction()
	{

		$this -> requestView("Backstage\View\index", array(
			'pageTitle'			=> 'MW | Backstage.',
			'title'				=> 'This is the backstage.',
			'nav'				=> $this -> getNavigationEntries($this -> context -> getUser() -> role),
			'editSettingsForm'	=> $this -> createEditSettingsForm()
		));		

	}
	
	public function profileAction()
	{

		$this -> requestView("Backstage\View\profile", array(
			'pageTitle'			=> 'MW | Profile.',
			'title'				=> 'This is your profile page.',
			'nav'				=> $this -> getNavigationEntries($this -> context -> getUser() -> role),
			'editSettingsForm'	=> $this -> createEditSettingsForm()					
		));		

	}	
	
	public function saveSettingsAction()
	{

		if($this -> request -> getMethod() != 'POST' || $this -> csrfCheck() !== true)
			exit;
			
		foreach($this -> settings -> getAll() -> toArray() as $s)
		{
	
			$s -> value = $this -> request -> {$s -> key};
			
			$s -> update();
			
		}
		
		return $this -> json(array(
			'status'	=> 'OK',
			'message'	=> 'Saved succesfully!'
		));		
		
	}	

	public function singleEntityAction()
	{
	
		$info = $this -> getEntityInfo($this -> entityname);		
		$nav = $this -> getNavigationEntries($this -> context -> getUser() -> role);
		$newForm = $this -> createNewEntityForm($info);			

		$this -> requestView("Backstage\View\item-list", array(
			'pageTitle'			=> sprintf('MW | Manage %s', $nav['entities']['entries'][$this -> entitylabel] -> label ?: ucwords($this -> entitylabel)."s"),
			'title'				=> sprintf('Manage %s', $nav['entities']['entries'][$this -> entitylabel] -> label ?: ucwords($this -> entitylabel)."s"),
			'nav'				=> $nav,			
			'entity'			=> $this -> entitylabel,
			'fields'			=> $info,
			'newForm'			=> $newForm,
			'editForm'			=> $newForm,
			'editSettingsForm'	=> $this -> createEditSettingsForm()			
		));
		
	}
	
	protected function createNewEntityForm($info)
	{

		$form = new BackstageForm(array(
			
			'action'			=> sprintf(BASE_PATH."backstage/%s/save", strtolower($this -> entitylabel)),
			'method'			=> 'post',
			'data-controller'	=> 'common',
			'data-action'		=> 'save'
			
		));

		foreach($info as $f)
		{
			
			if($f -> target !== 'form' && $f -> target !== 'both')
				continue;

			switch($f -> inputMode){
				
				case "picture":

					$form -> addPicture($f -> name, $f -> label, NULL);
				
					break;
					
				case "radio-boolean":
				
					
				
					break;

				case "textarea":

					$form -> addTextarea($f -> name, $f -> label, array(
					
						'id'		=> "_".$f -> name,
						'rows'		=> 5,
						'required'	=> $f -> default === NULL ? "required" : NULL,
						'data-rich'	=> $f -> rich ? 'true' : NULL,
						'class'		=> 'span4',
					
					));
				
					break;
					
				case "select":
				
					$options = $this -> getOptionList($f -> entity);
					array_unshift($options, array('value' =>''));

					$form -> addSelect($f -> name, $f -> label, $options, array(
					
						'id'				=> "_".$f -> name,
						'required'			=> $f -> default === NULL ? "required" : NULL,
						'data-placeholder'	=> 'Choose From List',
						'class'				=> 'span4',
						
					));
					
					break;
					
				case "select-multiple":

					$form -> addSelect($f -> name, $f -> label, $this -> getOptionList($f -> entity), array(

						'id'		=> "_".$f -> name,
						'required'	=> $f -> default === NULL ? "required" : NULL,
						'multiple'	=> true,
						'class'		=> 'span4',								

					));

					break;					

				case "text":				
				case "number":				
				case "email":				
				case "checkbox":
				
					$form -> addField($f -> inputMode, $f -> name, $f -> label, array(
						
						'id'		=> "_".$f -> name,
						'required'	=> $f -> default === NULL ? "required" : NULL,
						'maxlength'	=> $f -> length,
						'class'		=> 'span4',						
						
					));
				
					break;
				
			}
			
		}		

		$form -> addHidden('id', array(
			'value' => 0
		));		
		
		return $form;
		
	}
	
	protected function createEditSettingsForm()
	{

		$form = new BackstageForm(array(
			
			'action'			=> sprintf(BASE_PATH."backstage/settings/save"),
			'method'			=> 'post',
			'data-controller'	=> 'settings',
			'data-action'		=> 'save'
			
		));
		
		$this -> settings -> load();

		foreach($this -> settings -> getAll() -> toArray() as $s)
		{
			
			switch($s -> type){
				
				case 'text':
				case 'url':					
				case 'email':
				
					$form -> addField($s -> type, $s -> key, $s -> description, array(
					
						'id'	=> "_".$s -> key,
						'value'	=> $s -> value,
						'required' => 'required'
					
					));				
				
					break;

			}
			
		}
		
		return $form;		
		
	}
	
	protected function getOptionList($entityName, $selected = array())
	{
		
		$options = array();
		$tempSel = false;
		
		foreach(MWEntity::createRepository($entityName) -> findAll() -> toArray() as $e)
		{
			
			foreach($selected as $sel)
			{
				
				if(!$sel -> equals($e)) continue;
				$tempSel = true;
				
			}
			
			$options[] = array("value" => $e -> id, "label" => $e -> __toString(), "selected" => $tempSel);		
			$tempSel = false;
			
		}
		
		return $options;
		
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
			
			$note -> isRoleGranted($roleName) === true && $entries['entities']['entries'][ $note -> pathName] = $note;
			
		}
		
		$userNote = $this -> inspector -> getSingleAnnotationForEntity(
			MW_LOGIN_ENTITY,
			'Backstage\Annotation\EntitySetup'
		);
		
		$userNote -> isRoleGranted($roleName) === true && array_push($entries['common']['entries'], $userNote);
		
		return $entries;	
		
	}
	
	protected function getEntityInfo($entity)
	{

		$info = array();

		foreach($this -> inspector -> getAnnotationsForEntity($entity) as $field)
		{

			$singleField = new \stdClass();
			
			if(count($field['annotations']['Backstage\Annotation\BackstageField']) == 0)
				continue;
			
			foreach($field['annotations'] as $note)
			{
				
				foreach(get_object_vars($note[0]) as $key => $value)
				{
					
					$singleField -> $key = $value;
					
				}
				
			}
			
			$singleField -> name = $field['name'];
			
			$info[] = $singleField;
			
		}
		
		return $info;
		
	}
	
}