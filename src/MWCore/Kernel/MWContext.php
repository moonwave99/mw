<?php

namespace MWCore\Kernel;

class MWContext
{

	protected $user;
	
	protected $session;	
	
	public function __construct(&$session)
	{	
		
		$this -> session = $session;
		
		$this -> user = $this -> session -> get('user');
		
	}
	
	public function getUser(){ return $this -> user; }
	
	public function isUserLogged()
	{
		
		return $this -> session -> get('logged') === true;
		
	}
	
	public function isRoleGranted($role)
	{
		
		return	$this -> user != NULL && $this -> user -> hasRole($role);
		
	}
	
}