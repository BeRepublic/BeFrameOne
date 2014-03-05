<?php
namespace Classes;

class FrmModel
{
	protected $_db;
	protected $_model;

	private static $instance;

	/**
	 */
	public function __construct()
	{
		$this->setConfig();
	}

	public static function getInstance()
	{
		if (  !self::$instance instanceof self)
		{
			self::$instance = new self;
		}
		return self::$instance;
	}

	private function setConfig()
	{
		$this->_db = new \PDO('mysql:host='._db_host.';dbname='._db_name.'', _db_user, _db_pass);
	}

	public function getDb() {
		return $this->_db;
	}
	
	public function __destruct() {
		$this->_db = null;
	}
}