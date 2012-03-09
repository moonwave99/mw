<?php

namespace MWCore\Entity;

use MWCore\Entity\MWEntity;
use MWCore\Kernel\MWDBManager;	
	
/** 
*	@MWCore\Annotation\Table("mw_settings")
*	@MWCore\Annotation\Repository("\MWCore\Repository\MWSettingRepository")
*/	
class MWSetting extends MWEntity
{
	
	/**
	*	@MWCore\Annotation\Field(name="key", type="string", length="16")
	*/	
	protected $key;
	
	/**
	*	@MWCore\Annotation\Field(name="value", type="string", length="64")
	*/	
	protected $value;
	
	/**
	*	@MWCore\Annotation\Field(name="type", type="string", length="8")
	*/	
	protected $type;
	
	/**
	*	@MWCore\Annotation\Field(name="description", type="string", length="32")
	*/	
	protected $description;	
	
	public function __construct($id = NULL)
	{
		
		parent::__construct($id);
		
	}

}