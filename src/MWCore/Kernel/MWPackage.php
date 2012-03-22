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

use MWCore\Kernel\MWSingleRoute;
use MWCore\Kernel\MWFirewallRule;

/**
*	MWPackage Class - offers package representation for MW bundles.
*/
class MWPackage
{

	/**#@+
	*	@access protected
	*	@var string
	*/
	protected $name;
	protected $signature;

	/**#@+
	*	@access protected	
	*	@var array
	*/
	protected $constants;
	protected $routes;
	protected $rules;
	
	/**
	*	Default constructor.
	*	@access public
	*	@param string $name Package name
	*	@param string $signature Package short signature
	*/
	public function __construct($name, $signature)
	{	
		
		$this -> name = $name;

		$this -> signature = signature;
		
		$this -> constants = array();
		$this -> routes = array();
		$this -> rules = array();
		
	}
	
	/**
	*	Name getter
	*	@access public
	*	@return string
	*/
	public function getName(){ return $this -> name; }
	
	/**
	*	Signature getter
	*	@access public
	*	@return string
	*/	
	public function getSignature(){ return $this -> signature; }

	/**
	*	Returns Package src path
	*	@access public
	*	@return string
	*/
	public function getPath(){ return SRC_PATH.$this -> name."/"; }
	
	/**
	*	Adds a constant to package
	*	@access public
	*	@param string $key Constant key
	*	@param string $value Constant value
	*/	
	public function addConstant($key, $value)
	{
		
		$this -> constants[$key] = $value;
		
	}

	/**
	*	Adds many constants to package
	*	@access public
	*	@param array $constants An array of key-value pairs
	*/	
	public function addConstants($constants)
	{
		
		foreach($constants as $key => $value)
		{
			
			$this -> addConstant($key, $value);
			
		}
		
	}
	
	/**
	*	Constants getter
	*	@access public
	*	@return array
	*/	
	public function getConstants(){ return $this -> constants; }
	
	/**
	*	Adds a MWSingleRoute to package
	*	@access public
	*	@param MWSingleRoute $route The route being added
	*/	
	public function addRoute(MWSingleRoute $route)
	{
		
		$this -> routes[] = $route;
		
	}
	
	/**
	*	Adds many MWSingleRoute to package
	*	@access public
	*	@param array $routes An array of MWSingleRoute
	*/
	public function addRoutes($routes)
	{
		
		foreach($routes as $r)
		{
			
			$this -> addRoute($r);
			
		}
		
	}
	
	/**
	*	Routes getter
	*	@access public
	*	@return array
	*/	
	public function getRoutes(){ return $this -> routes; }	
	
	/**
	*	Adds a MWFirewallRule to package
	*	@access public
	*	@param MWFirewallRule $rule The rule being added
	*/	
	public function addRule(MWFirewallRule $rule)
	{
	
		$this -> rules[] = $rule;
		
	}
	
	/**
	*	Adds many MWFirewallRule to package
	*	@access public
	*	@param array $rules An array of MWFirewallRule
	*/	
	public function addRules($rules)
	{
		
		foreach($rules as $r)
		{
			
			$this -> addRule($r);
			
		}
		
	}	
	
	/**
	*	Rules getter
	*	@access public
	*	@return array
	*/	
	public function getRules(){ return $this -> rules; }		
	
	/**
	*	Loads fixtures for package
	*	@access public
	*/	
	public function loadFixtures()
	{
		
		$fixturePath = $this -> getPath() . "Resources/fixtures.php";
		
		if(!file_exists( $fixturePath )) throw new \MWCore\Exception\MWFixtureException($this -> name);
		
		include $fixturePath;
		
	}
		
}