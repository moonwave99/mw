<?php

use MWCore\Repository\MWRoleRepository;

$roleRep = new MWRoleRepository();

$user = new \MWCore\Entity\MWUser();
$user -> username = "admin";
$user -> setPassword("admin");
$user -> roleList -> add($roleRep -> findOneByField('name', 'ROLE_ADMIN'));

$user -> create();	