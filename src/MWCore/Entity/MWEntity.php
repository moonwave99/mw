<?php

namespace MWCore\Entity;

use MWCore\Kernel\MWDBManager;
use MWCore\Kernel\MWClassInspector;
use MWCore\Kernel\MWQueryBuilder;
use MWCore\Interfaces\MWPersistent;
use MWCore\Component\MWCollection;
	
class MWEntity implements MWPersistent
{
	
	protected $id;

	public function __construct($id = NULL)
	{
	
		$this -> id = $id;
		
	}
	
	public function __set($property, $value)
	{

		method_exists($this, 'set'.ucwords($property))
			? $this -> {'set'.ucwords($property)}($value)
			: property_exists($this, $property) && $this -> $property = $value;

	}	
	
	public function &__get($property)
	{

		return method_exists($this, 'get'.ucwords($property))
			? $this -> {'get'.ucwords($property)}()
			: (property_exists($this, $property) ? $this -> $property : NULL);
		
	}
	
	public function standardize()
	{
		
		$std = new \stdClass;
		
		foreach (get_object_vars($this) as $name => $value) {
			
			if(is_object($value)){
			
				switch(get_class($value)){
					
					case "DateTime":
						$value = $value -> format(\MWCore\Kernel\MWDBManager::$dateFormat);
						break;
						
					case "MWCore\Component\MWCollection":
						$value = $value -> toArray();
						break;
						
					default:
						$value -> standardize();					
						break;
					
				}

			}
			
			if(is_array($value)){
				
				foreach($value as &$v){
					
					$v = $v -> standardize();
					
				}
				
			}
			
			$std -> $name = $value;
			
		}
		return $std;		
		
	}
	
	public function __toString()
	{
		
		return get_class($this)." - ".$this -> id;
		
	}
	
	public function equals($other)
	{
		
		return get_class($this) == get_class($other) && $this -> id == $other -> id;
		
	}
	
	public function export()
	{
		
		return get_object_vars($this);
		
	}
	
	public function fillFromArray($result)
	{

		$fields = self::getFieldsWithAnnotationsFromClass($this);

		$this -> id = $result['id'];
		
		$fieldName = null;
		$results = null;
		$annotation = null;

		foreach($fields as $field)
		{
			
			$annotation = array_shift(array_shift(array_values($field['annotations'])));
			
			switch( get_class($annotation) ){
				
				case "MWCore\Annotation\Field":
				
					$fieldName = $field['name'];

					$this -> $fieldName = $annotation -> type == 'datetime' ? 
						new \DateTime($result[$fieldName]) :
						$result[$fieldName];
				
					break;
					
				case "MWCore\Annotation\OneToMany":
				
					$fieldName = $field['name'];
					$repName = self::getRepositoryNameFromClass($annotation -> entity);				

					$rep = new $repName;
					
					$results = $rep -> findAllByField("id_". self::getTableNameFromClass($entityname), $result['id'] );

					$this -> $fieldName = $results === false ? array() : $results;
					
					break;
				
				case "MWCore\Annotation\OneToOne":
				case "MWCore\Annotation\ManyToOne":					
					
					$fieldName = self::getTableNameFromClass($annotation -> entity);	
					$repName = self::getRepositoryNameFromClass($annotation -> entity);
					$entityName = $annotation -> entity;
					
					$rep = new $repName;

					$this -> $fieldName = $annotation -> container == "false" ?
								new $entityName( $result['id_'.$fieldName] ) :
								$rep -> findOneById( $result['id_'.$fieldName] );
				
					break;
					
				case "MWCore\Annotation\ManyToMany":
					
					$fieldName = $field['name'];

					$this -> $fieldName = \MWCore\Repository\MWRepository::findFromJoinTable(
						$annotation,
						get_class($this),
						$result['id']
					);
				
					break;

				default:
				
					break;
				
			}
			
		}	
		
	}
	
