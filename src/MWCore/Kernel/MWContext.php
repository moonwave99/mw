<?php

namespace MWCore\Kernel;

use MWCore\Interfaces\MWSingleton;
use MWCore\Kernel\MWSession;

class MWContext implements MWSingleton
{

	private static $instance = null;

	protected $user;
	
	public static function getInstance()
	{

		if(self::$instance == null)
		{   
			$c = __CLASS__;			
			self::$instance = new $c;
		}

		return self::$instance;
		
	}	
	
	private function __construct()
	{	
		
		$this -> user = MWSession::getInstance() -> get('user');
		
	}
	
	public function getUser(){ return $this -> user; }
	
	public function isUserLogged()
	{
		
		return MWSession::getInstance() -> get('logged') === true;
		
	}
	
	public function isRoleGranted($roleName)
	{

		return $this -> user != NULL ? $this -> user -> hasRole($roleName) : false;
		
	}
	
}