<?php
	
namespace MWCore\Component\Form;

use MWCore\Component\Form\MWField;
use MWCore\Component\Form\MWSelect;
use MWCore\Component\Form\MWTextarea;

class MWForm
{
	
	protected $fields;
	
	protected $attributes;
	
	static $urlRegex = "(http|ftp|https):\/\/[\w\-_]+(\.[\w\-_]+)+([\w\-\.,@?^=%&amp;:/~\+#!]*[\w\-\@?^=%&amp;/~\+#])?";

	public function __construct($attributes = array())
	{
		
		$this -> attributes = $attributes;
		$this -> fields = array();
		
	}
	
	public function addField($type, $name, $label, $attributes = array())
	{
		
		$this -> fields[] = new MWField($type, $name, $label, $attributes);
		
	}
	
	public function addText($name, $label, $attributes = array())
	{
		
		$this -> fields[] = new MWField('text', $name, $label, $attributes);
		
	}	
	
	public function addHidden($name, $attributes = array())
	{
		
		$this -> fields[] = new MWField('hidden', $name, NULL, $attributes);
		
	}	
	
	public function addCheckbox($name, $label, $attributes = array())
	{
		
		$this -> fields[] = new MWField('checkbox', $name, $label, $attributes);
		
	}
	
	public function addTextarea($name, $label, $attributes = array())
	{
		
		$this -> fields[] = new MWTextarea($name, $label, $attributes);		
		
	}	
	
	public function addSelect($name, $label, $options, $attributes = array())
	{
		
		$this -> fields[] = new MWSelect($name, $label, $options, $attributes);	
		
	}	
	
	/*
	public function addRadio($name, $label, $options, $attributes = array())
	{
		
		$this -> fields[] = array(
			
			"type"			=> 'radio',
			"name"			=> $name,
			"label"			=> $label,
			"options"		=> $options,
			"attributes"	=> $attributes
			
		);		
		
	}
	*/
	
	public function render()
	{
		
		echo '<form';
		
		foreach($this -> attributes as $key => $att)
		{
			
			echo sprintf(' %s="%s"', $key, $att);
			
		}
		
		echo '>';		

		foreach($this -> fields as $f)
		{
			
			$f -> render();
			
		}
		
		echo '</form>';
		
	}
	
}