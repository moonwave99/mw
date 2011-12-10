<?php

namespace MWCore\Component\MWMailer;

class MWMailMessage
{
	
	protected $senderName;
	
	protected $senderAddress;
	
	protected $subject;
	
	protected $recipients;
	
	protected $body;
	
	public function __construct()
	{
		
		$this -> recipients = array(
			"To"	=> array(),
			"Cc"	=> array(),
			"Bcc"	=> array()
		);
		
	}
	
	public function setSenderName($senderName){ $this -> senderName = $senderName; }
	public function getSenderName(){ return $this -> senderName; }
	
	public function setSenderAddress($senderAddress){ $this -> senderAddress = $senderAddress; }
	public function getSenderAddress(){ return $this -> senderAddress; }		
	
	public function setSubject($subject){ $this -> subject = $subject; }
	public function getSubject(){ return $this -> subject; }
	
	public function addRecipient($address, $mode = "To" ){ $this -> recipients[$mode][] = $address; }
	public function getRecipients(){ return $this -> recipients; }
	
	public function setBody($body){ $this -> body = $body; }
	public function getBody(){return $this -> body; }

	
}