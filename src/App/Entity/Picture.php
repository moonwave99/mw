<?php

namespace App\Entity;

use MWCore\Entity\MWEntity;
use MWCore\Kernel\MWDBManager;
use MWCore\Component\MWCollection;	
	
/** 
*	@MWCore\Annotation\Table("picture")
*	@MWCore\Annotation\Repository("\App\Repository\PictureRepository")
*	@Backstage\Annotation\EntitySetup(label="Pictures", pathName="picture", granted="ROLE_ADMIN, ROLE_USER", icon="picture", viewMode='gallery')
*/	
class Picture extends MWEntity
{
	
	/**
	*	@MWCore\Annotation\Field(name="src", type="string", length="64", default="")
	*	@Backstage\Annotation\BackstageField(label="Picture", inputMode="picture", target="both")	
	*/	
	protected $src;
	
	/**
	*	@MWCore\Annotation\Field(name="label", type="string", length="16")
	*	@Backstage\Annotation\BackstageField(label="Name")
	*/	
	protected $label;
	
	/**
	*	@MWCore\Annotation\Field(name="description", type="text", default="")
	*	@Backstage\Annotation\BackstageField(label="Description", inputMode="textarea", target="both")
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