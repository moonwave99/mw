<?php
	
namespace MWCore\Component\Form;

use MWCore\Interfaces\MWRenderable;

class MWSelect implements MWRenderable
{
	
	protected $name;
	protected $label;
	protected $options;
	protected $attributes;
	
	public function __construct($name, $label, $options, $attributes = array())
	{
		
		$this -> name = $name;
		$this -> label = $label;
		$this -> options = $options;
		$this -> attributes = $attributes;
		
		$this -> attributes['multiple'] === true && $this -> name.='[]';		
		
	}	
	
	public function render(){
		
		$this -> renderField();
		
	}	
	
	protected function renderField()
	{
		
		printf('<select name="%s"', $this -> name);

		foreach($this -> attributes as $key => $att)
		{

			$att !== NULL && printf(' %s="%s"', $key, $att);

		}

		echo '>';
		
		foreach($this -> options as $option)
		{

			echo sprintf('<option %s>%s</option>',
				$option['disabled'] === true ? 'disabled="true"' : 'value="' . $option['value'] . '"',
				$option['label']
			);

		}					
		
		echo '</select>';		
		
	}
	
}