<?php
namespace Classes;

class FrmPdo extends \PDO
{
	protected $_pdo;

	private static $instance;

	public static function getInstance()
	{
		if (  !self::$instance instanceof self)
		{
			try{
				$options = array(
				    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
				    \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
				  );
				self::$instance = new self('mysql:host='._db_host.';dbname='._db_name, _db_user, _db_pass, $options );
			}catch (\PDOException $e){
				die('POD: No puedo conectarme a la base de datos! <!--'.$e->getMessage().'-->');
			}catch (\Exception $e){
				die('Error: '.$e->getMessage());
			}
		}
		return self::$instance;
	}
	
	public function __destruct() {
		$this->_pdo = null;
	}

}