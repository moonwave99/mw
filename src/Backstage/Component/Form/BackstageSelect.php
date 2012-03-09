<?php
	
namespace Backstage\Component\Form;

use MWCore\Component\Form\MWSelect;

class BackstageSelect extends MWSelect
{
	
	public function render()
	{

	    echo '<div class="control-group">';
	    printf('<label class="control-label" for="%s">%s</label>', "_".$this -> name, $this -> label);
	    echo	'<div class="controls">';
	
		$this -> renderField();
		
	    echo '  </div>';
	    echo '</div>';		
		
	}
	
}