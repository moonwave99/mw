<?php

namespace MWCore\Kernel\MWQueryBuilder;

use MWCore\Entity\MWEntity;
use MWCore\Kernel\MWDBManager;
	
class MWQueryBuilder
{

	protected $entityname;
	
	protected $query;
	
	protected $type;
	
	protected $columnList;

	protected $joinList;
	
	protected $where;
	
	protected $order;
	
	protected $limit;

	public function __construct()
	{
		$this -> columnList = array();
		$this -> joinList = array();
		$this -> where = array();	
	}
	
	public function build()
	{

		switch($this -> type){
			
			case 'SELECT':
			
				foreach($this -> joinList as $join)
				{

					$this -> query .= sprintf(
						" INNER JOIN %s ON %s = %s ",
						$join['jointable'],
						$join['field_from'].".id",
						$join['jointable'].".id_".$join['field_from']
					);
					
				}
								
				break;
			
			case 'INSERT':
			
				$values = "";
			
				$this -> query .="(";

				foreach($this -> columnList as $column)
				{
					$this -> query .= $column['name'].", ";					
					$values .= ":".$column['name'].", ";
				}

				$this -> query = substr($this -> query, 0, -2).")";
				$this -> query .= " VALUES(". substr($values, 0, -2) . ")";
			
				break;
				
			case 'UPDATE':
			
				$values = "";

				foreach($this -> columnList as $column)
				{

					$this -> query .= $column['name']." = :". $column['name'].", ";					
					
				}

				$this -> query = substr($this -> query, 0, -2);		
			
				break;
			
		}
		
		// render WHERE
		if(count($this -> where) > 0){

			$this -> query .= " WHERE ";
			
			foreach($this -> where as $i => $w)
			{
				
				( $i > 0 ) && $this -> query .= sprintf(" %s ", $w['boolean']);

				$this -> query .= sprintf($w['mode'] == 'MD5' ? "MD5(%s.%s) %s %s" : "%s.%s %s %s", 
					$w['table'] == "" ? MWEntity::getTableNameFromClass( $this -> entityname ) : $w['table'],
					$w['field'],
					$w['operator'],
					$w['value'] == NULL ? ":".$w['field'] : ":".$w['value']
				);
				
			}
			
		}
		
		// render ORDER BY
		if($this -> order != NULL){
		
			$this -> query .= sprintf(
				" ORDER BY %s.%s %s",
				!isset($this -> order['table']) ? MWEntity::getTableNameFromClass( $this -> entityname ) : $this ->order['table'],
				$this -> order['column'],
				$this -> order['order']
			);
		
		}				
		
		// render LIMIT
		if($this -> limit != NULL){
			
			$this -> query .= sprintf(" LIMIT %d, %d", $this -> limit['start'], $this -> limit['range']);
			
		}	
		
		return $this -> query;			
		
	}
	
	public function selectFrom($entityname)
	{

		$this -> type = "SELECT";
		
		$this -> entityname = $entityname;
		
		$this -> query = sprintf(
			"SELECT %s.* FROM %s",
			MWEntity::getTableNameFromClass( $entityname ),
			MWEntity::getTableNameFromClass( $entityname )
		);

		return $this;
		
	}
	
	public function insertInto($entityname)
	{
		
		$this -> type = "INSERT";
		
		$this -> entityname = $entityname;		
		
		$this -> query = sprintf(
			"INSERT INTO %s ",
			MWEntity::getTableNameFromClass( $entityname )
		);		
		
		return $this;		
		
	}
	
	public function update($entityname)
	{
		
		$this -> type = "UPDATE";
		
		$this -> entityname = $entityname;	
		
		$this -> query = sprintf(
			"UPDATE %s SET ",
			MWEntity::getTableNameFromClass( $entityname )
		);			
		
		return $this;		
		
	}
	
	public function deleteFrom($entityname)
	{
		
		$this -> entityname = $entityname;		
		
		return $this;		
		
	}
	
	public function addColumn($name, $value, $type)
	{
		
		$this -> columnList[] = array(
			'name'	=> $name,
			'value' => $value,
			'type'	=> $type
		);
		
		return $this;
		
	}
	
	public function innerjoin($jointable, $field_from, $field_to = NULL)
	{

		$this -> joinList[] = array(
			'jointable'		=> $jointable,
			'field_from'	=> MWEntity::getTableNameFromClass($field_from),
			'field_to'		=> $field_to != NULL ? MWEntity::getTableNameFromClass($field_to) : NULL
		);
		
		return $this;
		
	}
	
	public function where($field, $operator, $value = NULL, $table = NULL)
	{

		$this -> where[] = array(
			'field'		=> $field,
			'operator'	=> $operator,
			'value'		=> $value,
			'table'		=> $table,
			'boolean'	=> 'AND'
		);
		
		return $this;		
		
	}
	
	public function orWhere($field, $operator, $value = NULL, $table = NULL)
	{

		$this -> where[] = array(
			'field'		=> $field,
			'operator'	=> $operator,
			'value'		=> $value,
			'table'		=> $table,
			'boolean'	=> 'OR'
		);
		
		return $this;		
		
	}
	
	public function whereMD5($field, $operator, $value = NULL, $table = NULL)
	{

		$this -> where[] = array(
			'field'		=> $field,
			'operator'	=> $operator,
			'value'		=> $value,
			'table'		=> $table,
			'mode'		=> 'MD5'
		);
		
		return $this;		
		
	}	

	public function order($column, $order, $table = NULL)
	{
	
		$this -> order = array(
			'column'	=> $column,
			'order'		=> $order,
			'table'		=> $table
		);		
		
		return $this;		
		
	}
	
	public function limit($start, $range)
	{
		
		$this -> limit = array(
			'start'	=> $start,
			'range'	=> $range	
		);
		
		return $this;		
		
	}	
	
}