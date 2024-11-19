<?php
$path = $_SERVER['DOCUMENT_ROOT'];
$path .= '/includes/branding.php';
include_once($path);
$site_id = $brand_array['site_id'];
    if(strlen($_GET['idlist']) == 5 && is_numeric($_GET['idlist'])){
        $prefix = 'https://www.franchiseportals';
        $_REQUEST['dev-live'] = explode(".",$_SERVER['SERVER_NAME']);
        if ($_REQUEST['dev-live'][0] == 'dev'){
            $prefix = 'https://dev.franchiseportals';    
        }
        if($_REQUEST['dev-live'][1] == 'whitelabeldev') {
            $prefix = 'http://www.franchiseportalsdev';
        } 
        $profile_results = $prefix.'.com/'.$site_id.'/searchservices_new.php?name=fboid&idlist='.$_GET['idlist'].'&platform_type=WEB&webfilter=yes';
        $profileServices = file_get_contents($profile_results);
        $profileServices = str_replace("&#2013266094;","&reg;",$profileServices);
        echo $profileServices;
    }else{
        die();
    }
?>