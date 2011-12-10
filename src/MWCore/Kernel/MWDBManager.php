<?php

namespace MWCore\Kernel;

use MWCore\Interfaces\MWSingleton;

class MWDBManager implements MWSingleton
{
	
	private static $instance = null;
	
	protected $pdo;
	
	protected $queryCount = 0;
	
	static $dateFormat = 'Y-m-d H:i:s';
	
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

		$this -> pdo = new \PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
		
	}
	
	public function getQueryNumber(){ return $this -> queryNumber; }
	
	public function getDBData($query, $params = NULL)
	{

		$statement = $this -> pdo -> prepare($query);
		
		if(isset($params))
		{
			
			foreach($params as $i => $p)
			{
				
				$statement -> bindValue(':'.$p['field'], $p['value'], $p['type']);
				
			}
			
		}
	
		$statement -> setFetchMode(\PDO::FETCH_ASSOC);
		$statement -> execute();
		
		if(DEBUG === true){

			\MWCore\Kernel\MWLog::getInstance() -> add( $statement -> errorInfo() );
			$this -> queryNumber ++;	
			
		}
		
		return $statement -> fetchAll();
		
	}
	
	public function setDBData($query, $params = NULL)
	{

		$statement = $this -> pdo -> prepare($query);  

		if(isset($params))
		{
			
			foreach($params as $i => $p)
			{
				
				$statement -> bindValue(':'.$p['field'], $p['value'], $p['type']);
				
			}
			
		}	
		
		$statement -> execute();
		
		if(DEBUG === true){

			\MWCore\Kernel\MWLog::getInstance() -> add( $statement -> errorInfo() );
			$this -> queryNumber ++;	
			
		}

		return $this -> pdo ->lastInsertId();	
		
	}
	
}