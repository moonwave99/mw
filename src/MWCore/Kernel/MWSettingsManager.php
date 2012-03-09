<?php

namespace MWCore\Kernel;

use MWCore\Component\MWCollection;
use MWCore\Kernel\MWDBManager;
use MWCore\Entity\MWSetting;
use MWCore\Repository\MWSettingRepository;

class MWSettingsManager
{

	protected $loaded;
	
	protected $settingList;

	public function __construct()
	{

		$this -> loaded = false;
		$this -> settingList = new MWCollection();
		
	}
	
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
	
	public function getAll()
	{
		
		$this -> load();
		
		return $this -> settingList;
		
	}

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
	
	public function load($force = false)
	{
		
		if($this -> loaded === true && $force === false)
			return;
		
		$rep = new MWSettingRepository();
		$this -> settingList = $rep -> findAll();
		
	}

}