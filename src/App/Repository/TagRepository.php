<?php

namespace App\Repository;

use MWCore\Kernel\MWDBManager;
use MWCore\Repository\MWRepository;
use MWCore\Component\MWCollection;
	
class TagRepository extends MWRepository
{
	
	public function __construct()
	{

		parent::__construct('App\Entity\Tag');
		
	}

}