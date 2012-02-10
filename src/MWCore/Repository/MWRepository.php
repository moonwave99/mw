<?php

namespace MWCore\Repository;

use MWCore\Entity\MWEntity;
use MWCore\Kernel\MWDBManager;
use MWCore\Kernel\MWQueryBuilder\MWQueryBuilder;
use MWCore\Component\MWCollection;
	
class MWRepository
{

	protected $entityname;
	
	protected $cache;
	
	public function __construct($entityname)
	{

		$this -> entityname = $entityname;
		
		$this -> cache = array();
		
	}

	public function findAll($start = 0, $range = 1000, $column = 'id', $order = 'ASC')
	{
		
		$dbh = MWDBManager::getInstance();	
		$qb = new MWQueryBuilder();
		
		$qb -> selectFrom( $this -> entityname ) -> order($column, $order) -> limit($start, $range);
		
		$queryResults = $dbh -> getDBData( $qb -> build() );
		
		$results = array();
		
		foreach($queryResults as $r)
		{

			$results[] = $this -> fillObjectFromArray($this -> entityname, $r);
			
		}
		
		return new MWCollection($results);
		
	}
	
	public function findAllByField($name, $value)
	{

		$dbh = MWDBManager::getInstance();	
		$qb = new MWQueryBuilder();
		
		$qb -> selectFrom( $this -> entityname ) -> where($name, '=');

		$query = $qb -> build();

		$queryResults = $dbh -> getDBData( $query , array(
			array(
				'field'	=> $name,
				'value'	=> $value,
				'type'	=> \PDO::PARAM_STR
			)
		));
	
		$results = array();
	
		foreach($queryResults as $r)
		{

			$results[] = $this -> fillObjectFromArray($this -> entityname, $r);
		
		}
	
		return new MWCollection($results);
		
	}	

	public function findOneById($id)
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

		return count($queryResults) > 0 ? $this -> fillObjectFromArray($this -> entityname, $queryResults[0]) : false;

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

		return count($queryResults) > 0 ? $this -> fillObjectFromArray($this -> entityname, $queryResults[0]) : false;

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

		return count($queryResults) > 0 ? $this -> fillObjectFromArray($this -> entityname, $queryResults[0]) : false;
		
	}
	
	protected function findFromJoinTable($field, $containerEntity, $id)
	{

		$dbh = MWDBManager::getInstance();		
		$qb = new MWQueryBuilder();

		$qb -> selectFrom( $field -> entity )
			-> innerjoin($field -> jointable, $field -> entity, $containerEntity)
			-> where(
				'id_'.MWEntity::getTableNameFromClass($containerEntity),
				'=',
				NULL	,
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
	
		$results = array();
	
		foreach($queryResults as $r)
		{

			$results[] = $this -> fillObjectFromArray($field -> entity, $r);
		
		}
	
		return new MWCollection($results);
		
	}
	
	
	protected function fillObjectFromArray($entityname, $result)
	{

		$fields = MWEntity::getFieldsWithAnnotationsFromClass($entityname);

		$entity = new $entityname($result['id']);
		
		$fieldName = null;
		$results = null;
		$annotation = null;

		foreach($fields as $field)
		{
			
			$annotation = array_shift(array_shift(array_values($field['annotations'])));
	
			switch( get_class($annotation) ){
				
				case "MWCore\Annotation\Field":
				
					$fieldName = $field['name'];

					$entity -> $fieldName = $annotation -> type == 'datetime' ? 
						new \DateTime($result[$fieldName]) :
						$result[$fieldName];
				
					break;
					
				case "MWCore\Annotation\OneToMany":
				
					$fieldName = $field['name'];
					$repName = MWEntity::getRepositoryNameFromClass($annotation -> entity);				

					$rep = new $repName;
					
					$results = $rep -> findAllByField("id_". MWEntity::getTableNameFromClass($entityname), $result['id'] );

					$entity -> $fieldName = $results === false ? array() : $results;
					
					break;
				
				case "MWCore\Annotation\OneToOne":
				case "MWCore\Annotation\ManyToOne":					
								
					$fieldName = MWEntity::getTableNameFromClass($annotation -> entity);	
					$repName = MWEntity::getRepositoryNameFromClass($annotation -> entity);
					$entityName = $annotation -> entity;
					
					$rep = new $repName;

					$entity -> $fieldName = $annotation -> container == "false" ?
								new $entityName( $result['id_'.$fieldName] ) :
								$rep -> findOneById( $result['id_'.$fieldName] );
				
					break;
					
				case "MWCore\Annotation\ManyToMany":
					
					$fieldName = $field['name'];

					$entity -> $fieldName = $this -> findFromJoinTable($annotation, $entityname, $result['id']);
				
					break;

				default:
				
					break;
				
			}
			
		}

		return $entity;
		
	}

}