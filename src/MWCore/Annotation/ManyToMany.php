<?php

namespace MWCore\Annotation;
	
class ManyToMany extends \Annotation
{
	
	public $entity;
	public $jointable;
	public $container;
	public $allownull = false;
	public $lazy = false;

}