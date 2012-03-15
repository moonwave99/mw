<?php
	
namespace MWCore\Exception;

class MWClassDefinedException extends \Exception
{

	public function __construct($entity)
	{
		
		parent::__construct(sprintf("Class '%s' already defined in given namespace.", $entity));
		
	}

}