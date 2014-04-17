<?php
namespace Classes;
/**
 * 
 * @author J.Christensen
 * @version 1.1 2014-04-17
 * @copyright GNU
 *
 */
if (!defined('ENVIRONMENT')) die('Direct access not permitted');

use Classes\FrmAppException as FrmAppException;
use Classes\FrmHelper as helper;
use Models\ContentTranslation;

abstract class FrmApp {
	private $_errors = array();
	private $_templateFolder = 'pages';
	private $_languageFolder = 'language';
	private $_lang;
	private $_trans = array();
	private $_languages = array();
	
	// Intern Use
	private $_website;
	private $_routing;
	private $_requestedPage = false;
	private $_getRequest = array();
	
	// Render Data, form, image...
	private $_renderData = array();

	/**
	 * Start Application,
	 * Area can be setted to load specific config
	 * @param string $area
	 */
	public function __construct($area=false) 
	{
		$this->_area = $area;
		$this->setEnvironment();
	}
	
	public function setRenderData($key, $data)
	{
		$this->_renderData[$key] = $data;
	}
	
	public function getErrors() {
		return $this->_errors;
	}

	public function getTranslations()
	{
		return $this->_trans;
	}
	
	public function getLang()
	{
		return $this->_lang;
	}
	
	public function getRouting()
	{
		return $this->_routing;
	}
	
	public function getRequestedPage()
	{
		return $this->_requestedPage;
	}
	
	public function getWebsite()
	{
		return $this->_website;
	}	

