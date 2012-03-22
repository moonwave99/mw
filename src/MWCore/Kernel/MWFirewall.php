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
*	MWFirewall Class - filters any request against the set of provided rules
*/
use MWCore\Kernel\MWSingleRoute;

class MWFirewall
{

	/**
	*	@access protected
	*	@var array
	*/
	protected $rules;
	
	/**
	*	@access protected
	*	@var MWContext
	*/
	protected $context;
	
	/**
	*	Default constructor.
	*	@param MWContext $context MWContext instance injected
	*/
	public function __construct(&$context)
	{	
		
		$this -> rules = array();
		
		$this -> context = $context;
		
	}
	
	/**
	*	Rules setter
	*	@param array $rules The rules being set
	*/
	public function setRules($rules)
	{
		
		foreach($rules as $r){
			
			$this -> rules[] = $r;
			
		}
		
	}
	
	/**
	*	Checks if the user has permission to follow given pattern
	*	@param string $pattern The pattern being checked
	*	@return boolean
	*/
	public function isPatternRejected($pattern)
	{

		$currentTiles = MWSingleRoute::tiles($pattern);
		
		$check = NULL;
		
		foreach($this -> rules as $rule)
		{

			$ruleTiles = MWSingleRoute::tiles( $rule -> getPattern() );

			if(count($ruleTiles) > count($currentTiles))
				continue;		
				
			$check = true;
				
			foreach($ruleTiles as $i => $tile )
			{
				
				if($tile != $currentTiles[$i]){
					
					$check = false;
					break;
					
				}
				
			}
			
			if($check === true)
			{

				return $this -> context -> isRoleGranted( $rule -> getRole() ) || 
					($rule -> isFlashEnabled() && strpos($_SERVER['HTTP_USER_AGENT'], "Adobe Flash Player") !== false )
					? false : $rule -> getFallbackPattern();
				
			}
			
		}
		
		return false;
		
	}
	
}