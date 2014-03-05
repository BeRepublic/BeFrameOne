<?php
if(!defined('ENVIRONMENT')) die('Direct access not permitted');

// Website
define('default_lang','en');

// Global
define('session_name', 'SomeSessionName');

// Database
if ( ENVIRONMENT != 'DEVEL' ) {
	define('_db_host', 'localhost');
	define('_db_name', 'testdb');
	define('_db_user', 'testuser');
	define('_db_pass', 'somepwd');
}else{
	define('_db_host', 'localhost');
	define('_db_name', 'poker_service');
	define('_db_user', 'root');
	define('_db_pass', '');
}

