<?php

namespace MWCore\Entity;

use MWCore\Kernel\MWDBManager;
use MWCore\Interfaces\MWPersistent;
	
class MWEntity implements MWPersistent
{
	
	protected $id;
	
	protected $tablename;

	public function __construct($id = NULL)
	{
		$this -> id = $id;
	}
	
	public function __set($property, $value)
	{
		
		if(property_exists($this, $property)){
			
			$this -> $property = $value;			
			
		}else{

		}
		
	}	
	
	public function __get($property)
	{
		
		if(property_exists($this, $property)){
			
			return $this -> $property;
			
		}else{
				
		}		
		
	}
	
	public function create()
	{
		
	}
	
	public function update()
	{
		
	}
	
	public function delete()
	{

		$dbh = MWDBManager::getInstance();
		
		$query = "DELETE FROM ".$this -> tablename. " WHERE id = :id";
		
		$dbh -> setDBData($query, array(
			array(
				'field'	=> 'id',
				'value'	=> $this -> id,
				'type'	=> \PDO::PARAM_INT				
			)
		));
		
	}	

}