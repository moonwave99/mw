<?php
	
namespace MWCore\Kernel;

use MWCore\Kernel\MWSingleRoute;
use MWCore\Kernel\MWFirewall;
use MWCore\Kernel\MWFirewallRule;
use MWCore\Kernel\MWProvider;

class MWRouter
{
	
	protected $routes;
	
	protected $firewall;
	
	public function __construct(&$firewall)
	{	
	
		$this -> routes = array();
		$this -> firewall = $firewall;
		
	}	
	
	public function setRoutes($routes){ 
	
		foreach($routes as $r)
		{
			
			$this -> routes[$r -> getPatternLength()][] = $r;
			
		}
		
	}
	
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
		$controller = MWProvider::makeController($route -> controller);

		if($controller === false || !method_exists($controller, $route -> action."Action"))
			$this -> requestNotFound();

		$params = MWSingleRoute::tiles($pattern);
		
		while(count($params) > $route -> getParamCount() )
		{
			array_shift($params);
		}
		
		call_user_func_array(
			array(
				$controller,
				$route -> action."Action"
			),
			$this -> cleanParams($params)
		);
		
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