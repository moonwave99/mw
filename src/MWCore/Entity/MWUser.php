<?php

namespace MWCore\Entity;

use MWCore\Entity\MWEntity;
use MWCore\Kernel\MWDBManager;
use MWCore\Component\MWCollection;	
	
/** 
*	@MWCore\Annotation\Table("user")
*	@MWCore\Annotation\Repository("\MWCore\Repository\MWUserRepository")
*	@Backstage\Annotation\EntitySetup(label="Users", pathName="user", granted="ROLE_ADMIN", icon="user")
*/	
class MWUser extends MWEntity
{
	
	/**
	*	@MWCore\Annotation\Field(name="username", type="string", length="10")
	*	@Backstage\Annotation\BackstageField(label="Username")
	*/	
	protected $username;
	
	/**
	*	@MWCore\Annotation\Field(name="password", type="string", length="64")
	*/	
	protected $password;
	
	/**
	*	@MWCore\Annotation\Field(name="salt", type="string", length="6")
	*/	
	protected $salt;
	
	/**
	*	@MWCore\Annotation\Field(name="email", type="string", length="64", default="")
	*	@Backstage\Annotation\BackstageField(label="E-mail", inputMode="email")	
	*/	
	protected $email;
	
	/**
	*	@MWCore\Annotation\Field(name="enabled", type="int", length="1", default="0")
	*	@Backstage\Annotation\BackstageField(label="Enabled", inputMode="radio-boolean")	
	*/	
	protected $enabled;
	
	/**
	*	@MWCore\Annotation\Field(name="createdAt", type="datetime")
	*	@Backstage\Annotation\BackstageField(label="Created On", inputMode="date", target="table")	
	*/	
	protected $createdAt;	
	
	/**
	*	@MWCore\Annotation\Field(name="role", type="int", length="3", default="1")
	*/	
	protected $role;
	
	public function __construct($id = NULL)
	{
		
		parent::__construct($id);
		
		$this -> createdAt = new \DateTime();
		
	}
	
	public function hasRole($role)
	{

		return $this -> role % $role == 0;
		
	}
	
	public function setPassword($password)
	{
		
		$this -> salt = generateSalt(6);
		$this -> password = encodePassword($password, $this -> salt);
		
	}

}