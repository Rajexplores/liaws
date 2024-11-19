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

function post_json($url,$pet = true){
    global $api_url;
    $request_URL = $api_url.$url;
    $ch = curl_init();  
 
    curl_setopt($ch,CURLOPT_URL,$request_URL);
    curl_setopt($ch,CURLOPT_POST, true);

    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
 
    $output=curl_exec($ch);
 
    curl_close($ch);
    $array = json_decode($output,true);
    return $array;
}

$brand_array = [
    'brand_name'=>'Franchises For Sale',
    'site_id'=>'5',
    'website_id'=>'55',
    'description'=>'',
    'robots'=>'noindex, nofollow',
    'home_page'=>'default',
    'logo_link' => false,
    'home_page_title'=>'Find a Franchise',
    'contact_address' => 'info@franchiseventures.com,it@franchiseventures.com',
    'prop' => 'whitelabel',
    'prop_short' => 'wl',
    'google_id' => '',
    'lrq' => true
];
$directory_array = [
    'about-us'=>[
        'title'=>'About Us',
        'long_title'=>'About Us'
    ],
    'contact-us'=>[
        'title'=>'Contact Us',
        'long_title'=>'Contact Us'
    ],
    'landing'=>[
        'title'=>'Top Franchises',
        'long_title'=>'Top Franchises'
    ],
    'privacy-policy'=>[
        'title'=>'Privacy Policy',
        'long_title'=>'Privacy Policy'
    ]
];
$footer_array = [];
?>