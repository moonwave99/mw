<?php

namespace MWCore\Controller;

use MWCore\Controller\MWController;

class MWTestController extends MWController
{

	public function testAction()
	{
		
		$tag = new \App\Entity\Tag(1);
		
		//pre($tag);
		
		$note = $this -> inspector -> getSingleAnnotationForEntity($tag, 'Backstage\Annotation\EntitySetup');
		
		pre($note);
		
	}
		
}