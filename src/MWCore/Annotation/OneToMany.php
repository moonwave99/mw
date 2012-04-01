<?php

namespace MWCore\Annotation;
	
class OneToMany extends \Annotation
{
	
	public $entity;
	public $container;
	public $allownull = false;
	public $lazy = false;		

}