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

use MWCore\Component\MWCollection;
use MWCore\Kernel\MWDBManager;
use MWCore\Entity\MWSetting;
use MWCore\Repository\MWSettingRepository;

/**
*	MWSettingsManager Class - handles global website settings and provides lazy loading.
*/
class MWSettingsManager
{

	/**
	*	@access protected
	*	@var boolean
	*/
	protected $loaded;
	
	/**
	*	@access protected
	*	@var MWCollection
	*/	
	protected $settingList;

	/**
	*	Default constructor.
	*	@access public	
	*/
	public function __construct()
	{

		$this -> loaded = false;
		$this -> settingList = new MWCollection();
		
	}
	
	/**
	*	Magic getter
	*	@access public
	*	@param string $key Property name
	*	@return mixed
	*/	
	public function __get($key)
	{
		
		!$loaded && $this -> load();
		
		foreach($this -> settingList -> toArray() as $s)
		{
			
			if($s -> key == $key)
				return $s;
			
		}
		
		return NULL;
		
	}
	
	/**
	*	Returns all settings
	*	@access public
	*	@return MWCollection
	*/	
	public function getAll()
	{
		
		$this -> load();
		
		return $this -> settingList;
		
	}

	/**
	*	Saves Setting entry to database
	*	@access public
	*	@param string $key Property name
	*	@param mixed $value Property value
	*	@param string $description Setting description text
	*	@param string $type Setting type
	*/
	public function saveSetting($key, $value, $description, $type)
	{
		
		!$loaded && $this -> load();		
		
		$setting = new MWSetting();
		$setting -> key = $key;
		$setting -> value = $value;
		$setting -> description = $description;
		$setting -> type = $type;
		
		foreach($this -> settingList -> toArray() as $i => $s)
		{
			
			if($s -> key == $setting -> key)
			{
				
				$setting -> id = $s -> id;
				$setting -> update();
				
				return true;
				
			}
			
		}
		
		$setting -> create();
		
	}
	
	/**
	*	Loads settings from database, if not loaded yet
	*	@access public
	*	@param boolean $force If true, enforces reload
	*/	
	public function load($force = false)
	{
		
		if($this -> loaded === true && $force === false)
			return;
		
		$rep = new MWSettingRepository();
		$this -> settingList = $rep -> findAll();
		
	}

}