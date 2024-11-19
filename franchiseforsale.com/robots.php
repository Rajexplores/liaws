<?php
$path = $_SERVER['DOCUMENT_ROOT'];
$path .= '/includes/branding.php';
include_once($path);
$api_url = 'https://franchiseinsights.franchiseportals.com';
$subdomain_check = explode('.',$_SERVER['HTTP_HOST']);
$is_dev = false;
if ($subdomain_check[0] != 'www'){
    $api_url = 'https://'.$subdomain_check[0].'.franchiseinsights.franchiseportals.com';  
    $is_dev = true;
}
$website_id = $brand_array['website_id'];
$robots = get_json('/api/robots?site_id='.$website_id);
$robots = json_decode($robots,true);
$rule = $robots['data'][0]['rule'];
$breaks = array("<br />","<br>","<br/>","<br />","&lt;br /&gt;","&lt;br/&gt;","&lt;br&gt;");  
$rule = str_ireplace($breaks, "\r\n", $rule); 
header("Content-Type: text/plain");
$subdomain_check = explode('.',$_SERVER['HTTP_HOST']);
if ($is_dev){
    $rule = 'User-agent: *
    Disallow: /';
}
echo $rule;
?>