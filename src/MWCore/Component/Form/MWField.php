<?php
	
namespace MWCore\Component\Form;

use MWCore\Interfaces\MWRenderable;

class MWField implements MWRenderable
{
	
	protected $type;
	protected $name;
	protected $label;
	protected $attributes;

	public function __construct($type, $name, $label, $attributes = array())
	{
		$this -> type = $type;
		$this -> name = $name;
		$this -> label = $label;
		$this -> attributes = $attributes;

	}
	
	public function render(){
		
		$this -> renderField();
		
	}
	
	protected function renderField(){
		
		printf('<input type="%s" name="%s"', $this -> type, $this -> name);

		foreach($this -> attributes as $key => $att)
		{

			$att !== NULL && printf(' %s="%s"', $key, $att);

		}

		echo "/>";		
		
	}
	
}