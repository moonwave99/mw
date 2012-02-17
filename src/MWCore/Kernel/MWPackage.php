<?php

namespace MWCore\Kernel;

use MWCore\Kernel\MWSingleRoute;
use MWCore\Kernel\MWFirewallRule;

class MWPackage
{

	protected $name;
	protected $signature;

	protected $constants;
	protected $routes;
	protected $rules;
	
	public function __construct($name, $signature)
	{	
		
		$this -> name = $name;

		$this -> signature = signature;
		
		$this -> constants = array();
		$this -> routes = array();
		$this -> rules = array();
		
	}
	
	public function getName(){ return $this -> name; }
	
	public function getSignature(){ return $this -> signature; }

	public function getPath(){ return SRC_PATH.$this -> name."/"; }
	
	public function addConstant($key, $value)
	{
		
		$this -> constants[$key] = $value;
		
	}
	
	public function addConstants($constants)
	{
		
		foreach($constants as $key => $value)
		{
			
			$this -> addConstant($key, $value);
			
		}
		
	}
	
	public function getConstants(){ return $this -> constants; }
	
	public function addRoute(MWSingleRoute $route)
	{
		
		$this -> routes[] = $route;
		
	}
	
	public function addRoutes($routes)
	{
		
		foreach($routes as $r)
		{
			
			$this -> addRoute($r);
			
		}
		
	}
	
	public function getRoutes(){ return $this -> routes; }	
	
	public function addRule(MWFirewallRule $rule)
	{
	
		$this -> rules[] = $rule;
		
	}
	
	public function addRules($rules)
	{
		
		foreach($rules as $r)
		{
			
			$this -> addRule($r);
			
		}
		
	}	
	
	public function getRules(){ return $this -> rules; }		
	
	public function loadFixtures()
	{
		
		$fixturePath = $this -> getPath() . "Resources/fixtures.php";
		
		if(!file_exists( $fixturePath )) throw new \MWCore\Exception\MWFixtureException($this -> name);
		
		include $fixturePath;
		
	}
		
}