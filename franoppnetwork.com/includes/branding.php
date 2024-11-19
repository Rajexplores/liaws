<?php
//Get JSON from API
function get_json($url,$pet = true){
    global $api_url;
    $request_URL = $api_url.$url;
    $ch = curl_init();  
 
    curl_setopt($ch,CURLOPT_URL,$request_URL);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_HEADER, false);  
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
 
    $output=curl_exec($ch);
 
    curl_close($ch);
    $array = json_decode($output,true);
    return $output;
}
?>