	public function hydrate()
	{
		
		$rep = self::createRepository($this);
		return $rep -> findOneById($this -> id);
		
	}
	
	public function create()
	{

		$dbh = MWDBManager::getInstance();
		$ins = MWClassInspector::getInstance();		
		$qb = new MWQueryBuilder();

		$qb -> insertInto( $this );

		$fields = $ins -> getAnnotationsForEntity($this);
		
		$value = null;
		$type = null;
		$params = array();		
		$m2m = array();
		$tmpAnnotationName = null;
		
		if($this -> id !== NULL){
			
			$qb -> addColumn("id", $this -> id, 'int');
			$params[] = array(
				'field'	=> 'id',
				'value'	=> $this -> id,
				'type'	=> \PDO::PARAM_INT
			);			
			
		}
			
		
		foreach($fields as $field)
		{

			$tmpAnnotationName = @array_shift(@array_keys($field['annotations']));

			switch($tmpAnnotationName){
				
				case "MWCore\Annotation\Field":
				
					$qb -> addColumn($field['name'], $field['value'], $field['annotations'][$tmpAnnotationName][0] -> type);
					
					switch($field['annotations'][$tmpAnnotationName][0] -> type){

						case "string":
						case "text":	
							$value = $this -> $field['name'] !== NULL ? $this -> $field['name'] : $field['annotations'][$tmpAnnotationName][0] -> default;
							$type = \PDO::PARAM_STR;							
							break;
							
						case "datetime":					
							$value = $this -> $field['name'] !== NULL ? 
								$this -> $field['name'] -> format(\MWCore\Kernel\MWDBManager::$dateFormat) :
								date(\MWCore\Kernel\MWDBManager::$dateFormat);
							$type = \PDO::PARAM_STR;						
							break;
							
						case "int":
						default:
							$value = $this -> $field['name'] !== NULL ? $this -> $field['name'] : $field['annotations'][$tmpAnnotationName][0]	 -> default;						
							$type = \PDO::PARAM_INT;
							break;

					}					
					
					$params[] = array(
						'field'	=> $field['name'],
						'value'	=> $value,
						'type'	=> $type
					);					
				
					break;

				case "MWCore\Annotation\ManyToOne":					
				case "MWCore\Annotation\OneToOne":
				
					$qb -> addColumn("id_".$field['name'], $field['value'] -> id, 'int');
					
					$params[] = array(
						'field'	=> "id_".$field['name'],
						'value'	=> $this -> $field['name'] -> id,
						'type'	=> \PDO::PARAM_INT				
					);								
				
					break;
					
				case "MWCore\Annotation\ManyToMany":

					foreach($field['value'] -> toArray() as $i => $v)
					{
					
						$m2m[] = array(
						
							"query" => sprintf(
								"INSERT INTO %s VALUES(:id_%s, :id_%s, :order) ",
								$field['annotations'][$tmpAnnotationName][0] -> jointable,
								$ins -> getTableNameForEntity($field['annotations'][$tmpAnnotationName][0] -> entity),
								$ins -> getTableNameForEntity($this)
							),
						
							"binds" => array(
								array(
									'field'	=> sprintf('id_%s', $ins -> getTableNameForEntity($field['annotations'][$tmpAnnotationName][0] -> entity)),
									'value'	=> $v -> id,
									'type'	=> \PDO::PARAM_INT					
								),
							
								array(
									'field'	=> 'order',
									'value'	=> $i,
									'type'	=> \PDO::PARAM_INT					
								),								

							)
						
						);				
					
					}
				
					break;
				default:
					break;
				
			}
			
		}

		if($this -> id !== NULL)
			$dbh -> setDBData($qb -> build(), $params);
		else
			$this -> id = $dbh -> setDBData($qb -> build(), $params);

		foreach($m2m as $m)
		{

			$m['binds'][] = array(
				'field'	=> sprintf('id_%s', $ins -> getTableNameForEntity($this)),
				'value'	=> $this -> id,
				'type'	=> \PDO::PARAM_INT
			);

			$dbh -> setDBData($m['query'], $m['binds']);
			
		}
	
	}
	
