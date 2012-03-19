<?php
	
namespace Backstage\Component\Form;

use MWCore\Interfaces\MWRenderable;

class BackstagePicture implements MWRenderable
{
	
	protected $name;
	protected $label;
	protected $src;
	protected $attributes;
	
	public function __construct($name, $label, $src = NULL, $attributes = array())
	{
		
		$this -> name = $name;
		$this -> label = $label;
		$this -> src = $src;
		$this -> attributes = $attributes;

	}
	
	public function render()
	{

	    echo '<div class="control-group">';
	    printf('<label class="control-label" for="%s">%s</label>', "file_".$this -> name, $this -> label);
	    echo	'<div class="controls">';
	
		$this -> renderField();
		
	    echo '  </div>';
	    echo '</div>';	
		
	}
	
	protected function renderField()
	{
		
		echo '<div class="clearfix">';
	    echo '<span class="thumbnail">';
	    echo    '<img src="http://placehold.it/160x120" alt="" id="thumb_'.$this -> name.'"/>';
	    echo '</span>';	
		printf('<input accept="image/*" type="file" class="input-file" id="file_'.$this -> name.'" name="files[]" %s/>', $this -> attributes['required'] !== NULL ? 'required' : '');
		echo '<input type="hidden" id="_'.$this -> name.'" name="'.$this -> name.'"/>';
		echo '</div>';		
		
	}
	
}