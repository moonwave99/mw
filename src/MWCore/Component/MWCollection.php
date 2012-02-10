<?php
	
namespace MWCore\Component;

class MWCollection
{
	
	protected $elements;

	public function __construct($elements = NULL)
	{
		
		$this -> elements = $elements === NULL ? array() : $elements;
		
	}
	
	public function get($key)
	{
		
		return $this -> elements[$key];
		
	}
	
	public function set($key, $value)
	{
		
		$this -> elements[$key] = $value;
		
	}
	
	public function add($element)
	{
		
		$this -> elements[] = $element;
		
	}
	
	public function isEmpty()
	{
		
		return count($this -> elements) === 0;
		
	}
	
	public function clear()
	{
		
		$this -> elements = array();
		
	}
	
	public function size()
	{
		
		return count($this -> elements);
		
	}
	
	public function contains($element)
	{
		
		foreach($this -> elements as $e)
		{
			
			if($e -> equals($element) )
				return true;
			
		}
		
		return false;
		
	}
	
	public function toArray()
	{
		
		return $this -> elements;
		
	}
	
}