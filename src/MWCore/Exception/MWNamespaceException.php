<?php
	
namespace MWCore\Exception;

class MWNamespaceException extends \Exception
{

	public function __construct($entity)
	{
		
		parent::__construct(sprintf("Class '%s' not found in given namespace.", $entity));
		
	}

}