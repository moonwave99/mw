<?php
	
namespace MWCore\Tools;

use MWCore\Kernel\MWClassInspector;
use MWCore\Exception\MWNamespaceException;

class MWComponentGenerator
{

	protected $entity;
	
	public function __construct($entity)
	{
		
		if(!class_exists($entity)){

			throw new MWNamespaceException($entity);

		}
		
		$this -> entity = $entity;
		
	}
	
	public function generateRepository()
	{
		
		printf("	Building repository for '%s'...\n", $this -> entity);
		
		$bits = explode('\\', $this -> entity);		
		$destination = sprintf(SRC_PATH."%s/Repository/%sRepository.php", $bits[0], $bits[2]);
		
		if(file_exists($destination)){
			
			printf("	Repository for '%s' already exists!\n\n", $this -> entity);
			
		}else if(touch($destination)){
			
			file_put_contents(
				$destination,
				sprintf(file_get_contents(__DIR__."/Templates/TemplateRepository.tpl"), $bits[0], $bits[2], $this -> entity)
			);
			
			printf("	Repository for '%s' built succesfully!\n\n", $this -> entity);			
			
		}else{
			
			printf("	Couldn't write repository file for '%s' .\n\n", $this -> entity);			
			
		}

	}
	
	public function generateCrudController()
	{
		
		printf("	Building CRUD Controller for '%s'...\n", $this -> entity);
		
		$bits = explode('\\', $this -> entity);
		$destination = sprintf(SRC_PATH."%s/Controller/Crud/%sController.php", $bits[0], $bits[2]);

		if(file_exists($destination)){
			
			printf("	CRUD Controller for '%s' already exists!\n\n", $this -> entity);
			
		}else if(touch($destination)){
			
			file_put_contents(
				$destination,
				sprintf(file_get_contents(__DIR__."/Templates/TemplateCrud.tpl"), $this -> entity, $bits[2])
			);
			
			printf("	CRUD Controller for '%s' built succesfully!\n\n", $this -> entity);			
			
		}else{
			
			printf("	Couldn't write CRUD Controller file for '%s' .\n\n", $this -> entity);			
			
		}

	}	

}