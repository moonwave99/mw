<?php
	
namespace MWCore\Exception;

class MWFixtureException extends \Exception
{

	public function __construct($packageName)
	{
		
		parent::__construct(sprintf("Fixtures for package '%s' can't be loaded.", $packageName));
		
	}

}