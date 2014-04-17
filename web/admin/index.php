<?php
/**
 * @Autor: Jacob Christensen
 */

// Load classes
require dirname(__FILE__).'/../../include/boostrap.php';
use Classes\FrmHelper as helper;
use Models\Language;
use Models\Content;
use Models\ContentTranslation;

// Start Application
$app = new App('admin');
$appAdmin = new Classes\FrmAdmin();

// Page to Load
$user = $appAdmin->getUser($app);
if (!$user) $page='login';
else $page = $app->getRequestedPage();

$mainLayout = 'admin/layout';
$content='';
$_header = true;

// Output type
$_headerOutput = helper::getOutputType();

// Content Render
switch($page){
    //LOGIN & LOGOUT
	case 'login':
        $_header = false;
		$token = $app->getToken();	
		$content = $app->render('admin/login', array('token' => $token));
		break;
    case 'logout':
        $_header = false;
        $appAdmin->logout();
        $token = $app->getToken();
        $content = $app->render('admin/login', array('token' => $token));
        break;

    //CONTENT
	case 'content':
        if($_headerOutput == 'AJAX' ) {
            $model = new Content();
            $content = $model->getPaginated();
        } else {
            $content = $app->render('admin/content');
        }
		break;


    //LANGUAGES
    case 'language':
        if($_headerOutput == 'AJAX' ) {
            $model = new Language();
            $content = $model->getPaginated();
        } else {
            $content = $app->render('admin'.DIRECTORY_SEPARATOR.'language');
        }
        break;
        
    case 'language_form':
        $errors = array();
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $language = new Language();

        if ( $_POST ) {
        	$data = filter_input_array(INPUT_POST, $language->getFields());        	        	
        	foreach ($data as $input=>$value)
        		$language->$input = trim($value);        	
        	        	
            $id = $language->save();
			$errors = $language->getErrors();            
        }
        
        if($id)  $language = $language->getById($id);
        
        $content = $app->render('admin/language_form', array('language' => $language, 'errors' => $errors ));
        break;
    case 'language_delete':
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if($id)
        {
            $lang = new Language();
            $lang = $lang->getById($id);
            if($lang)
                $lang->delete();

            header( 'Location: '.$app->getWebsite().'/admin/language.html' ) ;
        }
        break;

    //OFFICES
    case 'content':
        if($_headerOutput == 'AJAX' ) {
            $content = new Content();
            $content = $content->getPaginated();
        } else {
            $content = $app->render('admin/content');
        }
        break;
    case 'content_form':
    	$content = new Content();
    	$language = new Language();
    	
        $errors = array();
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);    
        if($id)  $content = $content->getById($id);
        
        
        if ( $_POST && $content ) {
        	$postLanguages = isset($_POST['languages']) ? $_POST['languages'] : array();
        	$postContent = isset($_POST['content']) ? $_POST['content'] : array();
        	$postContentTranslation = isset($_POST['contentTranslation']) ? $_POST['contentTranslation'] : array();
        	
        	$data = filter_var_array($postContent, $content->getFields());    
        	    	        	
        	foreach ($data as $input=>$value)
        		$content->$input = trim($value);

        	// delete translations if not required
        	if ( $content->id ) {
        		$actualTranslations = $content->getTranslation();
        		foreach ($actualTranslations as $trans){
        			if ( !in_array($trans->language_id, $postLanguages)){
        				echo 'sdelete: '.$trans->id.'<br>';
        			}
        		}
        	}
        	
        	// Translations
        	foreach ($postLanguages as $plangId){
        		$lang = $language->getById($plangId);
        		if (!$lang) continue;
        		$translation = new ContentTranslation();
        		$translation->setLanguage($lang);
        		// If there is post for this
        		$posted = isset($postContentTranslation[$lang->iso]) ? $postContentTranslation[$lang->iso] : array();
        		foreach ($posted as $k=>$v) $translation->$k = $v;
        		// set to content
        		$content->setTranslation($translation);
        	}
        	
        	// Save All     	
            $id = $content->save();
			$errors = $content->getErrors();    

        }
        
        $languages = $language->getAll();
        
        $content = $app->render('admin/content_form', array('content' => $content, 'errors' => $errors, 'languages' => $languages ));
        break;
    case 'content_delete':
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if($id)
        {
            $content = new Content();
            $content = $content->getById($id);
            if($content)
                $content->delete();

            header( 'Location: '.$app->getWebsite().'/admin/content.html' ) ;
        }
        break;
	default:
		$content = $app->render('error/404');		
}

// Render Outpur
if ( 'HTML' == $_headerOutput ) {
    if($_header)
        $_header = $app->render('admin/_header', array('page' => $page));

	echo $app->render($mainLayout, array(
        '_content' => $content,
        '_header' => $_header
    ));
} else {
	header('Content-Type: application/json');
	echo json_encode($content);
}

?>