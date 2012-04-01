<?php

namespace MWCore\Repository;

use MWCore\Entity\MWEntity;
use MWCore\Kernel\MWDBManager;
use MWCore\Kernel\MWClassInspector;
use MWCore\Kernel\MWQueryBuilder;
use MWCore\Component\MWCollection;
	
class MWRepository
{

	protected $entityname;
	
	static $cache = array();
	
	public function __construct($entityname)
	{

		$this -> entityname = $entityname;
		
	}

	public function findAll($start = 0, $range = 1000, $column = 'id', $order = 'ASC')
	{

		$dbh = MWDBManager::getInstance();	
		$qb = new MWQueryBuilder();

		$qb -> selectFrom( $this -> entityname ) -> order($column, $order) -> limit($start, $range);
		
		$queryResults = $dbh -> getDBData( $qb -> build() );
		
		return self::fillCollection($queryResults, $this -> entityname);
		
	}
	
	public function findAllByField($name, $value, $start = 0, $range = 1000, $column = 'id', $order = 'ASC')
	{

		$dbh = MWDBManager::getInstance();	
		$qb = new MWQueryBuilder();
		
		$qb -> selectFrom( $this -> entityname ) -> order($column, $order) -> where($name, '=') -> limit($start, $range);

		$query = $qb -> build();

		$queryResults = $dbh -> getDBData( $query , array(
			array(
				'field'	=> $name,
				'value'	=> $value,
				'type'	=> \PDO::PARAM_STR
			)
		));
	
		return self::fillCollection($queryResults, $this -> entityname);
		
	}	

	public function findOneById($id)
	{
		
		if($entity = &self::searchInCache($this -> entityname, $id))
		{

			return $entity;
			
		}

		if(!$rawData = $this -> getSingleRawData($id)) return false;

		$entity = new $this -> entityname;
		$entity -> fillFromArray($rawData);
			
		self::storeInCache($entity);

		return $entity;

	}
	
	public function findOnebyHash($hash)
	{
		
		$dbh = MWDBManager::getInstance();	
		$qb = new MWQueryBuilder();
		
		$qb -> selectFrom( $this -> entityname ) -> whereMD5('id', '=', ':hash') -> limit(0, 1);
		
		$queryResults = $dbh -> getDBData( $qb -> build() , array(
			array(
				'field'	=> 'hash',
				'value'	=> $hash,
				'type'	=> \PDO::PARAM_STR
			)
		));

		if( count($queryResults) == 0 ) return false;
		
		if($entity = &self::searchInCache($this -> entityname, $queryResults[0]['id'])){
		
			return $entity;		
			
		}else{
			
			$entity = new $this -> entityname;
			$entity -> fillFromArray($queryResults[0]);
			
			self::storeInCache($entity);

			return $entity;			
			
		}	

	}	
	
	public function findOneByField($fieldName, $value)
	{

		$dbh = MWDBManager::getInstance();	
		$qb = new MWQueryBuilder();
		
		$qb -> selectFrom( $this -> entityname ) -> where($fieldName, '=') -> limit(0, 1);

		$query = $qb -> build();

		$queryResults = $dbh -> getDBData( $query , array(
			array(
				'field'	=> $fieldName,
				'value'	=> $value,
				'type'	=> \PDO::PARAM_STR
			)
		));

		if( count($queryResults) == 0 ) return false;
		
		if($entity = &self::searchInCache($this -> entityname, $queryResults[0]['id'])){
		
			return $entity;		
			
		}else{
			
			$entity = new $this -> entityname;
			$entity -> fillFromArray($queryResults[0]);
			
			self::storeInCache($entity);

			return $entity;			
			
		}
		
	}
	
	public function getTotalRecords()
	{
		
		$dbh = MWDBManager::getInstance();		
		$ins = MWClassInspector::getInstance();	
		$qb = new MWQueryBuilder();
		
		$qb -> selectCount($this -> entityname);				

		$result = $dbh -> getDBData($qb -> build());
		
		return $result[0]['COUNT(id)'];
		
	}
	
	public function getSingleRawData($id)
	{
		
		$dbh = MWDBManager::getInstance();	
		$qb = new MWQueryBuilder();
		
		$qb -> selectFrom( $this -> entityname ) -> where('id', '=') -> limit(0, 1);

		$queryResults = $dbh -> getDBData( $qb -> build() , array(
			array(
				'field'	=> 'id',
				'value'	=> $id,
				'type'	=> \PDO::PARAM_INT
			)
		));
	
		return count($queryResults) == 0 ? false : $queryResults[0];		
		
	}
	
	static function fillCollection($queryResults, $entityname)
	{

		$results = array();
		
		$entity = NULL;
		
		foreach($queryResults as $r)
		{
		
			if(!$entity = &self::searchInCache($entityname, $r['id'])){

				$entity = new $entityname;
				$entity -> fillFromArray($r);

				self::storeInCache($entity);

			}
			
			$results[] = $entity;			
			
		}
		
		return new MWCollection($results);		
		
	}
	
	static function findFromJoinTable($field, $containerEntity, $id)
	{
		
		$dbh = MWDBManager::getInstance();		
		$qb = new MWQueryBuilder();

		$qb -> selectFrom( $field -> entity )
			-> innerjoin($field -> jointable, $field -> entity, $containerEntity)
			-> where(
				'id_'.MWEntity::getTableNameFromClass($containerEntity),
				'=',
				NULL,
				$field -> jointable
			)
			-> order('order', 'ASC', $field -> jointable);
		
		$queryResults = $dbh -> getDBData( $qb -> build() , array(
			array(
				'field'	=> 'id_'.MWEntity::getTableNameFromClass($containerEntity),
				'value'	=> $id,
				'type'	=> \PDO::PARAM_INT
			)
		));
		
		return self::fillCollection($queryResults, $field -> entity);
		
	}
	
	static function searchInCache($entityname, $id)
	{
	
		return self::$cache[$entityname][$id] ?: false;		
		
	}
	
	static function storeInCache($entity)
	{

		self::$cache[get_class($entity)][$entity -> id] = $entity;
		
	}
	
}