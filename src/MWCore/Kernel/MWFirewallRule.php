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
*	MWFirewallRule Class - holds info for a single Firewall rule, based on the given pattern and role the user has to be granted.
*/
class MWFirewallRule
{
	
	/**
	*	@access protected
	*	@var string
	*/
	protected $pattern;
	
	/**
	*	@access protected
	*	@var mixed
	*/
	protected $role;
	
	/**
	*	@access protected
	*	@var string
	*/	
	protected $fallbackPattern;
	
	/**
	*	@access protected
	*	@var boolean
	*/	
	protected $flashEnabled;
	
	/**
	*	Default constructor
	*	@access public
	*	@param string $pattern Rule pattern
	*	@param mixed $role Role needed
	*	@param string $fallbackPattern The path to be routed to in case role is not granted
	*	@param boolean $flashEnabled Hack for enabling Flash aagents
	*/
	public function __construct($pattern, $role, $fallbackPattern, $flashEnabled = false)
	{
		
		$this -> setPattern($pattern);
		$this -> setRole($role);
		$this -> setFallbackPattern($fallbackPattern);
		$this -> flashEnabled = $flashEnabled;
		
	}
	
	/**
	*	Pattern setter
	*	@access public
	*	@param string $pattern Pattern being set
	*/
	public function setPattern($pattern){ $this -> pattern = $pattern; }

	/**
	*	Pattern getter
	*	@access public
	*	@return string
	*/
	public function getPattern(){ return $this -> pattern; }
	
	/**
	*	Role setter
	*	@access public
	*	@param mixed $role Role being set
	*/
	public function setRole($role){ $this -> role = $role; }
	
	/**
	*	Role getter
	*	@access public
	*	@return mixed
	*/	
	public function getRole(){ return $this -> role; }
	
	/**
	*	Fallback pattern setter
	*	@access public
	*	@param string $fallbackPattern The fallback pattern being set
	*/	
	public function setFallbackPattern($fallbackPattern){ $this -> fallbackPattern = $fallbackPattern; }
	
	/**
	*	Fallback pattern getter
	*	@access public
	*	@return string
	*/	
	public function getFallbackPattern(){ return $this -> fallbackPattern; }
	
	/**
	*	Returns if Flash support is enabled
	*	@access public
	*	@return boolean
	*/	
	public function isFlashEnabled(){ return $this -> flashEnabled; }
	
}