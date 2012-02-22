<?php

namespace MWCore\Controller;

use MWCore\Component\MWRequest;
use MWCore\Kernel\MWRouter;
use MWCore\Kernel\MWLog;
use MWCore\Kernel\MWClassInspector;
use MWCore\Kernel\MWSettings;
use MWCore\Kernel\MWView;
	
class MWController
{

	protected $session;
	
	protected $context;	
	
	protected $log;	

	protected $request;
	
	protected $settings;
	
	protected $inspector;

	public function __construct($session, $context, $request, $settings)
	{

		$this -> session	= $session;
		$this -> context	= $context;
		$this -> request	= $request;
		$this -> settings	= $settings;
		
		$this -> inspector	= MWClassInspector::getInstance();
		$this -> log	 	= MWLog::getInstance();		
		
	}
	
	public function json($data)
	{
		
		header('Cache-Control: no-cache, must-revalidate');
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		header('Content-type: application/json');

		echo json_encode( standardize($data) );
		
	}
	
	public function redirect($path)
	{
		
		header("Location: ".BASE_PATH.$path);
		
		exit;
		
	}
	
	protected function csrfCheck()
	{

		return $this -> request -> token == $this -> session -> get('csrfToken');
		
	}
	
	protected function requestView($viewName, $data)
	{
		
		try{
			
			$view = new MWView($viewName, $data, $this -> session, $this -> settings, $this -> context);

			$view -> render();		
			
		}catch(\MWCore\Exception\MWViewException $e){
			
			
			\MWCore\Kernel\MWRouter::requestNotFound();
			
		}
		
	}
	
	protected function wrapView($viewName, $data)
	{
		
		try{
		
			$view = new MWView($viewName, $data, $this -> session, $this -> settings, $this -> context);
			return $view -> wrap();
			
		}catch(\MWCore\Exception\MWViewException $e){
			
			return false;
			
		}

	}

}