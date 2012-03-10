<?php

namespace Backstage\Controller;

use Backstage\Controller\CrudController;
use Backstage\Library\UploadHandler;

class PictureController extends CrudController
{

	public function __construct()
	{
		
		parent::__construct("App\Entity\Picture", "Picture");

	}	

	public function indexAction()
	{

		parent::indexAction();
		
	}
	
	public function getAction()
	{

		parent::getAction();
		
	}
	
	public function listAction()
	{	

		parent::listAction();
		
	}
	
	public function saveAction()
	{
	
		if($this -> request -> getMethod() != 'POST' || $this -> csrfCheck() !== true)
			exit;

		$entity = $this -> _bindRequest();

		($this -> request -> id != 0) ? $entity -> update() : $entity -> create();
		
		@rename(TMP_UPLOAD_FOLDER . $entity -> src, UPLOAD_FOLDER . $entity -> src);		

		return $this -> json(array(
			'status'	=> 'OK',
			'message'	=> 'Saved succesfully!'
		));

	}
	
	public function deleteAction()
	{
		
		parent::deleteAction();	
		
	}
	
	public function uploadAction()
	{

		header('Pragma: no-cache');
		header('Cache-Control: no-store, no-cache, must-revalidate');
		header('Content-Disposition: inline; filename="files.json"');
		header('X-Content-Type-Options: nosniff');
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: OPTIONS, HEAD, GET, POST, PUT, DELETE');
		header('Access-Control-Allow-Headers: X-File-Name, X-File-Type, X-File-Size');
			
		$uh = new UploadHandler(array(
			
			'upload_dir'		=> TMP_UPLOAD_FOLDER,
            'image_versions'	=> array(
	
				'thumbnail'		=> array(
                	'upload_dir' => THUMBNAIL_FOLDER,
                	'upload_url' => BASE_PATH . '/thumbnails/',
                	'max_width' => 80,
                	'max_height' => 80
				)
				
            )

		));

		$uh -> post();
		
	}	
		
}