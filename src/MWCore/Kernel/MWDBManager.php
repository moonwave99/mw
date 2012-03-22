<?php

/**
*	Part of MW - lightweight MVC framework.
*	@author Diego Caponera <diego.caponera@gmail.com>
*	@link https://github.com/moonwave99/mw
*	@copyright Copyright 2011-2012 Diego Caponera
*	@license http://www.opensource.org/licenses/mit-license.php MIT License
*	@package MWCore/Kernel
*/

namespace MWCore\Kernel;

use MWCore\Interfaces\MWSingleton;

/**
*	MWDbManager Class - handles db connection, data fetching and putting.
*/
class MWDBManager implements MWSingleton
{
	
	/**
	*	Singleton instance field
	*	@access private
	*	@var MWDBManager
	*/	
	private static $instance = null;
	
	/**
	*	Good ol' PDO instance
	*	@access protected
	*	@var PDO
	*/	
	protected $pdo;
	
	/**
	*	Query counter
	*	@access protected
	*	@var int
	*/	
	protected $queryCount = 0;
	
	/**
	*	MySQL - compliant datetime format
	*	@access public
	*	@var string
	*/	
	static $dateFormat = 'Y-m-d H:i:s';
	
	/**
	*	Returns unique instance of current class
	*	@access public
	*	@return MWDBManager
	*/	
	public static function getInstance()
	{

		if(self::$instance == null)
		{   
			$c = __CLASS__;			
			self::$instance = new $c;
		}

		return self::$instance;
		
	}	
	
	/**
	*	Default constructor, private for design purposes
	*	@access private
	*/	
	private function __construct()
	{

		$this -> pdo = new \PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
		
	}
	
	/**
	*	Query number getter
	*	@access public
	*	@return int
	*/	
	public function getQueryNumber(){ return $this -> queryNumber; }
	
	/**
	*	Returns and array with query results
	*	@param string $query The query being used
	*	@param array $params Query params
	*	@return array
	*/
	public function getDBData($query, $params = array())
	{

		$statement = $this -> pdo -> prepare($query);

		foreach($params as $i => $p)
		{
			
			$statement -> bindValue(':'.$p['field'], $p['value'], $p['type']);
			
		}
	
		$statement -> setFetchMode(\PDO::FETCH_ASSOC);
		$statement -> execute();
		
		if(DEBUG === true){

			\MWCore\Kernel\MWLog::getInstance() -> add( array($query, $statement -> errorInfo(), $params) );
			$this -> queryNumber ++;	
			
		}
		
		$results = $statement -> fetchAll();
		
		return $results;
		
	}
	
	/**
	*	Executes update/insert query, and returns last inserted item id in case
	*	@param string $query The query being used
	*	@param array $params Query params
	*	@return int
	*/	
	public function setDBData($query, $params = array())
	{

		$statement = $this -> pdo -> prepare($query);  
			
		foreach($params as $i => $p)
		{
			
			$statement -> bindValue(':'.$p['field'], $p['value'], $p['type']);
			
		}
		
		$statement -> execute();
		
		if(DEBUG === true){

			\MWCore\Kernel\MWLog::getInstance() -> add( array($query, $statement -> errorInfo(), $params) );
			$this -> queryNumber ++;	
			
		}

		return $this -> pdo ->lastInsertId();	
		
	}
		
}