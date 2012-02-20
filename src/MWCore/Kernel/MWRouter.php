<?php
	
namespace MWCore\Kernel;

use MWCore\Interfaces\MWSingleton;
use MWCore\Kernel\MWSingleRoute;
use MWCore\Kernel\MWFirewall;
use MWCore\Kernel\MWFirewallRule;

class MWRouter implements MWSingleton
{
	
	private static $instance = null;	
	
	protected $routes;	
	
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
		$this -> routes = array();
	}	
	
	public function setRoutes($routes){ 
	
		foreach($routes as $r){
			
			$this -> routes[$r->getPatternLength()][] = $r;
			
		}
		
	}
	
	public function routeRequest()
	{

		$pattern = $this -> getPatternFromURI();	

		$firewallProblem = MWFirewall::getInstance() -> isPatternRejected($pattern);
		
		if($firewallProblem !== false){

			header("Location: ".BASE_PATH.$firewallProblem);
			exit;
			
		}			

		$route = $this -> searchPattern($pattern);		
		
		if(false === $route || false === $route -> follow($pattern)){
			
			MWRouter::requestNotFound();
			
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

	static function requestNotFound()
	{

		header("HTTP/1.0 404 Not Found");

		include(SRC_PATH."MWCore/View/404.php");
		
		exit;
		
	}

}