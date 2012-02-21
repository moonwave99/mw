<?php

namespace MWCore\Repository;

use MWCore\Kernel\MWDBManager;
use MWCore\Repository\MWRepository;
use MWCore\Component\MWCollection;
	
class MWUserRepository extends MWRepository
{
	
	public function __construct()
	{

		parent::__construct('MWCore\Entity\MWUser');
		
	}

}