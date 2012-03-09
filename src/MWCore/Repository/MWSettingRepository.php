<?php

namespace MWCore\Repository;

use MWCore\Kernel\MWDBManager;
use MWCore\Repository\MWRepository;
use MWCore\Component\MWCollection;
	
class MWSettingRepository extends MWRepository
{
	
	public function __construct()
	{

		parent::__construct('MWCore\Entity\MWSetting');
		
	}

	public function findOneByKey($key)
	{
		
		return $this -> findOneByField('key', $key);
		
	}

}