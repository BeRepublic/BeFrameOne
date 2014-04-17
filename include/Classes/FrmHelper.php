<?php
namespace Classes;

if(!defined('ENVIRONMENT')) die('Direct access not permitted');

class FrmHelper
{

	/**
	 * user ip address
	 * @return string
	 */
	public static function getClientIpAddr() {
		$ipaddress = '';
	     if (isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP']))
	         $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
	     else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR']))
	         $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
	     else if(isset($_SERVER['HTTP_X_FORWARDED']) && !empty($_SERVER['HTTP_X_FORWARDED']))
	         $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
	     else if(isset($_SERVER['HTTP_FORWARDED_FOR']) && !empty($_SERVER['HTTP_FORWARDED_FOR']))
	         $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
	     else if(isset($_SERVER['HTTP_FORWARDED']) && !empty($_SERVER['HTTP_FORWARDED']))
	         $ipaddress = $_SERVER['HTTP_FORWARDED'];
	     else if(isset($_SERVER['REMOTE_ADDR']) && !empty($_SERVER['REMOTE_ADDR']))
	         $ipaddress = $_SERVER['REMOTE_ADDR'];
	     else
	         $ipaddress = '0.0.0.0';

	     return $ipaddress;
	}



	/**
	 * Modifies a string to remove all non ASCII characters and spaces.
	 */
	static public function slugify($str, $cleanMsg=false, $replace=array(), $delimiter='-', $allowSlash=false)
	{
		setlocale(LC_ALL, 'en_US.UTF8');
		if( !empty($replace) ) {
			$str = str_replace((array)$replace, ' ', $str);
		}

		$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
		if($cleanMsg){
			$clean = preg_replace("/[^a-zA-Z0-9\[\]. -]/", '', $clean);
			setlocale(LC_ALL, 'es_ES.UTF8');
			return $clean;
		}
		$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);		
		$clean = strtolower(trim($clean, '-'));
		
		if ( $allowSlash ){
			setlocale(LC_ALL, 'es_ES.UTF8');
			return $clean;
		}
		
		$clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
		setlocale(LC_ALL, 'es_ES.UTF8');
		return $clean;
	}


    /**
     * Formatear string de fechas
     *
     * @param $str_datetime
     * @param bool $str_format
     * @return bool|string
     */
    static public function getFormatDatetime($str_datetime, $str_format=false)
    {
        $time = strtotime($str_datetime);
        $format = ($str_format) ? $str_format : 'd/m/Y - H:i:s';

        if( !date($format, $time) ){
             return $str_datetime;
        }

        return date($format, $time);
    }
    
    
    /**
     * Getting kind of page render
     * @return string
     */
    public static function getOutputType()
    {
    	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    		return 'AJAX';
    	} else {
    		return 'HTML';
    	}
    }

}
?>