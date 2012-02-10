<?php

namespace MWCore\Kernel;

use MWCore\Interfaces\MWSingleton;

class MWClassInspector implements MWSingleton
{

	private static $instance = null;

	protected $annotationCache;
	protected $tableCache;
	protected $repCache;
	protected $reverseCache;	
	
	public static function getInstance()
	{

		if(self::$instance == null)
		{   
			$c = __CLASS__;			
			self::$instance = new $c;
		}

		return self::$instance;
		
	}
	
	private function __construct()
	{	
		
		$this -> annotationCache = array();
		$this -> tableCache = array();
		$this -> repCache = array();
		$this -> reverseCache = array();
		
	}	
	
	public function getAnnotationsForEntity($entity)
	{

		$className = is_object($entity) ? get_class($entity) : $entity;

		$properties = NULL;
		
		if( $this -> annotationCache[$className] === NULL){

			$fields = array();
			$annotations = null;
			$temp = null;
			
			$refClass = new \ReflectionClass($className);
			
			foreach($refClass -> getProperties() as $field )
			{

				$ref = new \ReflectionAnnotatedProperty($className, $field -> name);

				$annotations = $ref -> getAnnotations();
				
				if(count($annotations) == 0)
					continue;
					
				$temp = array();
					
				foreach($annotations as $a){
					
					$temp[get_class($a)][] = $a;
					
				}

				$fields[ $ref -> name ] = array(
					"name" => $field -> name,
					"annotations" => $temp
				);

			}
			
			$this -> annotationCache[$className] = $fields;

		}
		
		$annotatedValues = $this -> annotationCache[$className];
		
		if(is_object($entity)){
			
			foreach($entity -> export() as $key => $value)
			{
				
				$annotatedValues[$key]['value'] = $value;
				
			}
			
		}

		return $annotatedValues;
		
	}	
	
	public function getTableNameForEntity($entity)
	{
		
		$className = is_object($entity) ? get_class($entity) : $entity;
		
		if( $this -> tableCache[$className] === NULL){

			$ref = new \ReflectionAnnotatedClass($entity);			
			$this -> tableCache[$className] = $ref -> getAnnotation("MWCore\Annotation\Table") -> value;
			
		}

		return $this -> tableCache[$className];		
		
	}
	
	public function getRepositoryNameForEntity($entity)
	{
	
		$className = is_object($entity) ? get_class($entity) : $entity;
		
		if( $this -> repCache[$className] === NULL){

			$ref = new \ReflectionAnnotatedClass($entity);
			$this -> repCache[$className] = $ref -> getAnnotation("MWCore\Annotation\Repository") -> value;			
			
		}

		return $this -> repCache[$className];
		
	}
	
	public function getReverseAnnotationsForEntity($entity)
	{

		$className = is_object($entity) ? get_class($entity) : $entity;
		
		if( $this -> reverseCache[$className] === NULL){

			$ref = new \ReflectionAnnotatedClass($entity);
			$this -> reverseCache[$className] = $ref -> getAllAnnotations("MWCore\Annotation\ReverseManyToMany");
			
		}

		return $this -> reverseCache[$className]; 
		
	}	
	
	
}