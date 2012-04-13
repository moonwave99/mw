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

use MWCore\Kernel\MWRouter;

/**
*	MWSingleRoute Class - holds info for a specific route to be matched agains URI request.
*/
class MWSingleRoute
{

	/**#@+
	*	@access protected	
	*	@var string
	*/
	protected $pattern;
	protected $controllerName;
	protected $actionName;

	/**
	*	Default constructor.
	*	@access public
	*	@param string $pattern Route pattern
	*	@param string $controllerName Associated controller name
	*	@param string $actionName Associated action name
	*/
	public function __construct($pattern, $controllerName, $actionName)
	{
		
		$this -> pattern = $pattern;
		$this -> controllerName = $controllerName;
		$this -> actionName = $actionName;
		
	}

	/**
	*	Magic getter
	*	@access public
	*	@param string $property Property name
	*	@return mixed
	*/	
	public function __get($property)
	{

		return $this -> $property;
		
	}

	/**
	*	Splits own pattern in slash-separated tiles
	*	@access public
	*	@return array
	*/	
	public function getTiles()
	{
		
		return self::tiles($this -> pattern);
		
	}
	
	/**
	*	Splits given pattern in slash-separated tiles
	*	@access public
	*	@param string $pattern Pattern
	*	@return array
	*/	
	static function tiles($pattern)
	{

		return preg_split('@/@', $pattern, NULL, PREG_SPLIT_NO_EMPTY);

	}
	
	/**
	*	Returns the number of variable params [ex. /{id} ]in own pattern
	*	@access public
	*	@return int
	*/	
	public function getParamCount()
	{
		
		return substr_count($this -> pattern, "{");
		
	}

	/**
	*	Returns the length [measured in tiles number] of own pattern
	*	@access public
	*	@return int
	*/	
	public function getPatternLength()
	{

		return self::patternLength($this -> pattern);
		
	}
	
	/**
	*	Returns the length [measured in tiles number] of given pattern
	*	@access public
	*	@param string $pattern Pattern
	*	@return int
	*/	
	static function patternLength($pattern)
	{
	
		return strpos($pattern, '*') !== false ? '*' : count(self::tiles($pattern));
		
	}
	
	/**
	*	Checks if given pattern matches own one
	*	@access public
	*	@param string $pattern Pattern to try matching against
	*	@return boolean
	*/	
	public function isPatternMatching($pattern)
	{	
		
		$tiles = self::tiles($pattern);
		$ownTiles = $this -> getTiles();
		
		if(count($tiles) < count($ownTiles))
			return false;
		
		foreach($tiles as $i => $t)
		{
			
			if($ownTiles[$i] == '*')
				return true;			
			
			if($t !== $ownTiles[$i] && strpos($ownTiles[$i], "{") === false){
				return false;
			}
			
		}

		return true;

	}	

}