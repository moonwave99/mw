<?php

namespace MWCore\Component\MWMailer;

use MWCore\Interfaces\MWSingleton;
use MWCore\Component\MWMailer\MWMailMessage;

class MWMailer implements MWSingleton
{
	
	private static $instance = null;
	
	protected $messages;
	
	public static function getInstance()
	{

		if(self::$instance == null)
		{   
			$c = __CLASS__;			
			self::$instance = new $c;
		}

		return self::$instance;
		
	}	
	
	private function __construct()
	{
		
		$this -> messages = array();
		
	}
	
	public function addMessage(MWMailMessage $message){ $this -> messages[] = $message; }
	
	public function sendAll()
	{
		
		$headers = NULL;
		
		foreach($this -> messages as $message)
		{

			$headers = $this -> createHeaders($message);

			mail(
				$headers['to'],
				$message -> getSubject(),
				$message -> getBody(),
				$headers['headers']
			);
			
		}
		
	}	

	protected function createHeaders(MWMailMessage $message)
	{
		
		$toList = "";
		$headers = "";
		$tempList = array();
		
		foreach($message -> getRecipients() as $key => $value)
		{

			$tempList[$key] = "";
			
			foreach($value as $recipient){
				
				if($key == "To")
					$toList .= $recipient.",";
				
				$tempList[$key] .= $recipient.",";

				
			}
			
			if( strlen($tempList[$key]) > 0 ){
				
				$tempList[$key] = $key.": ".substr($tempList[$key], 0, -1). "\r\n";
				
			}
			
		}
		
		// To send HTML mail, the Content-type header must be set
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

		// Additional headers
		$headers .= $tempList['To'];
		$headers .= 'From: '.$message -> getSenderName().' <'.$message -> getSenderAddress().'>' . "\r\n";
		$headers .= $tempList['Cc'];
		$headers .= $tempList['Bcc'];
		
		return array(
			"to" 		=> substr($toList, 0, -1),
			"headers"	=> $headers
		);		
		
	}	
	
}