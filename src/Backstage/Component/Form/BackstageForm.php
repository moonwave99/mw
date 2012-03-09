<?php
	
namespace Backstage\Component\Form;

use MWCore\Component\Form\MWForm;

use Backstage\Component\Form\BackstageField;
use Backstage\Component\Form\BackstageSelect;
use Backstage\Component\Form\BackstageTextarea;
use Backstage\Component\Form\BackstagePicture;

class BackstageForm extends MWForm
{
	
	public function __construct($attributes = array())
	{
		
		parent::__construct($attributes);
		
		$this -> attributes['class'] = "form-horizontal backstage-form";
		
	}	
	
	public function addField($type, $name, $label, $attributes = array())
	{
		
		$this -> fields[] = new BackstageField($type, $name, $label, $attributes);
		
	}
	
	public function addText($name, $label, $attributes = array())
	{
		
		$this -> fields[] = new BackstageField('text', $name, $label, $attributes);
		
	}		
	
	public function addCheckbox($name, $label, $attributes = array())
	{
		
		$this -> fields[] = new BackstageField('checkbox', $name, $label, $attributes);
		
	}
	
	public function addTextarea($name, $label, $attributes = array())
	{
		
		$this -> fields[] = new BackstageTextarea($name, $label, $attributes);		
		
	}	
	
	public function addSelect($name, $label, $options, $attributes = array())
	{
		
		$this -> fields[] = new BackstageSelect($name, $label, $options, $attributes);	
		
	}	
	
	public function addPicture($name, $label, $src = NULL)
	{
	
		$this -> fields[] = new BackstagePicture($name, $label, $src);
		
	}	

	public function render()
	{
		
		echo '<form';
		
		foreach($this -> attributes as $key => $att)
		{
			
			echo sprintf(' %s="%s"', $key, $att);
			
		}
		
		echo '>';
		
		echo '<div class="modal-body">';
		
		foreach($this -> fields as $field)
		{
		
			$field -> render();
					
		}

?>
	
	</div>
	<div class="modal-footer">
		<input type="submit" class="btn btn-primary" value="Submit"/>
		<a href="#" class="btn" data-dismiss="modal">Cancel</a>
	</div>
</form>

<?php
		
	}
	
}