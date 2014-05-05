<?php
/**
 * Cache Control Class
 * A simple class that writes arrays to file
 * Use it when big queries.
 * Builds files as $name-strtotime.frc serielize
 * 
 *	$cache = new FrmCache();
 *	$data = array('some1'=>'bla','some2'=>'bla2');
 *  calling save will delete files with same name
 *	$cache->save('some', $data);
 *	$data = $cache->get('some', 3600);
 *
 * using data model
 * $data = $cache->getModelData('Models\Language', 'getAll', 3600);
 * with params
 * $tc = $cache->getModelData('Models\ContentTranslation', 'getByUrl', 3600, array('uri'=>'/en/this-is-my-life'));
 * and from here, fx, $lang = $tc->getLanguage(); echo $lang->name;
 */
namespace Classes;

Class FrmCache
{
	private $cache_dir = 'cache';
	private $cacheData;
	
	public function __construct() {
		// Cache active
		if ( frm_cache ) {
			// Verify writable directory
			$this->cache_dir = __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.$this->cache_dir;
			if ( !is_dir($this->cache_dir) || !is_writable($this->cache_dir)) {
				throw new FrmAppException('Cache directory is not writeable! '. $this->cache_dir);
			}			
		}
	}
	
	/**
	 * Return cached data or false
	 * You can recover cached if no time is passed
	 * and file exists. 
	 * @param string $name
	 * @param int $expire ( seconds )
	 */
	public function get($name, $expire=false) 
	{
		$file = $this->getFilesByName($name, false, $expire);
		
		if ( !$file || !is_file($file)) return false;
		
		$content = file_get_contents($file);
			
		return unserialize($content);
	}
	
	/**
	 * Save cache to file
	 * @param string $name
	 * @param array $data
	 */
	public function save($name, $data) {
		$str = serialize($data);
		$filename = $this->cache_dir.DIRECTORY_SEPARATOR.$name.'-'.strtotime('now').'.frc';
		// Get existing files to be deleted
		$existingFiles = $this->getFilesByName($name);
		if ( $existingFiles ) 
			foreach ($existingFiles as $eFile ) unlink($eFile);
		
		file_put_contents($filename, $str);
	}
	
	/**
	 * Special Model data cache function
	 * @param string $modelName
	 * @param string $modelFunction
	 * @param int $expire ( seconds )
	 * @param array $params ( explode to function ) 
	 */
	public function getModelData($modelName, $modelFunction, $expire, $params = array())
	{
		if ( frm_cache ) {
			$cacheName = $modelName.'-'.$modelFunction;
			if (!empty($params)) {
				$keys = implode('-', array_keys($params));
				$values = implode('-', array_values($params));
				$cacheName .= '-'.$keys.'-'.$values;
			}		
			$cacheName = FrmHelper::slugify($cacheName);
			
			// Find Cache
			$cache = $this->get($cacheName, $expire);
			if ( $cache ) return $cache;
		}	
		
		// Run model
		try {
			$model = new $modelName();
		} catch (Exception $e) {
			throw new FrmAppException('Cache Model not found: '.$modelName);
		}
		
		// Make request
		try {
			$data = call_user_func_array(array($model, $modelFunction), $params );
		} catch (Exception $e) {
			throw new FrmAppException('Cache Model function not found: '.$modelFunction);
		}
		
		if ( empty($data) ) return array();
		
		// Save Cache
		if ( frm_cache ) $this->save($cacheName, $data);		
		
		return $data;
	} 
	
	/**
	 * Clear all cached files
	 * @return boolean|number
	 */
	public function clearCache()
	{
		$fileName = $this->cache_dir.DIRECTORY_SEPARATOR.'*.frc';
		$files = glob($fileName);
		if ( empty($files) ) return false;
		foreach ( $files as $file ){
			unlink($file);
		}
		return count($files);
	}
	
	/**
	 * Simple intern fnction too get cache files
	 * @param string $name
	 * @param bool $all
	 * @param int seconds $expire
	 * @return boolean|string $file(s)
	 */
	private function getFilesByName($name, $all=true, $expire = false)
	{
		$fileName = $this->cache_dir.DIRECTORY_SEPARATOR.$name.'-*.frc';
		$files = glob($fileName);
		if ( empty($files) ) return false;
		
		$lastest = array();
		foreach ( $files as $file ){
			$elements = explode(DIRECTORY_SEPARATOR, $file);
			$fName = end($elements);
			$time = (int) $this->between($fName, $name.'-', 'frc');
			if($time && (!$expire || $time >= (strtotime('now')-$expire) ) )
				$lastest[$time] = $file;
		}
		
		if ( empty($lastest)) return false;
		
		// sort by lastest, not relying on "file glob".
		krsort($lastest);
		
		// if just one lastest file
		return ($all) ? $lastest : reset($lastest);	
		
	}
	
	/**
	 * get value between 2 strings
	 * @param string $src
	 * @param string $start
	 * @param string $end
	 * @return string
	 */
	private function between($src,$start,$end){
		$re = '/'.$start.'(.*).'.$end.'/is';
		if (preg_match($re, $src, $match)) {
			return isset($match[1]) ? $match[1]: false;
		}
		return false;
	}
}