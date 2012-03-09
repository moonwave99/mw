<?php

namespace MWCore\Kernel;

class MWContext
{

	protected $session;	
	
	public function __construct(&$session)
	{	
		
		$this -> session = $session;
		
	}

	public function getUser(){ return $this -> session -> get('user'); }
	
	public function isUserLogged()
	{
		
		return $this -> session -> get('logged') === true;
		
	}
	
	public function isRoleGranted($role)
	{
		
		return	$this -> session -> get('user') != NULL && $this -> session -> get('user') -> hasRole($role);
		
	}
	
}