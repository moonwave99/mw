<?php
	
namespace MWCore\Kernel;

use MWCore\Kernel\MWSingleRoute;

class MWFirewall
{

	protected $rules;
	
	protected $context;
	
	public function __construct(&$context)
	{	
		
		$this -> rules = array();
		
		$this -> context = $context;
		
	}
	
	public function setRules($rules)
	{
		
		foreach($rules as $r){
			
			$this -> rules[] = $r;
			
		}
		
	}
	
	public function isPatternRejected($pattern)
	{

		$currentTiles = MWSingleRoute::tiles($pattern);
		
		foreach($this -> rules as $rule)
		{
			
			$tiles = MWSingleRoute::tiles( $rule -> getPattern() );
			
			if($tiles[0] == $currentTiles[0])
			{

				return $this -> context -> isRoleGranted( $rule -> getRole() ) || 
					($rule -> isFlashEnabled() && strpos($_SERVER['HTTP_USER_AGENT'], "Adobe Flash Player") !== false )
					? false : $rule -> getFallbackPattern();
				
			}
			
		}
		
		return false;
		
	}
	
}