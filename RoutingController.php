<?php

/**
 * Created by PhpStorm.
 * User: lokendra.surya
 * Date: 7/15/16
 * Time: 5:16 PM
 */

require_once 'ApiFunctions.php';
class RoutingController
{
    public function getController($path){
        $url=strtok($_SERVER["REQUEST_URI"],'?');
        if($url=="/signUp"){
            return "signUpPage.php";
        }else if (strpos($url, '/api/v1/') !== false)
        {
            $endPoint = end(explode('/api/v1/', $url));
            $apiFunctions =  new ApiFunctions();
            $apiFunctions->processAPI($endPoint,$_REQUEST);
        }else
            return "loginPage.php";
    }
}