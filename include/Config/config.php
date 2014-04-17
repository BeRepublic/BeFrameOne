<?php
if(!defined('ENVIRONMENT')) die('Direct access not permitted');

// Website
define('default_lang','en');

// Global
define('session_name', 'SomeSessionName');

// this should be by database
define('config_admin_user', 'admin');
define('config_admin_pass', 'admin');

// Database
if ( ENVIRONMENT != 'DEVEL' ) {
	define('_db_host', 'localhost');
	define('_db_name', 'beframeone');
	define('_db_user', 'beframeone');
	define('_db_pass', 'beframeone');
}else{
	define('_db_host', 'localhost');
	define('_db_name', 'beframeone');
	define('_db_user', 'beframeone');
	define('_db_pass', 'beframeone');
}

