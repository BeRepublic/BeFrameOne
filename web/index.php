<?php
use Classes\FrmHelper;
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

$mainLayout = 'front/layout';
$content='';

// Content Render
switch($page){
	case 'home':
		$token = $app->getToken();	
		$content = $app->render('front/home');
		break;
	case 'faq':
		$content = $app->render('front/faq');
		break;
	case 'simple':
		$content = $app->render('front/simple');
		break;
	case 'complete':
		$content = $app->render('front/complete');
		break;
	default:
		$content = $app->render('error/404');		
}

// Render Outpur
if ( 'HTML' == FrmHelper::getOutputType() ) {
	echo $app->render($mainLayout, array('_content' => $content));
} else {
	header('Content-Type: application/json');
	echo json_encode($content);
}
?>