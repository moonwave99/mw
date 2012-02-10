<?php
	
namespace MWCore\Tools;

use MWCore\Kernel\MWClassInspector;
use MWCore\Kernel\MWDBManager;
use MWCore\Exception\MWNamespaceException;

class MWSchemaGenerator
{
	
	protected $entity;

	protected $tableInfo;
	
	protected $columnInfo;
	
	protected $pdo;
	
	protected $ins;

	public function __construct($entity)
	{
		
		if(!class_exists($entity)){

			throw new MWNamespaceException($entity);

		}
		
		$this -> entity = $entity;		
		$this -> pdo = new \PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
		$this -> ins = MWClassInspector::getInstance();

	}
	

	
	public function generateSchema()
	{

		$query = sprintf(
			"SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '%s' AND TABLE_NAME = '%s'",
			DB_NAME,
			$this -> ins -> getTableNameForEntity($this -> entity)
		);
		
		$this -> tableInfo = $this -> pdo -> query($query) -> fetch();

		if(!$this -> tableInfo['TABLE_NAME']){
			
			printf("	Creating schema for entity '%s':\n\n", $this -> entity);	
			$this -> _createSchema();
			printf("\n	Schema for '%s' created succesfully!\n\n", $this -> entity);
			
		}else{
			
			printf("	Updating schema for entity '%s':\n\n", $this -> entity);
			$this -> _updateSchema();
			printf("\n	Schema for '%s' updated succesfully!\n\n", $this -> entity);
			
		}		
		
	}
	
	protected function _createSchema()
	{
		
		$fields = $this -> ins -> getAnnotationsForEntity($this -> entity);

		$tmpAnnotation = NULL;
		
		$queryFields = "id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, ";

		foreach($fields as $field)
		{

			$tmpAnnotation = array_shift(array_shift($field['annotations']));				

			switch(get_class($tmpAnnotation)){

				case "MWCore\Annotation\Field":

					$queryFields .= sprintf('%1$s %2$s %3$s, ',
						$tmpAnnotation -> name,
						$this -> _getVarType($tmpAnnotation),
						$tmpAnnotation -> default != "" ? 'DEFAULT '.$tmpAnnotation -> default : ""
					);													

					break;
					
				case "MWCore\Annotation\OneToOne":							
				case "MWCore\Annotation\ManyToOne":
				
					$queryFields .= sprintf('id_%s INT(10) NOT NULL, ',
						$this -> ins -> getTableNameForEntity($tmpAnnotation -> entity)
					);					
				
					break;	
					
				case "MWCore\Annotation\ManyToMany":

					$this -> _createCrossTable($tmpAnnotation);

					break;											

				default:
					break;

			}

		}					
		
		$query = sprintf("CREATE TABLE %s (%s)", $this -> ins -> getTableNameForEntity($this -> entity), substr($queryFields, 0, -2));
		
		printf("#	%s \n", $query);	
		
		$this -> pdo -> query($query);		
		
	}	
	
	protected function _updateSchema()
	{
		
		$query = sprintf(
			"SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '%s' AND TABLE_NAME = '%s'",
			DB_NAME,
			$this -> ins -> getTableNameForEntity($this -> entity)
		);			

		$this -> columnInfo = $this -> pdo -> query($query) -> fetchAll(\PDO::FETCH_ASSOC);
		$fields = $this -> ins -> getAnnotationsForEntity($this -> entity);

		$tmpAnnotation = NULL;
		$tmpCol = NULL;
		$prev = NULL;
		$count = 0;
		
		foreach($fields as $field)
		{

			$tmpAnnotation = array_shift(array_shift($field['annotations']));				
			$tmpCol = $this -> _searchColumn($tmpAnnotation);
			
			switch(get_class($tmpAnnotation)){
				
				case "MWCore\Annotation\Field":
				
					$query = sprintf('ALTER TABLE %1$s %2$s %3$s %4$s %5$s %6$s',
						$this -> tableInfo['TABLE_NAME'],
						$tmpCol !== false ? 'MODIFY' : 'ADD',
						$tmpAnnotation -> name,
						$this -> _getVarType($tmpAnnotation),
						$tmpAnnotation -> default != "" ? 'DEFAULT '.$tmpAnnotation -> default : "",
						$prev !== NULL && $tmpCol === false ? "AFTER ".$this -> _getFieldFromAnnotation($prev) : ""
					);											

					$this -> pdo -> query($query);
					
					printf("#	%s	: %s \n", $field['name'], $query);
					
					break;				

				case "MWCore\Annotation\OneToOne":							
				case "MWCore\Annotation\ManyToOne":
				
					$query = sprintf('ALTER TABLE %1$s %2$s id_%3$s INT(10) NOT NULL %4$s',
						$this -> tableInfo['TABLE_NAME'],
						$tmpCol !== false ? 'MODIFY' : 'ADD',							
						$this -> ins -> getTableNameForEntity($tmpAnnotation -> entity),
						$prev !== NULL && $tmpCol === false ? "AFTER ".$this -> _getFieldFromAnnotation($prev) : ""							
					);					

					$this -> pdo -> query($query);
					
					printf("#	%s	: %s \n", $field['name'], $query);
				
					break;
					
				case "MWCore\Annotation\ManyToMany":
				
					$this -> _createCrossTable($tmpAnnotation);
					
					printf("#	%s	: creating cross table...\n", $field['name']);
				
					break;

				default:
					
					break;

			}		

			$prev = $tmpAnnotation;

		}		
		
	}	
	
	protected function _getVarType($annotation)
	{

		switch($annotation -> type){
			
			case "string":
				return sprintf("VARCHAR(%d)", $annotation -> length == "" ? 255 : $annotation -> length);
				break;
			
			case "text":
				return "TEXT";	
				break;
				
			case "int":
				return sprintf("INT(%d)", $annotation -> length == "" ? 11 : $annotation -> length);
				break;
			
		}
			
	}
	
	protected function _createCrossTable($annotation)
	{
		
		$query = sprintf(
			"SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '%s' AND TABLE_NAME = '%s'",
			DB_NAME,
			$annotation -> jointable
		);
		
		$crossInfo = $this -> pdo -> query($query) -> fetch();
		
		if(!$crossInfo['TABLE_NAME']){
			
			$query = sprintf(
				'CREATE TABLE %1$s (
					id_%2$s int(10) unsigned NOT NULL,
					id_%3$s int(10) unsigned NOT NULL,
					`order` int(10) unsigned NOT NULL,
					PRIMARY KEY (id_%2$s, id_%3$s)
				)',
				$annotation -> jointable,
				$this -> ins -> getTableNameForEntity($annotation -> entity),
				$this -> ins -> getTableNameForEntity($this -> entity)
			);
		
			$this -> pdo -> query($query);			
			
		}
		
	}
	
	protected function _getFieldFromAnnotation($annotation)
	{
		
		switch(get_class($annotation)){
			
			case "MWCore\Annotation\OneToOne":							
			case "MWCore\Annotation\ManyToOne":
				
				return "id_".$this -> ins -> getTableNameForEntity($annotation -> entity);
				break;
			
			default:
				return $annotation -> name;			
				break;
			
		}		
		
		
	}
	
	protected function _searchColumn($annotation)
	{

		foreach($this -> columnInfo as $col)
		{
			
			if($col['COLUMN_NAME'] == $this -> _getFieldFromAnnotation($annotation))
				return $col;

		}
		
		return false;

	}
	
}