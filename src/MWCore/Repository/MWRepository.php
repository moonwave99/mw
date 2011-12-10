<?php

namespace MWCore\Repository;

use MWCore\Kernel\MWDBManager;
	
class MWRepository
{

	protected $entityname;
	
	protected $entitypath;
	
	public function __construct($entity)
	{

		$this -> entityname = strtolower(array_pop(explode('\\', $entity)));
		
		$this -> entitypath = $entity;
		
	}

	public function findAll()
	{
		
		$dbh = MWDBManager::getInstance();
		
		$query = "
			SELECT * FROM ".$this->entityname."
		";

		$queryResults = $dbh -> getDBData($query);

		if( count($queryResults) == 0 )
			return false;
		
		$results = array();
		
		foreach($queryResults as $r)
		{

			$results[] = $this -> fillObjectFromArray($this -> entitypath, $r);
			
		}
		
		return count($results) > 0 ? $results : false;
			
	}

	public function findOnebyId($id)
	{
		
		$dbh = MWDBManager::getInstance();
		
		$query = "
			SELECT * FROM ".$this->entityname."
			WHERE id = :id
		";
		
		$r = $dbh -> getDBData($query, array(
			array(
				'field'	=> 'id',
				'value'	=> $id,
				'type'	=> \PDO::PARAM_INT				
			)
		));
		
		return count($r) > 0 ? $this -> fillObjectFromArray($this -> entitypath, $r[0]) : false;

	}
	
	public function findOnebyHash($hash)
	{
		
		$dbh = MWDBManager::getInstance();
		
		$query = "
			SELECT * FROM ".$this->entityname."
			WHERE MD5(id) = :hash
		";
		
		$r = $dbh -> getDBData($query, array(
			array(
				'field'	=> 'hash',
				'value'	=> $hash,
				'type'	=> \PDO::PARAM_INT				
			)
		));
		
		return count($r) > 0 ? $this -> fillObjectFromArray($this -> entitypath, $r[0]) : false;

	}	
	
	public function findOneByName($name)
	{

		$dbh = MWDBManager::getInstance();
		
		$query = "
			SELECT * FROM ".$this->entityname."
			WHERE name = :name
			LIMIT 0, 1
		";
		
		$r = $dbh -> getDBData($query, array(
			array(
				'field'	=> 'name',
				'value'	=> $name,
				'type'	=> \PDO::PARAM_STR				
			)
		));

		return count($r) > 0 ? $this -> fillObjectFromArray($this -> entitypath, $r[0]) : false;	
		
	}
	
	protected function fillObjectFromArray($entityName, $array)
	{

		$entity = new $entityName($array['id']);

		foreach($array as $key => $value)
		{
			
			$entity -> $key = $value;
			
		}

		return $entity;
		
	}

}