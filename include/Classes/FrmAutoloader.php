<?php
namespace Classes;
/**
 * 
 * @author J.Christensen
 * @version 0.1a
 * @copyright
 *
 */
class FrmAutoloader
{	
	/**
	 * Auto load classes
	 */
	public static function myAutoloader($class)
	{		
        $fls = FrmLoadedFiles::getInstance();
		if ( $fls->fileExists($class) ) return true;
		
		$file = __DIR__."/../$class.php";
		$file = str_replace('\\', '/', $file);
		if ( file_exists($file))
			include $file;	
	}
	
}

/**
 * To make sure files onle included once.
 */
class FrmLoadedFiles
{
	private $_loadedFiles = array();
	private static $instance;
	
	public static function getInstance()
	{
		if (  !self::$instance instanceof self)
		{
			self::$instance = new self;
		}
		return self::$instance;
	}
	
	public function fileExists($class)
	{
		if ( in_array($class, $this->_loadedFiles) ) return true;
		$this->_loadedFiles[] = $class;
	}
}

?>