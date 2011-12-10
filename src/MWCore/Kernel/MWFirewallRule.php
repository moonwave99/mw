<?php
	
namespace MWCore\Kernel;

class MWFirewallRule
{
	
	protected $pattern;
	
	protected $role;
	
	protected $fallbackPattern;
	
	public function __construct($pattern, $role, $fallbackPattern)
	{
		
		$this -> setPattern($pattern);
		$this -> setRole($role);
		$this -> setFallbackPattern($fallbackPattern);
		
	}
	
	public function setPattern($pattern){ $this -> pattern = $pattern; }
	public function getPattern(){ return $this -> pattern; }
	
	public function setRole($role){ $this -> role = $role; }
	public function getRole(){ return $this -> role; }
	
	public function setFallbackPattern($fallbackPattern){ $this -> fallbackPattern = $fallbackPattern; }
	public function getFallbackPattern(){ return $this -> fallbackPattern; }
	
}