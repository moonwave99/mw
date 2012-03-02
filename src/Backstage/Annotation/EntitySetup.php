<?php

namespace Backstage\Annotation;
	
class EntitySetup extends \Annotation
{

	public $granted;
	
	public $label;
	
	public $pathName;
	
	public function isRoleGranted($roleName)
	{
		
		$roles = explode(",", $this -> granted);
		
		foreach($roles as $r)
		{

			if(constant(trim($r)) % $roleName == 0)
				return true;
			
		}
		
		return false;
		
	}
	
}