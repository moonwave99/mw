<?php

	function asset($path)
	{
		
		echo ASSET_PATH.$path;
		
	}
	
	function requestView($view, $data)
	{

		include(APP_VIEWS.$view.".php");
		
	}
	
	function path_to($path)
	{
		
		echo BASE_PATH.$path;
		
	}