<?php

require_once("Libraries/addendum/annotations.php");
require_once("Libraries/mw/useful.inc.php");

class MWAutoloader
{

	public function __construct()
	{
		
		spl_autoload_extensions(".php");
		spl_autoload_register(array($this, 'loader'));
		
	}

	protected function loader($className)
	{
		
		$className = str_replace("\\", DIRECTORY_SEPARATOR, $className);

		if(file_exists(__DIR__."/..".DIRECTORY_SEPARATOR.$className.'.php')){

			require(__DIR__."/..".DIRECTORY_SEPARATOR.$className.'.php');
			
		}

	}

}

$autoloader = new MWAutoloader();