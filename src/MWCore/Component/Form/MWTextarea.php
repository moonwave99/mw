<?php
	
namespace MWCore\Component\Form;

use MWCore\Interfaces\MWRenderable;

class MWTextarea implements MWRenderable
{

	protected $name;
	protected $label;
	protected $attributes;
	
	public function __construct($name, $label, $attributes = array())
	{
		
		$this -> name = $name;
		$this -> label = $label;
		$this -> attributes = $attributes;
		
	}	
	
	public function render(){
		
		$this -> renderField();
		
	}
	
	protected function renderField()
	{
		
		printf('<textarea name="%s"', $this -> name);

		foreach($this -> attributes as $key => $att)
		{
			
			$att !== NULL && printf(' %s="%s"', $key, $att);

		}

		echo '></textarea>';		
		
	}
	
}