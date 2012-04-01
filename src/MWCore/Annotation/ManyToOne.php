<?php

namespace MWCore\Annotation;
	
class ManyToOne extends \Annotation
{
	
	public $entity;
	public $container;
	public $allownull = false;
	public $lazy = false;	

}