<?php
/** 
 * @author jacob
 */
namespace Models;
use Classes\FrmModelBase;

class Language extends FrmModelBase
{
	const model = 'language';

	protected $errors = array();	

	public function getFields() 
	{
		return array(
			'id'	=> array('filter' => FILTER_VALIDATE_REGEXP, 
						'type' 		=> 'int',
						'required'	=> false,
						"options" => array("regexp" => "/^[0-9]{0,10}+$/")),
			'name' 	=> array('filter' => FILTER_VALIDATE_REGEXP, 
						'type' 		=> 'str',
						'required'	=> true,
						"options" 	=> array("regexp" => "/^.{3,150}$/")),
			'iso' 	=> array('filter' => FILTER_VALIDATE_REGEXP, 
						'type'		=> 'str',
						'required'	=> false,
						"options"	=> array("regexp" => "/^.{0,5}$/")),
            'created_at' 	=> array('filter' => FILTER_VALIDATE_REGEXP,
                'type' 		=> 'str',
                'required'	=> false,
                "options" 	=> array("regexp" => "/^.{3,150}$/")),
		);
	}

	/**
	 * 
	 * @param int $id
	 * @return this class object
	 */
	public function getById( $id )
	{
		return $this->byId( $id, self::model, __CLASS__ );
	}

    public function getAll()
    {
        $db = $this->frmPdo->prepare('SELECT * FROM '.self::model.' ORDER BY name');
        $db->setFetchMode(\PDO::FETCH_CLASS, __CLASS__);
        $result = $db->execute();
        return $db->fetchAll();
    }
	
	public function save(){
		return parent::saveObj(self::model);
	}

    public function delete(){
        return parent::deleteById(self::model);
    }

    public function getPaginated()
    {
        return parent::paginate($this->getFields(), self::model, __CLASS__);
    }
}
?>