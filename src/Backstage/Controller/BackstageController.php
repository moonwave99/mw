<?php

namespace Backstage\Controller;

use MWCore\Controller\MWController;
use Backstage\Component\BackstageProvider;

class BackstageController extends MWController
{

	protected $helper;

	public function __construct()
	{

		$this -> helper = \Backstage\Component\BackstageProvider::getHelper();
		
	}

	public function switchAction($section, $action = 'index')
	{
		
		$controller = BackstageProvider::makeCrudController(sprintf("Backstage\Controller\%sController", ucwords($section)));
		
		if($controller === false || !method_exists($controller, $action."Action")) \MWCore\Kernel\MWRouter::requestNotFound();
		
		call_user_func(
			array($controller, $action."Action")
		);		
		
	}
	
	public function indexAction()
	{

		$this -> requestView("Backstage\View\index", array(
			'pageTitle'			=> 'MW | Backstage.',
			'title'				=> 'This is the backstage.',
			'nav'				=> $this -> helper -> getNavigationEntries($this -> context -> getUser() -> role),
			'editSettingsForm'	=> $this -> helper -> createEditSettingsForm($this -> settings)
		));		

	}
	
	public function profileAction()
	{

		$this -> requestView("Backstage\View\profile", array(
			'pageTitle'			=> 'MW | Profile.',
			'title'				=> 'This is your profile page.',
			'nav'				=> $this -> helper -> getNavigationEntries($this -> context -> getUser() -> role),
			'editSettingsForm'	=> $this -> helper -> createEditSettingsForm($this -> settings)					
		));		

	}	
	
	public function saveSettingsAction()
	{

		if($this -> request -> getMethod() != 'POST' || $this -> csrfCheck() !== true)
			exit;
			
		foreach($this -> settings -> getAll() -> toArray() as $s)
		{
	
			$s -> value = $this -> request -> {$s -> key};
			$s -> update();
			
		}
		
		return $this -> json(array(
			'status'	=> 'OK',
			'message'	=> 'Saved succesfully!'
		));		
		
	}	
	
}