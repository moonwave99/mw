<?php

namespace Backstage\Annotation;
	
class BackstageField extends \Annotation
{

	public $label;
	public $inputMode = "text";
	public $target = "both";
	public $source;
	public $rich = false;
	
}