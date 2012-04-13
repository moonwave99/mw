<?php
	
namespace MWCore\Component;

class MWCollection implements \Iterator
{
	
	protected $elements;

	public function __construct($elements = NULL)
	{
		
		$this -> elements = $elements === NULL ? array() : $elements;
		
	}
	
	public function &get($key)
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
	
    public function rewind()
    {

        reset($this -> elements);

    }
  
    public function current()
    {
		
		return current($this -> elements);

    }
  
    public function key() 
    {
        
		return key($this -> elements);
        
    }
  
    public function next() 
    {

 		return next($this -> elements);

    }
  
    public function valid()
    {
	
		return key($this -> elements) !== NULL && key($this -> elements) !== FALSE;

    }	

	public function standardize()
	{
		
		$values = $this -> toArray();
		
		foreach($values as &$v)
		{
			
			$v = $v -> standardize();
			
		}
		
		return $values;
		
	}
	
}