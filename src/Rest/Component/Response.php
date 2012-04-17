<?php
	
namespace Rest\Component;

class Response
{
	
	public $status;
	
	public $content;
	

	static $_200 = "200 OK";
	static $_201 = "201 Created";
	static $_403 = "403 Forbidden";	
	static $_404 = "404 Not Found";
	
	public function __construct($status = NULL, $content = NULL)
	{
		
		$this -> status = $status ?: self::$_200;
		$this -> content = $content;

	}

	public function toJSON()
	{

		header('HTTP/1.0 ' . $this -> status);
		header('Cache-Control: no-cache, must-revalidate');
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		header('Content-type: application/json');
		
		echo json_encode( standardize($this -> content) );		
		
	}
	
	protected function getResourceNameFromContent()
	{
		
		$resName = is_object($this -> content) ? 
			get_class($this -> content) == 'MWCore\Component\MWCollection' ? 
				get_class($this -> content -> get(0)) : get_class($this -> content)
			: 'text';
			
		return $resName;
		
	}
	
	static function getResourceNameForUrl($resName)
	{
		
		return strtolower(array_pop(explode("\\", $resName)));
		
	}
		
}