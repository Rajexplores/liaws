<?php
$path = $_SERVER['DOCUMENT_ROOT'];
$path .= '/includes/branding.php';
include_once($path);
$site_id = $brand_array['website_id'];
    if(strlen($_GET['list']) && preg_match("/^\d+(?:,\d+)*$/", $_GET['list'])){
        $prefix = 'https://www.franchiseportals';
        $_REQUEST['dev-live'] = explode(".",$_SERVER['SERVER_NAME']);
        if (strpos($_SERVER['SERVER_NAME'], "franchiseportals") !== false){
            $prefix = 'https://dev.franchiseportals';    
        }
        $profile_results = $prefix.'.com/'.$site_id.'/addressCheck.php?fbolist='.$_GET['list'];
        $profileServices = file_get_contents($profile_results);
        echo $profileServices;
    }else{
        die();
    } 
?>