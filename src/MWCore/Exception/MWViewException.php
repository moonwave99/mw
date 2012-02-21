<?php
	
namespace MWCore\Exception;

class MWViewException extends \Exception
{

	public function __construct($viewName)
	{
		
		parent::__construct(sprintf("View '%s' cannot be found.", $viewName));
		
	}

}