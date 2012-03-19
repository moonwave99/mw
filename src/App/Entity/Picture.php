<?php

namespace App\Entity;

use MWCore\Entity\MWEntity;
use MWCore\Kernel\MWDBManager;
use MWCore\Component\MWCollection;	
	
/** 
*	@MWCore\Annotation\Table("picture")
*	@MWCore\Annotation\Repository("\App\Repository\PictureRepository")
*	@MWCore\Annotation\ReverseManyToMany(jointable="tag_to_picture")
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
	*	@MWCore\Annotation\Field(name="type", type="string", length="4", default="JPEG")
	*	@Backstage\Annotation\BackstageField(label="Type", target="table")
	*/
	protected $type;

	/**
	*	@MWCore\Annotation\Field(name="size", type="int", length=8, default=0)
	*	@Backstage\Annotation\BackstageField(label="Size (KB)", target="table")
	*/
	protected $size;
	
	/**
	*	@MWCore\Annotation\Field(name="width", type="int", length=4, default=0)
	*	@Backstage\Annotation\BackstageField(label="Width", target="table")
	*/
	protected $width;
	
	/**
	*	@MWCore\Annotation\Field(name="height", type="int", length=4, default=0)
	*	@Backstage\Annotation\BackstageField(label="Height", target="table")
	*/
	protected $height;	
	
	/**
	*	@MWCore\Annotation\ManyToMany(entity="App\Entity\Tag", jointable="tag_to_picture", allownull=true)
	*	@Backstage\Annotation\BackstageField(label="Tags", inputMode="select-multiple", target="both")
	*/	
	protected $tagList;	
	
	public function __construct($id = NULL)
	{
		
		parent::__construct($id);
		
		$this -> tagList = new \MWCore\Component\MWCollection();
		
	}
	
	public function __toString()
	{
		
		return $this -> label;
		
	}

}