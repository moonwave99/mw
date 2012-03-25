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
*	MWSession Class - a session handler to get rid of ugly $_SESSION.
*/
class MWSession
{

	/**
	*	Current session name
	*	@access protected
	*	@var string
	*/
	protected $name;
	
	/**
	*	Stuff being hold in session
	*	@access protected
	*	@var array
	*/
	protected $values;
	
	/**
	*	Default constructor.
	*	@access public	
	*/
	public function __construct()
	{

		$this -> values = array();

	}
	
	/**
	*	Name setter
	*	@access public	
	*	@param string $name Name being set
	*/
	public function setName($name){ $this -> name = $name; }

	/**
	*	Name getter
	*	@access public	
	*	@return string
	*/
	public function getName(){ return $this -> name; }
	
	/**
	*	Stored values getter
	*	@access public	
	*	@param string $key Key of desired value
	*	@return mixed
	*/
	public function get($key)
	{
		
		return $_SESSION[$this -> name][$key];
		
	}
	
	/**
	*	Stored values setter
	*	@access public	
	*	@param string $key Key of what to set
	*	@param mixed $value What to set
	*/
	public function set($key, $value)
	{

		$_SESSION[$this -> name][$key] = $value;
		$this -> values[$key] = $value;
		
	}
	
	/**
	*	Removes something in current session by given key
	*	@access public	
	*	@param $key Key of what to remove
	*/
	public function remove($key)
	{
		
		$_SESSION[$this -> name][$key] = NULL;
		$this -> values[$key] = NULL;
		
	}
	
	/**
	*	Starts session
	*	@access public	
	*/
	public function start()
	{
		
		session_start($this -> name);

		$this -> values = $_SESSION[$this -> name];

		if( $this -> get('csrfToken') == NULL ){

			$this -> set('csrfToken', sha1(microtime()) );

		}
		
	}
	
	/**
	*	Destroys current session
	*	@access public	
	*/
	public function destroy()
	{
		
		$this -> values = array();
		$_SESSION = array();
	    session_destroy();
		
	}
	
	/**
	*	Regenerates session id
	*	@access public
	*/
	public function regenerate()
	{
		
		session_regenerate_id();
		
	}
	
}