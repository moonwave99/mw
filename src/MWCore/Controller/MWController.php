<?php

namespace MWCore\Controller;

use MWCore\Component\MWRequest;
use MWCore\Component\MWCollection;
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

	public function __construct()
	{
			
	}
	
	public function setSession($session){$this -> session = $session;}
	public function setContext($context){$this -> context = $context;}
	public function setRequest($request){$this -> request = $request;}
	public function setSettings($settings){$this -> settings = $settings;}
	public function setInspector($inspector){$this -> inspector = $inspector;}
	public function setLog($log){$this -> log = $log;}
	
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
	
	protected function bindRequest($entityName, $id = NULL)
	{
		
		$entity = null;
		$id = !isset($id) ? $this -> request -> id : $id;
		
		if($id !== NULL){

			$repName = $this -> inspector -> getRepositoryNameForEntity($entityName);	
			$rep = new $repName;
			$entity = $rep -> findOneById($id);

		}else{

			$entity = new $entityName;			
			
		}

		$fieldInfo = $this -> inspector -> getAnnotationsForEntity($entityName);
		
		$tmpEntity = null;
		$tmpEntityName = null;
		$tmpList = null;
		$tmpAnnotationName = null;
		
		foreach($fieldInfo as $field)
		{
			
			$tmpAnnotationName = array_shift(array_keys($field['annotations']));
			
			if($this -> request -> $field['name'] != ''){
			
				switch($tmpAnnotationName){

					case "MWCore\Annotation\Field":

						$entity -> $field['name'] = $this -> request -> $field['name'];
						break;

					case "MWCore\Annotation\OneToOne":
					case "MWCore\Annotation\ManyToOne":					

						$tmpEntityName = $field['annotations'][$tmpAnnotationName][0] -> entity;
						$tmpEntity = new $tmpEntityName;
						$tmpEntity -> id = $this -> request -> $field['name'];
						
						$entity -> $field['name'] = $tmpEntity;

						break;

					case "MWCore\Annotation\ManyToMany":		

						$entity -> $field['name'] = new MWCollection();	

						foreach($this -> request -> $field['name'] as $v){

							$tmpEntityName = $field['annotations'][$tmpAnnotationName][0] -> entity;
							$tmpEntity = new $tmpEntityName;
							$tmpEntity -> id = $v;

							$entity -> $field['name'] -> add($tmpEntity -> hydrate());

						}

						break;

					default:
						
						break;

				}			
				
			}		
			
		}
		
		return $entity;	
		
	}	

}