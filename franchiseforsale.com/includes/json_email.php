<?php
$path = $_SERVER['DOCUMENT_ROOT'];
$path .= '/includes/branding.php';
include_once($path);
if(strlen($_REQUEST['email_address'])){
    $return = array();
    $return['email'] = $sanitized_a = filter_var($_REQUEST['email_address'], FILTER_SANITIZE_EMAIL);
    $api_url = 'https://franchiseinsights.franchiseportals.com';
    if (strpos($_SERVER['SERVER_NAME'], "franchiseportals") !== false){
        $api_url = 'https://pet.franchiseportals.com';    
    }
    $towerdata = '/api/tower_data/'.$sanitized_a;
    $array = get_json($towerdata);
    $array = json_decode($array,true);
    if(is_array($array['data']['email_corrections'])){
        if(!is_null($array['data']['email_corrections'][0])){
            $return['email'] = $array['data']['email_corrections'][0];
        }
    }
    header('Content-Type: application/json');
    echo json_encode($return);
}else{
    die();
}
?>