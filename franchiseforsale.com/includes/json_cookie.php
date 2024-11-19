<?php
// Cookie variables to return
$return = [
    'email' => null,
    'landing_id' => null,
    'quiz_udid' => null,
    'udid' => null
];
foreach($return as $key => $value){
    if(isset($_COOKIE[$key])){
        $return[$key] = $_COOKIE[$key];
    }
}
header('Content-Type: application/json; charset=utf-8');
echo json_encode($return);