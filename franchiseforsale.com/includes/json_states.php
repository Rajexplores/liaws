<?php
$path = $_SERVER['DOCUMENT_ROOT'];
$path .= '/includes/branding.php';
include_once($path);
$site_id = $brand_array['site_id'];
if(strlen($_GET['state']) == 2){
    $state = $_GET['state'];
    $profile_results = '/'.$site_id.'/scripts/city_information.php?state='.$state;
    $return = get_json($profile_results,false);
    header('Content-Type: application/json');
    echo json_encode($return);
    echo $profile_results;
}else{
    die();
}
?>