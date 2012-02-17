<?php
	
namespace MWCore\Exception;

class MWPackageLoadException extends \Exception
{

	public function __construct($package)
	{
		
		parent::__construct(sprintf("Package '%s' can't be loaded.", $package));
		
	}

}