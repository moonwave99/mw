<?php

namespace App\Entity;

use MWCore\Entity\MWEntity;
use MWCore\Kernel\MWDBManager;
use MWCore\Component\MWCollection;	
	
/** 
*	@MWCore\Annotation\Table("tag")
*	@MWCore\Annotation\Repository("\App\Repository\TagRepository")
*	@Backstage\Annotation\EntitySetup(label="Tags", pathName="tag", granted="ROLE_ADMIN,'ROLE_USER", icon="tags")
*/	
class Tag extends MWEntity
{
	
	/**
	*	@MWCore\Annotation\Field(name="label", type="string", length="16")
	*	@Backstage\Annotation\BackstageField(label="Name")
	*/	
	protected $label;
	
	/**
	*	@MWCore\Annotation\Field(name="description", type="text", default="")
	*	@Backstage\Annotation\BackstageField(label="Description", inputMode="textarea", target="form")
	*/	
	protected $description;	
	
	public function __construct($id = NULL)
	{
		
		parent::__construct($id);
		
	}
	
	public function __toString()
	{
		
		return $this -> label;
		
	}

}