	/**
	 * For Server, Uri and query, language
	 * _request
	 */
	public function setEnvironment()
	{
		$sesStarted = (session_status() === PHP_SESSION_ACTIVE) ? true : false;
		if ( !$sesStarted ) session_start();
		
		$this->_languageFolder = DIRECTORY_SEPARATOR . '..'.DIRECTORY_SEPARATOR.$this->_languageFolder;
		$this->_lang = default_lang;
		
		$http = isset($_SERVER['HTTPS']) ? "https" : 'http';
		$uri = $_SERVER['REQUEST_URI'];
		$url = $http.'://'. $_SERVER['HTTP_HOST'] . $uri;
		$parsed = parse_url($url);
		$this->_website = $http.'://'.$parsed['host'];	
		
		// Clean query
		$query = array_filter(explode('/',$parsed['path']));
		if(!empty($query)) $query = array_slice($query, 0);
		
		// Request and language Language ADMIN
		if ( reset($query) == 'admin' ) {
			$this->_lang = 'en';
			// Routing File
			$this->loadRoutings();
			if ( isset($query[1]) )
				$this->_requestedPage = array_search($query[1], $this->_routing);
			if ( !$this->_requestedPage ) $this->_requestedPage = array_search('', $this->_routing);
		} else {	
			// Routing File
			$this->loadRoutings();
			// Language
			if ( isset($query[0]) && array_key_exists($query[0], $this->_languages) ){
				$this->_lang = $query[0];
				$_SESSION['userlanguage'] = $this->_lang;
				unset($query[0]);
				if(!empty($query)) $query = array_slice($query, 0);
			} elseif(isset($_SESSION['userlanguage']) && !empty($_SESSION['userlanguage'])) {
				$this->_lang = $_SESSION['userlanguage'];
			} else {
				$browserlang = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) : false;
				if ($browserlang && array_key_exists($browserlang, $this->_languages)) {
					$this->_lang = $browserlang;
					$_SESSION['userlanguage'] = $browserlang;
				}
			}
				
			// Page Query FrontPage
			$this->_getRequest = $query;
			$p = is_array($query) ? implode('/', $query) : '';
			if ( isset($this->_routing[$this->_lang]) && in_array($p, $this->_routing[$this->_lang]) ) {
				$this->_requestedPage = array_search($p, $this->_routing[$this->_lang]);
			}

			// Default page
			if ( empty($this->_requestedPage) ){
				$contentTranslated = new ContentTranslation();
				$translated = $contentTranslated->getByUrl($uri);
				if ( $translated ) {
					$this->setRenderData('translated', $translated);
					$this->_requestedPage = $translated->template;
					$this->_lang = $translated->getLanguage()->iso;
					$_SESSION['userlanguage'] = $this->_lang;					
				}
			}
			
		}
	}

	// Routing File
	public function loadRoutings()
	{
		if ( $this->_area )  {
			$file = __DIR__ . DIRECTORY_SEPARATOR .'..'. DIRECTORY_SEPARATOR. 'Config' . DIRECTORY_SEPARATOR . 'routing_'.$this->_area.'.php';
		} else {
			$file = __DIR__ . DIRECTORY_SEPARATOR .'..'. DIRECTORY_SEPARATOR. 'Config' . DIRECTORY_SEPARATOR . 'routing.php';
		}
		if (!is_file($file)) throw new FrmAppException('Routing file not found');
		include $file;
		$this->_routing = $_routing;
		$this->_languages = $_languages;
	}

	public function loadLanguages($page) {
		$dir = __DIR__ . DIRECTORY_SEPARATOR  . $this->_languageFolder . DIRECTORY_SEPARATOR . $this->_lang;
		if (!is_dir($dir))
			return false;

		include $dir . DIRECTORY_SEPARATOR . 'all.php';
		$this->_trans['all'] = $trans;

		if (is_file($dir . DIRECTORY_SEPARATOR . $page . '.php')) {
			include $dir . DIRECTORY_SEPARATOR . $page . '.php';
			$this->_trans['page'] = $trans;
		} else {
			include $dir . DIRECTORY_SEPARATOR . '404.php';
			$this->_trans['page'] = $trans;
		}

	}


	/**
	 * Render a template 
	 * @return html string
	 * @param string $template
	 * @param arrag key value for template output
	 */
	public function render($template, $data = array()) 
	{
		$_languages = $this->_languages;
		$_lang = $this->_lang;
		$_currentPage = $this->_requestedPage;
		
		$this->_renderData = array_merge($this->_renderData, $data);
		$App = $this;
		
		$templateFile = __DIR__ . DIRECTORY_SEPARATOR . '..'.DIRECTORY_SEPARATOR. $this->_templateFolder . DIRECTORY_SEPARATOR . $template . '.php';
		if (!file_exists($templateFile)) return ' <h3> template incorrecto </h3> ' . $templateFile;
		
		$string = '';
		ob_start();
		extract($this->_renderData);
		include $templateFile;
		$string = ob_get_contents();
		ob_end_clean();
		return $string;
	}

	public function getToken() {
		$ip = helper::getClientIpAddr();
		return sha1('SecurityToken_' . $ip);
	}
	
	/**
	 * Translate
	 * @param string $type ( msg or error ) 
	 */	
	public function translate($string, $type='msg')
	{
		$translations = $this->getTranslations();
		if ( isset($translations[$type]) && isset($translations[$type][$string]))
			return $translations[$type][$string];
		else
			return $string;		
	}
	
	
	/**
	 * URL generate
	 */
	public function url( $action, $lang=false, $params=false, $full = false)
	{
		$routing = $this->getRouting();
		$translations = $this->getTranslations();
		$website = $this->getWebsite();
		$lang = ($lang) ? $lang : $this->getLang();

		// Route
		$route = ( isset($routing[$lang]) && isset($routing[$lang][$action]) ) ? $routing[$lang][$action] : false;
		if ( !$route ){;
			$translation = new ContentTranslation();
			$translation = $translation->getUrlByContentNameAndLang($action, $lang);
			$route = ($translation) ? $translation->url : '#';
		} else {
			$route = '/'.$lang.'/'.$route;
		}
		
		if ( empty($params) ) {
			return ($full) ? $website.$route : $route;
		} else {
			if ( is_array($params)) {
				$str = '';
				foreach ($params as $k=>$v) $str .= $k.'/'.$v;
			} else {
				$str = $params;
			}
			return  ($full) ? $website.$route.'/'.$str : $route.'/'.$str;
		}
	
	}

}
