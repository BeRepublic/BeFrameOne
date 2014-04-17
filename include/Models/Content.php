<?php
/** 
 * @author jacob
 */
namespace Models;
use Classes\FrmModelBase;

class Content extends FrmModelBase
{
	const model = 'content';
	
	// This could go by database, for demo propouse that's not done.
	private $templates = array('simple','complete');

	protected $errors = array();	
	protected $translations = array();

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
			'template' => array('filter' => FILTER_VALIDATE_REGEXP, 
					'type' 		=> 'str',
					'required'	=> true,
					"options" 	=> array("regexp" => "(".implode('|', $this->templates).")")),
			'status' => array('filter' => FILTER_VALIDATE_REGEXP, 
					'type' 		=> 'str',
					'required'	=> true,
					"options" 	=> array("regexp" => "(draft|active|trash)")),
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
	
	public function getTemplates()
	{
		return $this->templates;
	}

    public function getAll()
    {
        $db = $this->frmPdo->prepare('SELECT * FROM '.self::model.' ORDER BY name');
        $db->setFetchMode(\PDO::FETCH_CLASS, __CLASS__);
        $result = $db->execute()->fetchAll();
        return $result;
    }    
	
	public function save(){
		$this->id = parent::saveObj(self::model);
		if ( $this->id && count($this->translations)>0){
			foreach ($this->translations as $translation){
				$translation->content_id = $this->id; 
				$trId = $translation->save();
				$language = $translation->getLanguage();
				if (!$trId) {
					if ( !isset($this->errors['contentTranslation'])){
						$this->errors['contentTranslation'] = array();
					}
					$this->errors['contentTranslation'][$language->iso] = $translation->getErrors();
				}
			}
		}
	}

    public function delete(){
        return parent::deleteById(self::model);
    }

    public function getPaginated($order='asc')
    {
    	$relations = array('translation');
        return parent::paginate($this->getFields(), self::model, __CLASS__, $relations);
    }
    
    public function getTranslation($locale=false)
    {
    	// no Content
    	if (!$this->id) return array(); 
    	
    	// in Memory
    	if ( isset($this->translations[$locale]) ) return $this->translations[$locale];   	
    	if ( !empty($this->translations) ) return $this->translations;
    	
    	if ( $locale ){
    		$query= 'SELECT ct.*
    				FROM content_translation ct 
    				INNER JOIN language l ON l.id=ct.language_id 
    				WHERE ct.content_id=:contentId AND l.iso=:locale 
    				ORDER BY ct.title LIMIT 1';
	    	$db = $this->frmPdo->prepare($query);
	    	$db->bindParam(':contentId', $this->id, \PDO::PARAM_INT);
	    	$db->bindParam(':locale', $locale, \PDO::PARAM_STR);
	    	$db->setFetchMode(\PDO::FETCH_CLASS, 'Models\ContentTranslation');
	    	$db->execute();
	    	$result = $db->fetch();
	    	$this->translations[$locale] = $result;
    	} else {
    		$db = $this->frmPdo->prepare('SELECT ct.* FROM content_translation ct WHERE ct.content_id=:contentId ORDER BY ct.title');
	    	$db->bindParam(':contentId', $this->id, \PDO::PARAM_INT);
    		$db->setFetchMode(\PDO::FETCH_CLASS, 'Models\ContentTranslation');
    		$db->execute();
    		$result = $db->fetchAll();
    		foreach ($result as $t) {
    			$lang = $t->getLanguage();    			
    			$this->translations[$lang->iso] = $t;
    		}
    	}
    	
    	return $this->translations;
    }
    
    /**
     * Translations
     * Mininmal need translation language
     */
    public function setTranslation(ContentTranslation $translation) 
    {
    	$language = $translation->getLanguage();
    	$locale = $language->iso;
    	$translation->content_id = $this->id;
    	$this->translations[$locale] = $translation;
    }
    
}
?>