<?php
	
namespace MWCore\Kernel;

use MWCore\Interfaces\MWSingleton;
use MWCore\Kernel\MWSingleRoute;
use MWCore\Kernel\MWContext;

class MWFirewall implements MWSingleton
{
	
	private static $instance = null;	

	protected $rules;
	
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
		$rules = array();
	}
	
	public function setRules($rules){ $this -> rules = $rules; }
	
	public function isPatternRejected($pattern)
	{

		$currentTiles = MWSingleRoute::tiles($pattern);

		foreach($this -> rules as $rule)
		{

			$tiles = MWSingleRoute::tiles( $rule -> getPattern() );
			
			if($tiles[0] == $currentTiles[0])
			{

				return MWContext::getInstance() -> isRoleGranted( $rule -> getRole() ) || 
					($rule -> isFlashEnabled() && strpos($_SERVER['HTTP_USER_AGENT'], "Adobe Flash Player") !== false )
					? false : $rule -> getFallbackPattern();
				
			}
			
		}
		
		return false;
		
	}
	
}