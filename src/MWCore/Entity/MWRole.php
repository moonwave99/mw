<?php

namespace MWCore\Entity;

use MWCore\Entity\MWEntity;
use MWCore\Kernel\MWDBManager;	

/** 
*	@MWCore\Annotation\Table("role")
*	@MWCore\Annotation\Repository("\MWCore\Repository\RoleRepository")
*/	
class MWRole extends MWEntity
{

	/**
	*	@MWCore\Annotation\Field(name="name", type="string", length="32")
	*/	
	protected $name;
	
	/**
	*	@MWCore\Annotation\Field(name="label", type="string", length="32")
	*/	
	protected $label;
	
	public function __construct($id = NULL)
	{
		
		parent::__construct($id);

	}

}