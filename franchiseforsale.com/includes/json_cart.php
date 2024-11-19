<?php
$path = $_SERVER['DOCUMENT_ROOT'];
$path .= '/includes/branding.php';
include_once($path);
$website_id = $brand_array['website_id'];
if(strlen($_GET['type'])){
$fields = [
        'type' => $_GET['type'],
        'landing_id' => htmlspecialchars($_COOKIE['landing_id'], ENT_QUOTES),
        'session_id' => htmlspecialchars($_COOKIE['udid'], ENT_QUOTES),
        'fbo_id' => $_GET['fbo_id']
    ];
    if(isset($_COOKIE['email'])){
        $fields['email_address'] =  $_COOKIE['email'];
    }
    if($_GET['type'] ==  'add'){
        $fields['position'] = $_GET['position'];
        $fields['status'] = 'A';
        $fields['udid'] = htmlspecialchars($_COOKIE['udid'], ENT_QUOTES);
        $fields['min_capital'] = $_GET['min_capital'];
        $fields['name'] = $_GET['name'];
        $fields['rate'] = $_GET['rate'];
        $fields['page_url'] = $_GET['page_url'];
        $fields['page_type'] = $_GET['page_type'];
    }
    $prefix = 'https://www.franchiseportals';
$subdomain_check = explode('.',$_SERVER['HTTP_HOST']);
if (!in_array($subdomain_check[0],['www','www2'])){
        $prefix = 'https://dev.franchiseportals';    
    }
    $url = $prefix.'.com/'.$website_id.'/cart.php';
    //echo json_encode($fields,true);
    //url-ify the data for the POST
    $fields_string = http_build_query($fields);

    //open connection
    $ch = curl_init();

    //set the url, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

    //So that curl_exec returns the contents of the cURL; rather than echoing it
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 

    //execute post
    $result = curl_exec($ch);
    $returned = json_decode($result,true);
}
echo json_encode($returned);
?>