	public function update()
	{
	
		$dbh = MWDBManager::getInstance();
		$ins = MWClassInspector::getInstance();		
		$qb = new MWQueryBuilder();

		$qb -> update( $this );

		$fields = $ins -> getAnnotationsForEntity($this);

		$value = null;		
		$type = null;
		$params = array();
		$m2m = array();
		$tmpAnnotationName = null;
		
		foreach($fields as $field)
		{

			$tmpAnnotationName = @array_shift(@array_keys($field['annotations']));

			switch($tmpAnnotationName){
				
				case "MWCore\Annotation\Field":
				
					$qb -> addColumn($field['name'], $field['value'], $field['annotations'][$tmpAnnotationName][0] -> type);
					
					switch($field['annotations'][$tmpAnnotationName][0] -> type){

						case "string":
						case "text":	
							$value = $this -> $field['name'] !== NULL ? $this -> $field['name'] : $field['annotations'][$tmpAnnotationName][0] -> default;
							$type = \PDO::PARAM_STR;							
							break;
							
						case "datetime":					
							$value = $this -> $field['name'] !== NULL ? 
								$this -> $field['name'] -> format(\MWCore\Kernel\MWDBManager::$dateFormat) :
								date(\MWCore\Kernel\MWDBManager::$dateFormat);
							$type = \PDO::PARAM_STR;						
							break;
							
						case "int":
						default:
							$value = $this -> $field['name'] !== NULL ? $this -> $field['name'] : $field['annotations'][$tmpAnnotationName][0] -> default;						
							$type = \PDO::PARAM_INT;
							break;

					}					
					
					$params[] = array(
						'field'	=> $field['name'],
						'value'	=> $value,
						'type'	=> $type
					);					
				
					break;

				case "MWCore\Annotation\ManyToOne":					
				case "MWCore\Annotation\OneToOne":
				
					$qb -> addColumn("id_".$field['name'], $field['value'] -> id, 'int');	
					
					$params[] = array(
						'field'	=> "id_".$field['name'],
						'value'	=> $this -> $field['name'] -> id,
						'type'	=> \PDO::PARAM_INT				
					);								
				
					break;
					
				case "MWCore\Annotation\ManyToMany":
				
					$delQuery = sprintf(
						"DELETE FROM %s WHERE %s = :%s",
						$field['annotations'][$tmpAnnotationName][0] -> jointable,
						'id_' . $ins -> getTableNameForEntity($this),
						'id_' . $ins -> getTableNameForEntity($this)
					);

					$dbh -> setDBData($delQuery, array(
						array(
							'field' => 'id_' . $ins -> getTableNameForEntity($this),
							'value' => $this -> id,
							'type'	=> \PDO::PARAM_INT
						)
					));			

					foreach($field['value'] -> toArray() as $i => $v)
					{
						
						$m2m[] = array(
							
							"query" => sprintf(
								"INSERT INTO %s VALUES(:id_%s, :id_%s, :order) ",
								$field['annotations'][$tmpAnnotationName][0] -> jointable,
								$ins -> getTableNameForEntity($field['annotations'][$tmpAnnotationName][0] -> entity),
								$ins -> getTableNameForEntity($this)
							),

							"binds" => array(
								array(
									'field'	=> sprintf('id_%s', $ins -> getTableNameForEntity($field['annotations'][$tmpAnnotationName][0] -> entity)),
									'value'	=> $v -> id,
									'type'	=> \PDO::PARAM_INT					
								),
								
								array(
									'field'	=> 'order',
									'value'	=> $i,
									'type'	=> \PDO::PARAM_INT					
								),								

							)
							
						);				
						
					}
				
					break;
				default:
					break;
				
			}
			
		}
		
		$qb -> where('id', '=', 'id');
		
		$params[] = array(
			'field'	=> 'id',
			'value'	=> $this -> id,
			'type'	=> \PDO::PARAM_INT
		);

		$dbh -> setDBData($qb -> build(), $params);

		foreach($m2m as $m)
		{

			$m['binds'][] = array(
				'field'	=> sprintf('id_%s', $ins -> getTableNameForEntity($this)),
				'value'	=> $this -> id,
				'type'	=> \PDO::PARAM_INT
			);

			$dbh -> setDBData($m['query'], $m['binds']);
			
		}

	}
	
