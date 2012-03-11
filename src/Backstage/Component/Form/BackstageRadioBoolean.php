<?php
	
namespace Backstage\Component\Form;

use MWCore\Component\Form\MWField;

class BackstageRadioBoolean extends MWField
{
	
	public function __construct($name, $label, $attributes)
	{
		
		parent::__construct('radio', $name, $label, $attributes);
		
	}
	
	public function render()
	{
	
	  printf(
		'<div class="control-group">
          <label class="control-label">%1$s</label>
          <div class="controls">
            <label class="radio">
              <input type="radio" name="%2$s" id="_%2$s1" value="0" checked="">
			  No
            </label>
            <label class="radio">
              <input type="radio" name="%2$s" id="_%2$s2" value="1">
			  Yes
            </label>
          </div>
        </div>',
		$this -> label,
		$this -> name		
	);
	
	}
	
}