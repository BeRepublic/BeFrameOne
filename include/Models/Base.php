<?php
namespace Models;
/**
 * 
 * @author jacob
 * 
 */
use Classes\FrmModel;

class Base
{
	protected $_model;
	protected $_class;

	protected $_db;
	protected $_DbModel;

	public function start($_class)
	{
		$this->_class = $_class;
		$this->_DbModel = FrmModel::getInstance();
		$this->_db = $this->_DbModel->getDb();
	}
	
	public function getId()
	{
		return isset($this->id) ? $this->id : false;
	}


	public function getById( $id )
	{
		$query = "SELECT * 
			FROM $this->_model 
			WHERE id=:id
			LIMIT 1";
		$db = $this->_db->prepare($query);
		$db->bindParam(':id', $id, \PDO::PARAM_INT);
		$db->setFetchMode(\PDO::FETCH_CLASS, $this->_class);
		$db->execute();
		return $db->fetch();
	}
	
	public function getByName( $name )
	{
		$query = "SELECT *
		FROM $this->_model
		WHERE name=:name
		LIMIT 1";
		$db = $this->_db->prepare($query);
		$db->bindParam(':name', $name, \PDO::PARAM_STR);
		$db->setFetchMode(\PDO::FETCH_CLASS, $this->_class);
		$db->execute();
		$obj = $db->fetch();
		return ($obj) ? $obj : new $this->_class();
	}
	
	/**
	 * update Quantity
	 */
	public function updateQuantity()
	{
		$query = "UPDATE $this->_model set quantity=quantity+1 where id=:id";
		$db = $this->_db->prepare($query);
		$db->bindParam(':id', $this->id, \PDO::PARAM_INT);
		return $db->execute();
	}
	
	/**
	 *  get top
	 */
	public function getTop($limit=5)
	{
		$query = "SELECT *
			FROM $this->_model
			ORDER BY quantity DESC
			LIMIT :limit";
		$db = $this->_db->prepare($query);

		$db->setFetchMode(\PDO::FETCH_CLASS, $this->_class);
		$db->bindParam(':limit', $limit, \PDO::PARAM_INT);
		$db->execute();
		$result =  $db->fetchAll();
		return ($result) ? $result : array();
	}
	
}