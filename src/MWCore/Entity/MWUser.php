<?php

namespace MWCore\Entity;

use MWCore\Entity\MWEntity;
use MWCore\Kernel\MWDBManager;
use MWCore\Component\MWCollection;	
	
/** 
*	@MWCore\Annotation\Table("user")
*	@MWCore\Annotation\Repository("\MWCore\Repository\MWUserRepository")
*/	
class MWUser extends MWEntity
{
	
	/**
	*	@MWCore\Annotation\Field(name="username", type="string", length="10")
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
	*/	
	protected $email;
	
	/**
	*	@MWCore\Annotation\Field(name="enabled", type="int", length="1", default="0")
	*/	
	protected $enabled;
	
	/**
	*	@MWCore\Annotation\Field(name="createdAt", type="datetime")
	*/	
	protected $createdAt;	
	
	/**
	*	@MWCore\Annotation\ManyToMany(entity="\MWCore\Entity\MWRole", jointable="role_to_user")
	*/	
	protected $roleList;
	
	public function __construct($id = NULL)
	{
		
		parent::__construct($id);
		
		$this -> createdAt = new \DateTime();
		
		$this -> roleList = new MWCollection();
		
	}
	
	public function hasRole($roleName)
	{

		foreach($this -> roleList -> toArray() as $role)
		{

			if($role -> name == $roleName)
				return true;
			
		}
		
		return false;
		
	}
	
	public function setPassword($password)
	{
		
		$this -> salt = generateSalt(6);
		$this -> password = encodePassword($password, $this -> salt);
		
	}

}