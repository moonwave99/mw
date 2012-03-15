<?php
	
namespace MWCore\Exception;

class MWResourceNotFoundException extends \Exception
{

	public function __construct($resName)
	{
		
		parent::__construct(sprintf("Resource '%s' not found.", $resName));
		
	}

}