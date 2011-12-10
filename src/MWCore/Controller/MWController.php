<?php

namespace MWCore\Controller;

use MWCore\Component\MWRequest;
use MWCore\Kernel\MWRouter;
use MWCore\Kernel\MWLog;
use MWCore\Kernel\MWContext;
use MWcore\Kernel\MWSession;	
use MWCore\Kernel\MWSettings;
	
class MWController
{

	protected $session;
	
	protected $context;	
	
	protected $log;	

	protected $request;		
	
	protected $settings;

	public function __construct()
	{

		$this -> session = MWSession::getInstance();
		$this -> context = MWContext::getInstance();
		$this -> log	 = MWLog::getInstance();
		$this -> request = MWRequest::getInstance();
		$this -> settings = MWSettings::getInstance();
	
	}
	
	public function json($data)
	{
		
		header('Cache-Control: no-cache, must-revalidate');
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		header('Content-type: application/json');		
		
		echo json_encode($data);
		
	}
	
	public function redirect($path)
	{
		
		header("Location: ".BASE_PATH.$path);
		exit;
		
	}
	
	protected function csrfCheck()
	{
		
		return $this -> request -> get('token') == $this -> session -> get('csrfToken');
		
	}
	
	protected function requestView($view, $data)
	{

		$view = str_replace("\\", DIRECTORY_SEPARATOR, $view);

		if(file_exists(APP_VIEWS.$view.".php")){
					
			$data['token']		= $this -> session -> get('csrfToken');
			$data['settings']	= $this -> settings;
			if($this -> context -> isUserLogged())
				$data['user'] = $this -> context -> getUser();

			require_once(MW_CORE."/Libraries/arshaw/ti.php");
			require_once(MW_CORE."/Libraries/mw/template_functions.inc.php");			
			
			requestView($view, $data);
			
		}else{

			MWRouter::requestNotFound();
			
		}

	}
	
	protected function wrapView($view, $data)
	{
		
		$view = str_replace("\\", DIRECTORY_SEPARATOR, $view);

		$wrapper = false;

		if(file_exists(APP_VIEWS.$view.".php")){
			
			ob_start();
			
			$data['token']		= $this -> session -> get('csrfToken');
			$data['settings']	= $this -> settings;			
			if($this -> context -> isUserLogged())
				$data['user'] = $this -> context -> getUser();			

			require_once(MW_CORE."/Libraries/arshaw/ti.php");
			require_once(MW_CORE."/Libraries/mw/template_functions.inc.php");			
			
			requestView($view, $data);
			
			$wrapper = ob_get_clean();
			
		}
		
		return $wrapper;
		
	}

}