<?php
$temp_url_check = $_SERVER['REQUEST_URI'];
$temp_url_check = strtok($temp_url_check, '?');

$pos = strpos($temp_url_check, '%20');
if ($pos !== false) {
    $temp_url_check = strtolower(htmlentities($temp_url_check)); 
    $temp_url_check = str_replace("%20", "-", $temp_url_check);
    $temp_url_check = str_replace(array("'","%27"), array("",""), $temp_url_check);
    header("HTTP/1.1 301 Moved Permanently"); 
    header("Location: ".$temp_url_check); 
    exit();
}
//echo $temp_url_check;die;
$redirects_301 = redirections(55);

if (array_key_exists($temp_url_check,$redirects_301)){
    header("HTTP/1.1 301 Moved Permanently"); 
    header("Location: ".$redirects_301[$temp_url_check]); 
    exit();
}
?>