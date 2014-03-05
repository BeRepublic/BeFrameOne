<?php
/**
 * @Autor: Jacob Christensen
 */

// Load classes
require dirname(__FILE__).'/../include/boostrap.php';

// Start Application
$app = new App();


// Page to Load
$page = $app->getRequestedPage();
$app->loadLanguages($page);

$mainLayout = 'layout';
$content='';

// Content Render
switch($page){
	case 'home':
		$token = $app->getToken();	
		$content = $app->render('home');
		break;
	case 'faq':
		$content = $app->render('faq');
		break;
	default:
		$content = $app->render('error404');		
}

// Render Outpur
if ( 'html' == $app->getOutputMethod() ) {
	echo $app->render($mainLayout, array('_content' => $content));
} else {
	header('Content-Type: application/json');
	echo json_encode($content);
}
?>