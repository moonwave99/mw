<?php

namespace Rest\Controller;

use MWCore\Controller\MWController;
use MWCore\Entity\MWEntity;
use MWCore\Kernel\MWRouter;
use MWCore\Kernel\MWSingleRoute;
use Rest\Component\Response;

class RestController extends MWController
{

	public function indexAction()
	{

		$this -> processURI(str_replace(REST_BASEPATH . "/", "", MWRouter::getPatternFromURI()));
		
	}
	
	protected function processURI($uri)
	{
		
		$tiles = MWSingleRoute::tiles($uri); 
		
		$baseTile = NULL;
		
		foreach($this -> getEntitiesInPackage() as $e)
		{
			
			$baseTile = $tiles[0];
			
			if($baseTile == array_pop(explode("\\", strtolower($e)))){
				
				$this -> handleResource($e, $this -> request -> getMethod(), array_slice($tiles, 1));
				return;
				
			}
			
		}
		
	}
	
	protected function handleResource($entityName, $method, $tiles)
	{

		$response = new Response();
		
		switch($method)
		{
			
			case 'GET':
			
				$rep = MWEntity::createRepository($entityName);
				
				$results = count($tiles) == 0 ? $rep -> findAll() : $rep -> findOneById($tiles[0]);
				
				$response -> content = $results;
				
				$results === false && $response -> status = Response::$_404;

				break;			
			
			case 'POST':
				
				if(is_numeric(end($tiles))){
					
					$response -> status = Response::$_403;					
					
				}else{
				
					$entity = $this -> bindRequest($entityName);

					$entity -> create();
					$response -> content = $entity;
					$response -> status = Response::$_201;				
					
				}
			
				break;			
			
			case 'PUT':

				$entity = $this -> bindRequest($entityName, end($tiles));
			
				if( (int)($entity -> id) == 0 ){
					
					$response -> status = Response::$_404;
					
				}else{
					
					$entity -> update();					
					$response -> content = $entity;				
					$response -> status = Response::$_200;
					
				}		

				break;
			
			case 'DELETE':
			
				$entity = $this -> bindRequest($entityName, end($tiles));
				
				if( (int)($entity -> id) == 0 ){
					
					$response -> status = Response::$_404;
					
				}else{
					
					$entity -> delete();								
					$response -> status = Response::$_200;
					
				}				
			
				break;
			
		}

		echo $response -> toJSON();
		
		exit;
		
	}
	
	protected function getEntitiesInPackage($package = "App")
	{

		$entities = glob(SRC_PATH . $package . '/Entity/*.php');
		
		foreach($entities as &$e)
		{
			
			$e = str_replace("/", "\\", substr($e, strlen(SRC_PATH), -4));
			
		}
		
		return $entities;
		
	}	
	
}