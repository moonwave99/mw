<?php

namespace MWCore\Annotation;
	
class OneToOne extends \Annotation
{
	
	public $entity;
	public $container;
	public $allownull = false;
	public $lazy = false;		

}