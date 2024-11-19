<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');
$return = $_SERVER;
if($_GET['json']){
    $return = array();
    $json = [
        'HTTP_CLOUDFRONT_VIEWER_COUNTRY_REGION_NAME' => 'region',
        'HTTP_CLOUDFRONT_VIEWER_COUNTRY_REGION' => 'region_code',
        'HTTP_CLOUDFRONT_VIEWER_CITY' => 'city',
        'HTTP_CLOUDFRONT_VIEWER_POSTAL_CODE' => 'postal_code',
        'HTTP_CLOUDFRONT_VIEWER_COUNTRY_NAME' => 'country',
        'HTTP_CLOUDFRONT_VIEWER_LATITUDE' => 'latitude',
        'HTTP_CLOUDFRONT_VIEWER_LONGITUDE' => 'longitude',
        'HTTP_CLOUDFRONT_VIEWER_ADDRESS' => 'ip_address',
        'HTTP_SEC_CH_UA_PLATFORM' => 'os'
    ];
    $platforms = [
        'HTTP_CLOUDFRONT_IS_MOBILE_VIEWER' => 'mobile',
        'HTTP_CLOUDFRONT_IS_TABLET_VIEWER' => 'tablet',
        'HTTP_CLOUDFRONT_IS_SMARTTV_VIEWER' => 'smart tv',
        'HTTP_CLOUDFRONT_IS_DESKTOP_VIEWER' => 'desktop',
        'HTTP_CLOUDFRONT_IS_IOS_VIEWER' => 'ios',
        'HTTP_CLOUDFRONT_IS_ANDROID_VIEWER' => 'android'
    ];
    foreach($json as $key => $value){
        $return[$value] = $_SERVER[$key];
    }
    $return['platform'] = null;
    foreach($platforms as $key => $value){
        if($_SERVER[$key] == true){
            $return['platform'] = $value;
            break;
        }
    }

}
echo json_encode($return);