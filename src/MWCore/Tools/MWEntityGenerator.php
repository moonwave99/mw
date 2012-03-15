<?php
	
namespace MWCore\Tools;

use MWCore\Exception\MWClassDefinedException;
use MWCore\Exception\MWResourceNotFoundException;

require(SRC_PATH."MWCore/Libraries/spyc/spyc.php");	

class MWEntityGenerator
{

	protected $packageName;
	
	protected $entityName;

	protected $fullEntityName;
	
	public function __construct($packageName, $entityName)
	{
		
		$this -> fullEntityName = sprintf('%s\Entity\%s', $packageName, $entityName);
		
		if(class_exists($this -> fullEntityName)) throw new MWClassDefinedException($this -> fullEntityName);
		
		$this -> packageName = $packageName;
		
		$this -> entityName = $entityName;
		
	}
	
	public function generateEntity()
	{

		$data = NULL;

		try{ $data = $this -> loadDefinition(); } catch(MWResourceNotFoundException $e){pre($e -> getMessage());}

		pre($data);

	}
	
	protected function loadDefinition()
	{
		
		$resName = sprintf('%s/Resources/Definitions/%s.yaml', $this -> packageName, $this -> entityName);

		if(!file_exists(SRC_PATH.$resName))
			throw new MWResourceNotFoundException($resName);
		
		return \Spyc::YAMLLoad(SRC_PATH.$resName);
		
	}

}