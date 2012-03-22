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

use MWCore\Interfaces\MWSingleton;

/**
*	MWClassInspector Class - inspects classes for meaningful annotations.
*/
class MWClassInspector implements MWSingleton
{

	/**
	*	Singleton instance field
	*	@access private
	*	@var MWClassInspector
	*/
	private static $instance = null;

	/**#@+
	*	@var array
	*/
	protected $annotationCache;
	protected $tableCache;
	protected $repCache;
	protected $reverseCache;
	protected $commonCache;	
	
	/**
	*	Returns unique instance of current class
	*	@access public
	*	@return MWClassInspector
	*/	
	public static function getInstance()
	{

		if(self::$instance == null)
		{   
			$c = __CLASS__;			
			self::$instance = new $c;
		}

		return self::$instance;
		
	}
	
	/**
	*	Default constructor, private for design purposes
	*	@access private
	*/	
	private function __construct()
	{	
		
		$this -> annotationCache = array();
		$this -> tableCache = array();
		$this -> repCache = array();
		$this -> reverseCache = array();
		$this -> commonCache = array();
		
	}	
	
	/**
	*	Returns annotations for given entity
	*	@access public
	*	@param mixed $entity Either entity instance or classname
	*	@return array
	*/
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
	
	/**
	*	Returns db-table name for given entity
	*	@access public
	*	@param mixed $entity Either entity instance or classname
	*	@return string
	*/	
	public function getTableNameForEntity($entity)
	{
		
		$className = is_object($entity) ? get_class($entity) : $entity;
		
		if( $this -> tableCache[$className] === NULL){

			$ref = new \ReflectionAnnotatedClass($entity);			
			$this -> tableCache[$className] = $ref -> getAnnotation("MWCore\Annotation\Table") -> value;
			
		}

		return $this -> tableCache[$className];		
		
	}
	
	/**
	*	Returns repository name
	*	@access public
	*	@param mixed $entity Either entity instance or classname
	*	@return string
	*/	
	public function getRepositoryNameForEntity($entity)
	{
	
		$className = is_object($entity) ? get_class($entity) : $entity;
		
		if( $this -> repCache[$className] === NULL){

			$ref = new \ReflectionAnnotatedClass($entity);
			$this -> repCache[$className] = $ref -> getAnnotation("MWCore\Annotation\Repository") -> value;			
			
		}

		return $this -> repCache[$className];
		
	}
	
	/**
	*	Returns reverse-annotations for given entity
	*	@access public
	*	@param mixed $entity Either entity instance or classname
	*	@return array
	*/	
	public function getReverseAnnotationsForEntity($entity)
	{

		$className = is_object($entity) ? get_class($entity) : $entity;
		
		if( $this -> reverseCache[$className] === NULL){

			$ref = new \ReflectionAnnotatedClass($entity);
			$this -> reverseCache[$className] = $ref -> getAllAnnotations("MWCore\Annotation\ReverseManyToMany");
			
		}

		return $this -> reverseCache[$className]; 
		
	}	
	
	/**
	*	Returns specific annotation for given entity by given name
	*	@access public
	*	@param mixed $entity Either entity instance or classname
	*	@param string $annotationName The name of the annotation being looked for
	*	@return array
	*/	
	public function getSingleAnnotationForEntity($entity, $annotationName)
	{

		$className = is_object($entity) ? get_class($entity) : $entity;

		if( $this -> commonCache[$className] === NULL){

			$ref = new \ReflectionAnnotatedClass($entity);
			$notes = $ref -> getAllAnnotations($annotationName);
			$this -> commonCache[$className] = $notes[0];
			
		}

		return $this -> commonCache[$className]; 
		
	}	
	
}