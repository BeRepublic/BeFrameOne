<?php
namespace Models;
/**
 * 
 * @author jacob
 * 
 */

class Domain extends Base
{
	protected $_model = 'service_domain';

	public function __construct()
	{
		parent::start(__CLASS__);
	}

	
	/**
	 * Save
	 */
	 public function save()
	 {	 	
	 	$query = "INSERT INTO $this->_model (name, service_domain_type_id) values(:name, :type)";	 	
	 	$db = $this->_db->prepare($query);
	 	$db->bindParam(':name', $this->name, \PDO::PARAM_STR);
	 	$db->bindParam(':type', $this->service_domain_type_id, \PDO::PARAM_INT);
	 	$result = $db->execute();
	 	return ($result) ? (int) $this->_db->lastInsertId() : false;
	 }
	 			
}
?>