<?php

namespace App\Entity;

use MWCore\Entity\MWEntity;
use MWCore\Kernel\MWDBManager;
use MWCore\Component\MWCollection;	
	
/** 
*	@MWCore\Annotation\Table("category")
*	@MWCore\Annotation\Repository("\App\Repository\CategoryRepository")
*	@Backstage\Annotation\EntitySetup(label="Categories", pathName="category", granted="ROLE_ADMIN, ROLE_USER", icon="folder-open")
*/	
class Category extends MWEntity
{

	/**
	*	@MWCore\Annotation\Field(name="label", type="string", length="16")
	*	@Backstage\Annotation\BackstageField(label="Name", colSize="20")
	*/	
	protected $label;
	
	/**
	*	@MWCore\Annotation\Field(name="description", type="text")
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