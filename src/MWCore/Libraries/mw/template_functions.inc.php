<?php

	function asset($path)
	{
		
		echo ASSET_PATH.$path;
		
	}
	
	function requestView($view, $data)
	{

		$view = str_replace("\\", DIRECTORY_SEPARATOR, $view);

		include( SRC_PATH.$view.".php" );
		
	}
	
	function path_to($path)
	{
		
		echo BASE_PATH.$path;
		
	}