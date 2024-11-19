<?php
    $api_url = 'https://franchiseinsights.franchiseportals.com';

    $subdomain_check = explode('.',$_SERVER['HTTP_HOST']);
    if (!in_array($subdomain_check[0],['www','www2'])){
        $sub = 'www';
        if(!is_numeric($subdomain_check[0])){
            $sub = $subdomain_check[0];
        }
        $api_url = 'https://'.$sub.'.franchiseinsights.franchiseportals.com';  
    }
    $path = $_SERVER['DOCUMENT_ROOT'];
    $path .= '/includes/branding.php';
    include_once($path);
    $website_id = $brand_array['website_id'];
    // $url = '/api/guidant/'.$site_id;
    $url = '/api/get_data_checkbox?site_id='.$website_id;
    $array = get_json($url);
    $array = json_decode($array);
    header('Content-Type: application/json');
    echo json_encode($array);
    die;
?>