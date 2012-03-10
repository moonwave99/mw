<?php
	
namespace Backstage\Component;

use MWCore\Entity\MWEntity;
use Backstage\Component\Form\BackstageForm;

class BackstageHelper
{
	
	public $inspector;
	
	public function __construct($inspector)
	{
		
		$this -> inspector = $inspector;

	}	
	
	public function getEntityInfo($entity)
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
	
	public function getNavigationEntries($roleName)
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

	public function createNewEntityForm($entityname, $entitylabel)
	{
		
		$info = $this -> getEntityInfo($entityname);

		$form = new BackstageForm(array(
			
			'action'			=> sprintf(BASE_PATH."backstage/%s/save", strtolower($entitylabel)),
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

					$form -> addTextarea('Enter some text here.', $f -> name, $f -> label, array(
					
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
	
	public function createEditSettingsForm($settings)
	{

		$form = new BackstageForm(array(
			
			'action'			=> sprintf(BASE_PATH."backstage/settings/save"),
			'method'			=> 'post',
			'data-controller'	=> 'settings',
			'data-action'		=> 'save'
			
		));
		
		$settings -> load();

		foreach($settings -> getAll() -> toArray() as $s)
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
	
	public function getOptionList($entityName, $selected = array())
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
		
}