<?php

namespace MWCore\Kernel;

use MWCore\Kernel\MWDBManager;

class MWSettings
{
	
	protected $tablename;
	
	protected $loaded = false;
	
	public function __construct()
	{
		$this -> tablename = 'settings';
	}
	
	public function __set($property, $value)
	{

		if(property_exists($this, $property) || $this -> loaded === false){
			
			$this -> $property = $value;			
			
		}else{

		}
		
	}

	public function __get($property)
	{
		if(!$this -> loaded){
			$this -> load();
		}
		
		if(property_exists($this, $property)){
			
			return $this -> $property;
			
		}else{
				
		}		
		
	}
	
	public function load()
	{
		
		$dbh = MWDBManager::getInstance();
		
		$queryResults = $dbh -> getDBData(sprintf("SELECT * FROM %s", $this -> tablename));
		
		foreach($queryResults as $r){
			
			$this -> $r['name'] = $r['value'];
			
		}
		
		$this -> loaded = true;
		
	}
	
	public function save()
	{

		$dbh = MWDBManager::getInstance();
		
		foreach(get_object_vars($this) as $key => $value){
			
			if(strtoupper($key) == $key){
				
				$dbh -> setDBData('UPDATE settings SET value = :value WHERE name = :key', array(					
					array(
						'field'	=> 'key',
						'value'	=> $key,
						'type'	=> \PDO::PARAM_STR			
					),					
					array(
						'field'	=> 'value',
						'value'	=> $value,
						'type'	=> \PDO::PARAM_STR			
					)
				));
				
			}
			
		}
		
	}
	
}