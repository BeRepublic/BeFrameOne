<?php
date_default_timezone_set('Europe/Madrid');
define('ENVIRONMENT', 'DEVEL');

// Config File
include __DIR__.'/Config/config.php';

// Autoloader Class
include __DIR__.'/Classes/FrmAutoloader.php';

spl_autoload_register(array('Classes\FrmAutoloader' , 'myAutoloader'));


// USE this in file to prevent direct access
/*
 *
if(!defined('ENVIRONMENT')) die('Direct access not permitted');

or
<?php if(!defined('ENVIRONMENT')) die('Direct access not permitted'); ?>

*/