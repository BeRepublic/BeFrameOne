<?php
/** 
 * @author jacob
 */
namespace Models;
use Classes\FrmModelBase;
use Classes\FrmHelper;

class ContentTranslation extends FrmModelBase
{
	const model = 'content_translation';

	protected $errors = array();
	protected $language;

	public function getFields() 
	{
		return array(
			'id'	=> array('filter' => FILTER_VALIDATE_REGEXP, 
					'type' 		=> 'int',
					'required'	=> false,
					"options" => array("regexp" => "/^[0-9]{0,10}+$/")),
			'content_id'	=> array('filter' => FILTER_VALIDATE_REGEXP, 
					'type' 		=> 'int',
					'required'	=> true,
					"options" => array("regexp" => "/^[0-9]{0,10}+$/")),
			'language_id'	=> array('filter' => FILTER_VALIDATE_REGEXP,
					'type' 		=> 'int',
					'required'	=> true,
					"options" => array("regexp" => "/^[0-9]{0,4}+$/")),
			'url' 	=> array('filter' => FILTER_SANITIZE_STRING,
					'type'		=> 'str',
					'required'	=> true),
			'md5url' 	=> array('filter' => FILTER_SANITIZE_STRING,
					'type'		=> 'str',
					'required'	=> true),
			'title' 	=> array('filter' => FILTER_VALIDATE_REGEXP, 
					'type'		=> 'str',
					'required'	=> true,
					"options"	=> array("regexp" => "/^.{0,255}$/")),
			'body' 	=> array('filter' => FILTER_SANITIZE_STRING, //  FILTER_UNSAFE_RAW
					'type'		=> 'str',
					'required'	=> false),
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
        $result = $db->execute()->fetchAll();
        return $result;
    }
	
	public function save(){
		$this->url = FrmHelper::slugify($this->url,false, array(), '-', true);
		$this->md5url = md5($this->url);
		return parent::saveObj(self::model);
	}

    public function delete(){
        return parent::deleteById(self::model);
    }

    public function getPaginated($displayStart, $displayLength, $name=false, $order='asc')
    {
        return parent::paginate($displayStart, $displayLength, $name, $this->getFields(), self::model, __CLASS__, $order);
    }
    
    public function setLanguage(Language $language)
    {
    	$this->language = $language;
    	$this->language_id = $language->id;
    }
    
    public function getLanguage()
    {
    	if ( $this->language )  return $this->language;
    	$language = new Language();
    	return $language->getById($this->language_id);
    }
    
    
    /**
     * Return ONE translation
     * @param unknown $url
     * @return unknown
     */
    public function getByUrl($uri)
    {
    	$md5url = md5($uri);
    	
    	$db = $this->frmPdo->prepare('SELECT ct.*, c.template FROM '.self::model.' ct INNER JOIN content c ON c.id=ct.content_id WHERE ct.md5url=:md5url AND c.status="active" LIMIT 1');
    	$db->bindParam(':md5url', $md5url, \PDO::PARAM_STR);
    	$db->setFetchMode(\PDO::FETCH_CLASS, __CLASS__);
    	$db->execute();
    	
    	return $db->fetch();
    }
    
    /**
     * Return ONE translation
     * @param unknown $url
     * @return unknown
     */
    public function getUrlByContentNameAndLang($contentName, $iso)
    {
    	$qery = 'SELECT ct.* FROM '.self::model.' ct 
    			INNER JOIN content c ON c.id=ct.content_id 
    			INNER JOIN language l ON ct.language_id=l.id
    			WHERE c.name=:contentname 
    			AND c.status="active" 
    			AND l.iso=:iso
    			LIMIT 1';
    	 
    	$db = $this->frmPdo->prepare($qery);
    	$db->bindParam(':contentname', $contentName, \PDO::PARAM_STR);
    	$db->bindParam(':iso', $iso, \PDO::PARAM_STR);
    	$db->setFetchMode(\PDO::FETCH_CLASS, __CLASS__);
    	$db->execute();
    	return $db->fetch();
    }
}
?>