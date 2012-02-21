<?php

namespace MWCore\Kernel;

class MWView
{
	
	protected $view;
	
	protected $data;
	
	public function __construct($view, $data, $session, $settings, $context)
	{	
		
		$this -> view = str_replace("\\", DIRECTORY_SEPARATOR, $view);

		if(!file_exists(SRC_PATH . $this -> view.".php")) throw new \MWCore\Exception\MWViewException($view);
				
		$this -> data = array();
					
		foreach($data as $key => $value)
		{
			
			$this -> $key = $value;
			
		}					
					
		$this -> token		= $session -> get('csrfToken');
		$this -> settings	= $settings;
		
		$context -> isUserLogged() && $this -> user = $session -> get('user');
		
		require(SRC_PATH."MWCore/Libraries/arshaw/ti.php");			
		
	}
	
	public function __set($property, $value)
	{
		
		$this -> data[$property] = $value;
		
	}	
	
	public function &__get($property)
	{
		
		return $this -> data[$property];
		
	}	
	
	public function render()
	{
		
		$this -> requestView($this -> view);		
		
	}
	
	public function wrap()
	{
		
		ob_start();		
		
		$this -> requestView($this -> view);
		
		return ob_get_clean();		
		
	}
	
	public function requestView($viewName)
	{

		$fileName = SRC_PATH.str_replace("\\", DIRECTORY_SEPARATOR, $viewName).".php";

		if(!file_exists($fileName)) throw new \MWCore\Exception\MWViewException($viewName);

		include( $fileName );
		
	}
	
	static function reqView($viewName)
	{
		
		$fileName = SRC_PATH.str_replace("\\", DIRECTORY_SEPARATOR, $viewName).".php";

		if(!file_exists($fileName)) throw new \MWCore\Exception\MWViewException($viewName);
		
		include( $fileName );		
		
	}
	
	public function asset($path)
	{
		
		echo ASSET_PATH.$path;
		
	}
	
	public function path_to($path)
	{
		
		echo BASE_PATH.$path;
		
	}	
		
}