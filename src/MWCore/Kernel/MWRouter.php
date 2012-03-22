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
use MWCore\Kernel\MWFirewall;
use MWCore\Kernel\MWFirewallRule;
use MWCore\Kernel\MWProvider;

/**
*	MWRouter Class - routes URI according to app routes collection, and fires a 404 eventually.
*/
class MWRouter
{
	
	/**
	*	@access protected
	*	@var array
	*/
	protected $routes;
	
	/**
	*	@access protected
	*	@var MWFirewall
	*/
	protected $firewall;
	
	/**
	*	Default constructor.
	*	@access public	
	*	@param MWFirewall $firewall MWFirewall instance injected
	*/
	public function __construct(&$firewall)
	{	
	
		$this -> routes = array();
		$this -> firewall = $firewall;
		
	}	
	
	/**
	*	Sets routes, dividing them by their pattern length
	*	@access public
	*	@param array $routes The routes being set
	*/
	public function setRoutes($routes){ 
	
		foreach($routes as $r)
		{
			
			$this -> routes[$r -> getPatternLength()][] = $r;
			
		}
		
	}
	
	/**
	*	Core feature of the whole framework, routes URI pattern agains own routes collection
	*	@access public
	*/
	public function routeRequest()
	{

		$pattern = $this -> getPatternFromURI();
		
		$firewallProblem = $this -> firewall -> isPatternRejected($pattern);

		if($firewallProblem !== false){

			header("Location: ".BASE_PATH.$firewallProblem);
			exit;
			
		}			

		$route = $this -> searchPattern($pattern);	
		$route === false && $this -> requestNotFound();
		$controller = MWProvider::makeController($route -> controllerName);

		if($controller === false || !method_exists($controller, $route -> actionName."Action"))
			$this -> requestNotFound();

		$params = MWSingleRoute::tiles($pattern);
		
		while(count($params) > $route -> getParamCount() )
		{
			array_shift($params);
		}
		
		call_user_func_array(
			array(
				$controller,
				$route -> actionName."Action"
			),
			$this -> cleanParams($params)
		);
		
	}	
	
	/**
	*	Provides a 404 page with proper http header
	*/
	static function requestNotFound()
	{

		header("HTTP/1.0 404 Not Found");
		
		\MWCore\Kernel\MWView::reqView('MWCore\View\Error\404');
		
		exit;
		
	}	
	
	/**
	*	Searches own collection for given pattern
	*	@access protected
	*	@param string $pattern The pattern being looked for
	*	@return mixed Matching MWSingleRoute if found, or false
	*/
	protected function searchPattern($pattern)
	{

		foreach($this -> routes[MWSingleRoute::patternLength($pattern)] as $r)
		{

			if($r -> isPatternMatching($pattern))
			{
				return $r;
			}
			
		}
		
		return false;
		
	}
	
	/**
	*	Returns pattern from URI
	*	@return string
	*/
	protected function getPatternFromURI()
	{

		$xpl = str_replace(array_pop(explode(' ', REWRITE_RULE)), '', $_SERVER['SCRIPT_NAME']);

		$route = explode('?', str_replace(
			$xpl == "/" ? '' : $xpl,
			'',
			$_SERVER['REQUEST_URI']
		));

		return strpos($_SERVER['REQUEST_URI'], 'index.php') !== false ? 
			array_shift(explode('&', array_pop($route))) :
			array_shift($route);
		
	}
	
	/**
	*	Cleans URI parameters
	*	@return array
	*/
	protected function cleanParams($params)
	{
		
		foreach($params as &$param)
		{
			
			$param = htmlentities($param, ENT_QUOTES, 'UTF-8');
			
		}
		
		return $params;
				
	}	

}