<?php
	
namespace MWCore\Kernel;

use MWCore\Kernel\MWRouter;

class MWSingleRoute
{

	protected $pattern;
	
	protected $controller;
	
	protected $action;

	public function __construct($pattern, $controller, $action)
	{
		
		$this -> pattern = $pattern;
		$this -> controller = $controller;
		$this -> action = $action;
		
	}
	
	public function isPatternMatching($pattern)
	{	
		
		$tiles = MWSingleRoute::tiles($pattern);
		$ownTiles = $this -> getTiles();

		foreach($tiles as $i => $t)
		{
			
			if($t !== $ownTiles[$i] && strpos($ownTiles[$i], "{") === false){
				return false;
			}
			
		}

		return true;

	}
	
	public function getTiles()
	{
		
		return MWSingleRoute::tiles($this -> pattern);
		
	}
	
	static function tiles($pattern)
	{

		return preg_split('@/@', $pattern, NULL, PREG_SPLIT_NO_EMPTY);

	}
	
	public function getParamCount()
	{
		
		return substr_count($this -> pattern, "{");
		
	}
	
	public function getPatternLength()
	{

		return MWSingleRoute::patternLength($this -> pattern);
		
	}
	
	static function patternLength($pattern)
	{
	
		return count(MWSingleRoute::tiles($pattern));
		
	}
	
	public function follow($pattern)
	{

		if(class_exists($this -> controller)){

			$controllerInstance = new $this -> controller;
			
			if(method_exists($controllerInstance, $this->action."Action")){

				$params = MWSingleRoute::tiles($pattern);
				
				while(count($params) > $this -> getParamCount() )
				{
					array_shift($params);
				}

				call_user_func_array(
					array(
						$controllerInstance	,
						$this->action."Action"
					),
					$params
				);
				
			}else{
			
				return false;
				
			}
			
		}else{
			
			return false;
			
		}
	
	}

}