	public function delete()
	{

		$dbh = MWDBManager::getInstance();
		$ins = MWClassInspector::getInstance();				

		$dbh -> setDBData(sprintf("DELETE FROM %s WHERE id = :id", $ins -> getTableNameForEntity($this)), array(
			array(
				'field'	=> 'id',
				'value'	=> $this -> id,
				'type'	=> \PDO::PARAM_INT				
			)
		));

		$m2m = $ins -> getAnnotationsForEntity($this);

		foreach($m2m['MWCore\Annotation\ManyToMany'] as $m)
		{

			$delQuery = sprintf(
				"DELETE FROM %s WHERE %s = :%s",
				$m['annotations'][0] -> jointable,
				'id_' . $ins -> getTableNameForEntity($this),
				'id_' . $ins -> getTableNameForEntity($this)
			);
			
			$dbh -> setDBData($delQuery, array(
				array(
					'field' => 'id_' . $ins -> getTableNameForEntity($this),
					'value' => $this -> id,
					'type'	=> \PDO::PARAM_INT
				)
			));

		}

		$reverse = $this -> getReverseAnnotations();
		
		foreach($reverse as $rev)
		{
			
			$dbh -> setDBData(sprintf("DELETE FROM %s WHERE id_%s = :id", $rev -> jointable, $ins -> getTableNameForEntity($this)), array(
				array(
					'field'	=> 'id',
					'value'	=> $this -> id,
					'type'	=> \PDO::PARAM_INT				
				)
			));
			
		}
		
	}	
	
	public function getTableName()
	{

		$ins = MWClassInspector::getInstance();
		return $ins -> getTableNameForEntity($this);
		
	}
	
	static function getTableNameFromClass($className)
	{
		
		$ins = MWClassInspector::getInstance();
		return $ins -> getTableNameForEntity($className);
		
	}
	
	public function getRepositoryName()
	{
		
		$ins = MWClassInspector::getInstance();
		return $ins -> getRepositoryNameForEntity($this);	
		
	}	
	
	static function getRepositoryNameFromClass($className)
	{
		
		$ins = MWClassInspector::getInstance();
		return $ins -> getRepositoryNameForEntity($className);	
		
	}
	
	public function getFieldsWithAnnotations($annotationName = NULL)
	{

		$ins = MWClassInspector::getInstance();
		return $ins -> getAnnotationsForEntity($this, $annotationName = NULL);
		
	}

	static function getFieldsWithAnnotationsFromClass($className, $annotationName = NULL)
	{

		$ins = MWClassInspector::getInstance();
		return $ins -> getAnnotationsForEntity($className, $annotationName = NULL);
		
	}
	
	public function getReverseAnnotations()
	{
		
		$ins = MWClassInspector::getInstance();
		return $ins -> getReverseAnnotationsForEntity($this);		
		
	}
	
	static function getReverseAnnotationsByClass($className)
	{
		
		$ins = MWClassInspector::getInstance();
		return $ins -> getReverseAnnotationsForEntity($className);
		
	}	
	
	static function createRepository($entity)
	{
		
		$ins = MWClassInspector::getInstance();
		$repName = $ins -> getRepositoryNameForEntity($entity);
		return new $repName;
		
	}

}