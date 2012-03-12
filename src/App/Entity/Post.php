<?php

namespace App\Entity;

use MWCore\Entity\MWEntity;
use MWCore\Kernel\MWDBManager;
use MWCore\Component\MWCollection;	
	
/** 
*	@MWCore\Annotation\Table("post")
*	@MWCore\Annotation\Repository("\App\Repository\PostRepository")
*	@Backstage\Annotation\EntitySetup(label="Posts", pathName="post", granted="ROLE_ADMIN, ROLE_USER", icon="pencil")
*/	
class Post extends MWEntity
{

	/**
	*	@MWCore\Annotation\Field(name="title", type="string", length=32)
	*	@Backstage\Annotation\BackstageField(label="Title")
	*/	
	protected $title;
	
	/**
	*	@MWCore\Annotation\Field(name="body", type="text")
	*	@Backstage\Annotation\BackstageField(label="Body", inputMode="textarea", target="form", rich="true")
	*/	
	protected $body;		
	
	/**
	*	@MWCore\Annotation\Field(name="createdAt", type="datetime")
	*	@Backstage\Annotation\BackstageField(label="Created On", inputMode="date", target="table")	
	*/	
	protected $createdAt;	
	
	/**
	*	@MWCore\Annotation\ManyToMany(entity="App\Entity\Tag", jointable="tag_to_post", allownull=true)
	*	@Backstage\Annotation\BackstageField(label="Tags", inputMode="select-multiple", target="both")
	*/	
	protected $tagList;		
	
	/**
	*	@MWCore\Annotation\ManyToOne(entity="App\Entity\Category")
	*	@Backstage\Annotation\BackstageField(label="Category", inputMode="select", target="both")
	*/	
	protected $category;
	
	public function __construct($id = NULL)
	{
		
		parent::__construct($id);
		
		$this -> createdAt = new \DateTime();
		$this -> tagList = new MWCollection();
		$this -> category = new \App\Entity\Category;
		
	}
	
	public function __toString()
	{
		
		return $this -> title;
		
	}

}