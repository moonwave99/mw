<?php

/**
*	Part of MW - lightweight MVC framework.
*	@author Diego Caponera <diego.caponera@gmail.com>
*	@link https://github.com/moonwave99/mw
*	@copyright Copyright 2011-2012 Diego Caponera
*	@license http://www.opensource.org/licenses/mit-license.php MIT License
*	@package MWCore/Kernel
*/

namespace MWCore\Kernel;

/**
*	MWContext Class - keeps status of the application and is asked for user information.
*/
class MWContext
{

	/**
	*	@access protected
	*	@var MWSession
	*/
	protected $session;	
	
	/**
	*	Default constructor.
	*	@param MWSession $session MWSession instance injected
	*/
	public function __construct(&$session)
	{	
		
		$this -> session = $session;
		
	}

	/**
	*	Returns current user
	*	@return mixed
	*/
	public function getUser(){ return $this -> session -> get('user'); }
	
	/**
	*	Checks if user is logged.
	*	@return boolean
	*/	
	public function isUserLogged()
	{
		
		return $this -> session -> get('logged') === true;
		
	}

	/**
	*	Checks if given role is granted to user
	*	@param mixed $role The role to check
	*	@return boolean
	*/	
	public function isRoleGranted($role)
	{
		
		return	$this -> session -> get('user') != NULL && $this -> session -> get('user') -> hasRole($role);
		
	}
	
}