<?php
$path = $_SERVER['DOCUMENT_ROOT'];
$path .= '/includes/branding.php';
include_once($path);
$site_id = $brand_array['site_id'];
    if(strlen($_REQUEST['validate']) && $_REQUEST['validate'] == 'true'){
        $prefix = 'https://www.franchiseportals';
        $subdomain_check = explode('.',$_SERVER['HTTP_HOST']);
        if ($subdomain_check[0] != 'www'){
            $prefix = 'https://'.$subdomain_check[0].'.franchiseportals';   
        }
        $profile_results = $prefix.'.com/'.$site_id.'/form-validator.php?';
        foreach($_REQUEST as $key => $value) {
            if($value == 'on'){
                $value = 1;
            }
            $profile_results .= $key.'='.str_replace(' ', '+', $value).'&';
        }
        $profile_results .= "preferred_state=&source_type=web";
        $profileServices = file_get_contents($profile_results);
        echo $profileServices;
    }else{
        die();
    }
?>