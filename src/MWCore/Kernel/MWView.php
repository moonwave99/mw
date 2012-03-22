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
*	MWView Class - the 'V' of MVC of course.
*/
class MWView
{

	/**
	*	The template file
	*	@access protected	
	*	@var string
	*/	
	protected $viewName;
	
	/**
	*	Holds data available in rendering environment
	*	@access protected	
	*	@var array
	*/	
	protected $data;
	
	/**
	*	Default constructor.
	*	@access public
	*	@param string $viewName View name in namespace flavour
	*	@param array $data Stuff accessible in render process
	*	@param MWSession $session MWSession instance injected
	*	@param MWSettingsManager $settings MWSettingsManager instance injected	
	*	@param MWContext $context MWContext instance injected	
	*/	
	public function __construct($viewName, $data, $session, $settings, $context)
	{	

		$this -> viewName = str_replace("\\", DIRECTORY_SEPARATOR, $viewName);

		if(!file_exists(SRC_PATH . $this -> viewName.".php")) throw new \MWCore\Exception\MWViewException($viewName);
		
		$this -> data = $data;

		$this -> token		= $session -> get('csrfToken');
		$this -> settings	= $settings;
		
		$context -> isUserLogged() && $this -> user = $session -> get('user');
		
		require(SRC_PATH."MWCore/Libraries/arshaw/ti.php");			
		
	}
	
	/**
	*	Magic setter
	*	@access public
	*	@param string $property Property name
	*	@param mixed $value Value being set
	*/	
	public function __set($property, $value)
	{
		
		$this -> data[$property] = $value;
		
	}	
	
	/**
	*	Magic getter
	*	@access public
	*	@param string $property Property name
	*	@return mixed
	*/	
	public function &__get($property)
	{
		
		return $this -> data[$property];
		
	}	
	
	/**
	*	Renders current view to output
	*	@access public
	*/
	public function render()
	{
		
		$this -> requestView($this -> viewName);		
		
	}
	
	/**
	*	Renders current view to a buffer
	*	@access public	
	*	@return string
	*/	
	public function wrap()
	{
		
		ob_start();		
		
		$this -> requestView($this -> viewName);
		
		return ob_get_clean();		
		
	}
	
	/**
	*	Fetches view file
	*	@access public	
	*	@param string $viewName The view script being fetched
	*/	
	public function requestView($viewName)
	{

		$fileName = SRC_PATH.str_replace("\\", DIRECTORY_SEPARATOR, $viewName).".php";
		
		if(!file_exists($fileName)) throw new \MWCore\Exception\MWViewException($viewName);

		include( $fileName );
		
	}

	/**
	*	Fetches view file
	*	@access public	
	*	@param string $viewName The view script being fetched
	*/
	static function reqView($viewName)
	{

		$fileName = SRC_PATH.str_replace("\\", DIRECTORY_SEPARATOR, $viewName).".php";
		
		if(!file_exists($fileName)) throw new \MWCore\Exception\MWViewException($viewName);
		
		include( $fileName );		

	}
	
	/**
	*	Prepends asset path to given path
	*	@access public	
	*	@param string $path The path
	*/	
	public function asset($path)
	{
		
		echo ASSET_PATH . $path;
		
	}
	
	/**
	*	Prepends base path to given path
	*	@access public	
	*	@param string $path The path
	*/	
	public function path_to($path)
	{
		
		echo BASE_PATH . $path;
		
	}	
		
}