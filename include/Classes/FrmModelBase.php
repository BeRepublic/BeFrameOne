<?php
namespace Classes;
/**
 * 
 * @author jacob
 *  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
 *  `name` varchar(150) NOT NULL,
 *  `iso` varchar(4) DEFAULT NULL,
 *  `created_at` timestamp
 */

Abstract class FrmModelBase
{
	private $frmPdo;
	
	protected $attributes = array();
	
	public function __construct() {
	}

    public function __get($key)
    {
    	if ( !isset($this->$key) && $key == 'frmPdo' ) {
    		$this->getFrmPdo();
    	} 
        return isset($this->$key) ? $this->$key : false;
    }

    public function __set($key, $value)
    {
        $this->$key = $value;
    }
    
    private function getFrmPdo()
    {
    	if ( $this->frmPdo ) return $this->frmPdo;
    	$this->frmPdo = FrmPdo::getInstance();
    	return $this->frmPdo;
    } 

	
	/**
	 * If Errors in saving or other
	 * @return array of errors:
	 */
	public function getErrors()
	{
		return $this->errors;
	}
	
	public function byId( $id, $model, $class )
	{
		
		$db = $this->getFrmPdo()->prepare('SELECT * FROM '.$model.' WHERE id=:id');
		$db->bindValue(":id", $id, \PDO::PARAM_INT);
		$db->setFetchMode(\PDO::FETCH_CLASS, $class);
		$result = $db->execute();
		return $db->fetch();
	}

    /**
     * delete register
     * @param $model
     */
    public function deleteById($model)
    {
        $id = isset($this->id) ? (int) $this->id : false;
        
        if(!$id) return false;
        
        $query = 'DELETE FROM '.$model.' WHERE id=:id';
        $db = $this->getFrmPdo()->prepare($query);
        $db->bindValue(":id", $id, \PDO::PARAM_INT);

        $result = $db->execute();

        if ( !$result ) {
            $this->errors['database'] = $this->getFrmPdo()->errorInfo();
            return false;
        }

        return true;

    }
	
	/**
	 * Save
	 */
	 public function saveObj( $model )
	 {	
	 	$fields = $this->getFields();
	 	$id = isset($this->id) ? (int) $this->id : false;
	 	$saveData = array();
	 	foreach ($fields as $key=>$options) {
	 		if ( isset($this->$key) ) $saveData[$key] = $this->$key;
	 	}
	 	
		$myinputs = filter_var_array($saveData, $fields);
		
		foreach ($myinputs as $field=>$value){
			if ( !$value && $fields[$field]['required']) $this->errors[$field] = $field.' is a required field';
		}
			
		// Return false if errors
		if (!empty($this->errors)) return false;

		// unset ID
		if(isset($myinputs['id'])) unset($myinputs['id']);
		
		// Update or New
		if (!$id) {			
			$formatQ = "INSERT INTO {$model} (%1s) values(%2s);";
			$createdAt = new \DateTime();
			$myinputs['created_at'] = $createdAt->format('Y-m-d H:i:s');
			
			$query = sprintf($formatQ,  implode(",",array_keys($myinputs)), ':'.implode(', :',array_keys($myinputs)) );			 
		} else {
			$query = "UPDATE {$model} SET ";
			$qArr = array();
			if ( isset($myinputs['created_at']) ) unset($myinputs['created_at']);
			foreach ($myinputs as $field=>$value){
				$qArr[] = "{$field}=:{$field}";
			}
			$query .= implode(', ',$qArr)." WHERE id=:id";
		}
		
		
		$db = $this->getFrmPdo()->prepare($query);
	
		foreach ($myinputs as $field=>$value) {
			if ($fields[$key]['type'] == 'int'){
				$db->bindValue(":$field", $value, \PDO::PARAM_INT);
			} else {
				if (empty($value)) {
					$db->bindValue(":$field", NULL, \PDO::PARAM_NULL);
				} else {
					$db->bindValue(":$field", $value, \PDO::PARAM_STR);
				}
			}	
		}
		
		if ($id) {
			$db->bindValue(":id", $id, \PDO::PARAM_INT);
		}
		
		$result = $db->execute();
		
		if ( !$result ) {
			$this->errors['database'] = $this->getFrmPdo()->errorInfo();
			return false;
		}

		$this->id = ($id) ? (int) $id : (int) $this->getFrmPdo()->lastInsertId();
		return $this->id;

	 }

    public function paginate($columns, $modelName, $className, $relations=array())
    {
    	$search = filter_input(INPUT_POST, 'sSearch', FILTER_SANITIZE_STRING);
    	$displayStart = filter_input(INPUT_POST, 'iDisplayStart', FILTER_SANITIZE_NUMBER_INT);
    	$displayLength = filter_input(INPUT_POST, 'iDisplayLength', FILTER_SANITIZE_NUMBER_INT);
    	$order = filter_input(INPUT_POST, 'sSortDir_0', FILTER_SANITIZE_STRING); 
    	
        $rtn = array('total'=>0,'data'=>array());
        // seteamos los valores de inicio/fin para buscar registros
        $displayStart = ($displayStart) ? (int)$displayStart : 0;
        $displayLength = ($displayLength) ? (int)$displayLength : 10;

        $searchSql = '';
        if ( !empty($search) ){
            $searchSql =  ' WHERE name LIKE "%:name%"';
        }

        $query = 'SELECT '.$modelName.'.* FROM '.$modelName
        		.$searchSql.
        		' ORDER BY '.$modelName.'.id '.$order.
        		' LIMIT '.$displayStart.','.$displayLength;

        $db = $this->getFrmPdo()->prepare($query);

        $db->setFetchMode(\PDO::FETCH_CLASS, $className);

        if ( !empty($search) ){
            $db->bindParam(':name', $search, \PDO::PARAM_STR);
        }

        $db->execute();
        $results = $db->fetchAll();

        $trtn = array();
        foreach ($results as $k=>$result){
            foreach($columns as $column=>$value)
            {
                $trtn[$k][$column] = $result->$column;
            }
            // If Relations
            foreach ($relations as $relation){
            	$get = 'get'.ucfirst($relation);
            	$trtn[$k][$relation] = $result->$get();
            }
        }

        $count = count($trtn);
        return array(
    			"sEcho"=>filter_input(INPUT_POST, 'sEcho', FILTER_SANITIZE_NUMBER_INT),
    			"iTotalRecords"=>$count,
    			"iTotalDisplayRecords"=> ($count) ? $this->getTotal($modelName) : $count,
    			"aaData"=> $trtn
    	);
    }

    public function getTotal($model)
    {
        $query = 'SELECT count(id) as total FROM '.$model;

        $db = $this->getFrmPdo()->prepare($query);
        $db->execute();
        $result = $db->fetchColumn();
        return ($result) ? $result : 0;
    }
}
?>