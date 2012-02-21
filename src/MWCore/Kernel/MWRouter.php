<?php
	
namespace MWCore\Kernel;

use MWCore\Kernel\MWSingleRoute;
use MWCore\Kernel\MWFirewall;
use MWCore\Kernel\MWFirewallRule;

class MWRouter
{
	
	protected $routes;
	
	protected $session;
	
	protected $context;
	
	protected $firewall;
	
	public function __construct(&$session, &$context, &$firewall)
	{	
	
		$this -> routes = array();
		
		$this -> session = $session;
		
		$this -> context = $context;
		
		$this -> firewall = $firewall;
		
	}	
	
	public function setRoutes($routes){ 
	
		foreach($routes as $r){
			
			$this -> routes[$r->getPatternLength()][] = $r;
			
		}
		
	}
	
	public function routeRequest()
	{

		$pattern = $this -> getPatternFromURI();
		
		$firewallProblem = $this -> firewall -> isPatternRejected($pattern);

		echo ($firewallProblem);

		if($firewallProblem !== false){

			header("Location: ".BASE_PATH.$firewallProblem);
			exit;
			
		}			

		$route = $this -> searchPattern($pattern);	
		
		$route === false && $this -> requestNotFound();

		if(class_exists($route -> controller)){

			$controllerName = $route -> controller;

			$controllerInstance = new $controllerName($this -> session, $this -> context);
			
			if(method_exists($controllerInstance, $route -> action."Action")){

				$params = MWSingleRoute::tiles($pattern);
				
				while(count($params) > $route -> getParamCount() )
				{
					array_shift($params);
				}
				
				call_user_func_array(
					array(
						$controllerInstance,
						$route -> action."Action"
					),
					$this -> cleanParams($params)
				);
				
			}else{
			
				$this -> requestNotFound();
				
			}
			
		}else{
			
			$this -> requestNotFound();
			
		}		
		
	}	
	
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
	
	protected function getPatternFromURI()
	{

		$rewrite_rule = explode(' ', REWRITE_RULE);
		
		$xpl = str_replace($rewrite_rule[1], '', $_SERVER['SCRIPT_NAME']);
		
		$route = explode('?', str_replace(
			$xpl == "/" ? '' : $xpl,
			'',
			$_SERVER['REQUEST_URI']
		));

		return $route[0];
		
	}
	
	protected function cleanParams($params)
	{
		
		$p = array();
		
		foreach($params as $param)
		{
			
			$p[] = htmlentities($param, ENT_QUOTES, 'UTF-8');
			
		}
		
		return $p;
				
	}	

	static function requestNotFound()
	{

		header("HTTP/1.0 404 Not Found");
		
		\MWCore\Kernel\MWView::reqView('MWCore\View\Error\404');
		
		exit;
		
	}

}