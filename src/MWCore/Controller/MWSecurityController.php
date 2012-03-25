<?php

namespace MWCore\Controller;

use MWCore\Controller\MWController;

class MWSecurityController extends MWController
{
	
	public function loginAction()
	{
		
		$this -> requestView('MWCore\View\Pages\login', array(
			'title'		=> 'Login page.',
			'message'	=> $this -> request -> error !== NULL ? "Please check your username and password." : "Please login."
		));
		
	}
	
	public function loginCheckAction()
	{
		
		// Request should be in POST method and the csrf token should match
		($this -> request -> getMethod() != 'POST' || $this -> csrfCheck() !== true) &&	$this -> redirect(MW_LOGIN_PATH);			
		
		$repName = $this -> inspector -> getRepositoryNameForEntity(MW_LOGIN_ENTITY);
		$rep = new $repName();
		$user = $rep -> findOneByField('username', $this -> request -> username );

		$user === false && $this -> redirect(MW_LOGIN_PATH."?error");
		
		($user -> checkPassword($this -> request -> password) === false && $user -> enabled == 1)
			&& $this -> redirect(MW_LOGIN_PATH."?error");
		
		$this -> session -> regenerate();
		$this -> session -> set('logged', true);				
		
		$user -> password = NULL;
		$user -> salt = NULL;
		
		$this -> session -> set('user', $user);				

		$this -> redirect(MW_LOGIN_ENTRANCE);
			
	}
	
	public function logoutAction()
	{
		
		$this -> session -> destroy();
		
		$this -> redirect(MW_LOGIN_PATH);
		
	}
	
	public function captchaImageAction($seed)
	{
		
	    $string = encodeSeed($seed);
	
	    $pic = imagecreatefromjpeg(SRC_PATH . "MWCore/Resources/Images/captcha.jpg");
	    $color = imagecolorallocate($pic, 0, 0, 0);
		

		$fonts = glob(SRC_PATH . 'MWCore/Resources/Fonts/*.ttf');
		
		for( $i = 0; $i < strlen($string); $i++ )
		{
			
			imagettftext(
				$pic,
				34,
				rand(-20,20),
				30 * $i + 2,
				45,
				$color,
				$fonts[rand(0, count($fonts) -1)],
				$string[$i]
			);			
			
		}				

	    header("Content-type: image/jpeg");
	    imagejpeg($pic);
		imagedestroy($pic);
		
	}
	
	public function captchaSeedAction()
	{
		
		$this -> json( array('seed' => generateSeed()) );
		
	}
	
}