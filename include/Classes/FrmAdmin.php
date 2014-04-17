<?php
namespace Classes;

use Classes\FrmHelper as helper;

if(!defined('ENVIRONMENT')) die('Direct access not permitted');

class FrmAdmin
{
    private $SESNAME = '_admin_';


    /**
     * Login
     */
    public function getUser($app)
    {
        // IP ACCESS, you can block by ip address admin access
        /*$userIp = helper::getClientIpAddr();
        $permitedIp = (array) json_decode(config_admin_ips);
        if ( !$userIp || !in_array($userIp, $permitedIp) ) {
            $app->setRenderData('error_ip', 'You are not allowed to access');
            return false;
        }*/

        // fast return if session is started
        if ( isset($_SESSION[$this->SESNAME]) && $_SESSION[$this->SESNAME] ){
            return $_SESSION[$this->SESNAME];
        }

        // If post to login
        $user = filter_input(INPUT_POST, '_user', FILTER_SANITIZE_STRING);
        $pass = filter_input(INPUT_POST, '_pass', FILTER_SANITIZE_STRING);

        if ($user && $pass){
            if ( $user==config_admin_user && $pass==config_admin_pass ) {
                $_SESSION[$this->SESNAME] = $user;
                return $user;
            } else {
                $app->setRenderData('error_ip', 'Incorrect Access');
            }
        }
        return false;
    }
    /**
     * Logout
     */
    public function logout()
    {
        if ( isset($_SESSION[$this->SESNAME]) && $_SESSION[$this->SESNAME] ){
            unset($_SESSION[$this->SESNAME]);
        }
    }

}
?>