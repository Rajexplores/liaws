<?php
//Checks for live or dev
$prefix = 'https://www.franchiseportals';
$api_url = 'https://franchiseinsights.franchiseportals.com';
$not_dev = true;
$global_robots = 'index, follow';
$subdomain_check = explode('.',$_SERVER['HTTP_HOST']);
if ($subdomain_check[0] != 'www'){
    $global_robots = 'noindex, nofollow';
}
if (!in_array($subdomain_check[0],['www','www2'])){
    $sub = 'www';
    if(!is_numeric($subdomain_check[0])){
        $sub = $subdomain_check[0];
    }
    $prefix = 'https://'.$sub.'.franchiseportals';    
    $api_url = 'https://'.$sub.'.franchiseinsights.franchiseportals.com';  
    $not_dev = false;
}

if(isset($_SERVER['HTTP_CF_CONNECTING_IP'])){
    $ipAddress = $_SERVER['HTTP_CF_CONNECTING_IP'];
}else if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
    $ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
}else{
    $ipAddress = $_SERVER['REMOTE_ADDR'];
}


if (isset($_GET['healthcheck']) || (isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/bot|crawl|curl|go-http-client|google|lighthouse|mediapartners|owler|site24x7|siteuptime|slurp|spider|trident/i', strtolower($_SERVER['HTTP_USER_AGENT']))) || isset($_GET['blog_include'])) {
    $geo = ['city'=>'corona','state'=>'ca','country'=>'us','country3'=>'usa','continent'=>6];
    setcookie('geo',json_encode($geo), time() + (1 * 24 * 60 * 60), "/", $matches[0] , $isSecure, true); //Expires in 1 day
}else if (isset($geo_ignore)) {
    //DO NOTHING
}else if (isset($_COOKIE['geo'])) {
    $geo = json_decode($_COOKIE['geo'],true);
}else{
    $geo = [];
    $netacuity = 'https://location.cloud.netacuity.com/webservice/query?u=66f61e33-5ced-48aa-bb1d-b159316cc9e7&ip=' . $ipAddress . '&dbs=all&trans_id=ou812v&json=true';
    if(@file_get_contents($netacuity)){
        $geoLocation = file_get_contents($netacuity);
        $geoLocation = json_decode($geoLocation, true); 
        $geo['city'] = $geoLocation['response']['pulse-city'];
        $geo['state'] = $geoLocation['response']['pulse-region'];
        $geo['country'] = $geoLocation['response']['pulse-two-letter-country'];
        $geo['country3'] = $geoLocation['response']['pulse-country'];
        $geo['continent'] = $geoLocation['response']['pulse-continent-code'];
        setcookie('geo',json_encode($geo), time() + (1 * 24 * 60 * 60), "/", $matches[0] , $isSecure); //Expires in 1 day
    }
}

$site_id = 5;
$website_id = 5;

if (isset($_SERVER['HTTP_REFERER'])) { 
    $relative_path = $_SERVER['HTTP_REFERER']; 
}else{
    $relative_path = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
}

//Replace special characters on contact forms
function replaceSpecialChars($string){
    return preg_replace('/[^ \w]+/', '', strip_tags($string));
}

//DEBUG
function debug($var){
	ob_start();
	echo '<pre>';
	print_r($var);
	echo '</pre>';
	ob_end_flush();
}

//Contact Form Section
function contact_form($type = 'contact_us'){
    //INCLUDE GLOBAL VARIABLES
    global $website_id,$ipAddress,$geo,$relative_path,$api_url;
    $unit = [
        '4'=>'Franchise Gator',
        '5'=>'Franchise Opportunities',
        '6'=>'Franchise Solutions',
        '7'=>'Franchise.com',
        '17'=>'BusinessBroker.net',
        '40'=>'Franchisecost.com',
        '47'=>'Small Business StartUP',
        '53'=>'Franchise Ventures',
        '55'=>'Franchise for Sale',
        '57'=>'FoodFranchise.com'
    ];

    //DECLARE DEFAULT VARIABLES
    $return = null;
    $url = $api_url.'/api/create_inquiry';
    $fields['url'] = $relative_path;
    $fields['inquiry_type'] = $type;
    $fields['site_id'] = $website_id;
    $fields['ip_address'] = $ipAddress;
    $field_checks = ['company','token'=>'recaptcha_token','city','address','country','site_name'=>'site_name'];

    //GEO FIELDS
    foreach($geo as $key => $value){
        if($key == 'continent'){
            continue;
        }
        if($key == 'city'){
            $value = ucwords($value);
        }else{
            $value = strtoupper($value);
        }
        if($key == 'country'){
            $key = 'geo_located_country';
        }
        if($key == 'country3'){
            $fields['country'] = strip_tags($value);
        }else{
            $fields[$key] = strip_tags($value);
        }
    }

    //GET AND CLEANUP PHONE
    if (isset($_REQUEST['visitor_phone'])) {
        $fields['phone'] = preg_replace("/[^0-9]/", "", $_REQUEST['visitor_phone'] );
    }

    //GET AND CLEANUP EMAIL ADDRESS
    if (isset($_REQUEST['visitor_email'])) {
        $fields['email'] = str_replace(array("\r", "\n", "%0a", "%0d"), '', $_REQUEST['visitor_email']);
        $fields['email'] = filter_var($fields['email'], FILTER_SANITIZE_EMAIL);
    }

    //GET AND CLEANUP NAME
    if (isset($_REQUEST['visitor_firstname']) || isset($_REQUEST['visitor_name'])) {
        if(isset($_REQUEST['visitor_name'])){
            $fields['name'] = $_REQUEST['visitor_name'];
        }else{
            $fields['name'] = $_REQUEST['visitor_firstname'];
        }
        $fields['name'] = filter_var($fields['name'], FILTER_SANITIZE_STRING);
        if(isset($_REQUEST['visitor_lastname'])){
            $fields['name'] .= ' '.filter_var($_REQUEST['visitor_lastname'], FILTER_SANITIZE_STRING);
        }
        $fields['name'] = preg_replace('/\d+/u', '', $fields['name']);
        $fields['name'] = replaceSpecialChars($fields['name']);
    }

    //GET AND CLEANUP EMAIL COMMENTS
    if (isset($_REQUEST['visitor_message'])) {
        $fields['message'] = strip_tags($_REQUEST['visitor_message']);
        if (isset($_REQUEST['visitor_reason'])) {
            $fields['message'] = 'Purpose For Email : '.$_REQUEST['visitor_reason'].' | '.$fields['message'];
        }
    }

    //LOOP THROUGH REMAINING VARIABLES
    foreach($field_checks as $key => $value){
        $temp_value = $value;
        if(is_numeric($key)){
            $key = $value;
            $temp_value = 'visitor_'.$value;
        }
        if(isset($_REQUEST[$temp_value])){
            $fields[$key] = strip_tags($_REQUEST[$temp_value]);
        }
    }

    //RETURN COPY TEXT AND SUBMIT INQUIRY
    if (preg_match("/.ru/i", $fields['email'])) { //IF RUSSIAN
        $return = 'Thank you for contacting us. You will get a reply within 24 hours.';
    }else if (filter_var($fields['email'], FILTER_VALIDATE_EMAIL)) {
        //RUN CURL
        $fields_string = http_build_query($fields);

        //open connection
        $ch = curl_init();
            
        //set the url, number of POST vars, POST data
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            
        //execute post
        $response = curl_exec($ch);
        $error = curl_error($ch);

        //close connection
        curl_close($ch);
        
        $status = json_decode($response,true);
        if ($status['status'] == 'success') { //IF SUCCESS
            if($type == 'advertise_with_us'){
                $return = 'Thank you for requesting more information about advertising with '.$unit[$website_id].'. Your information has been sent to a memeber of our Client Consultant Team and you can expect to be contacted shortly.';
            }else{
                $temp_name = '.';
                if(!is_null($fields['name'])){
                    $temp_name = ', '.$fields['name'].'.';
                }
                $return = 'Thank you for contacting us'.$temp_name.' You will get a reply within 24 hours.';
            }
        }else if($status['status'] == 'error'){
            if($status['message'] == 'timeout-or-duplicate'){ //IF DUPLICATE
                $return = 'A message with this email address has recently been submitted. Please try again at a later time.';
            }else{ //FALLBACK ERROR MESSAGE
                $return = 'There was a problem sending this message. Please try again later.';
            }
        }
    }else{ //IF EMAIL ADDRESS IS BAD
        $return = 'There was a problem with your email address. Please check your details and try again.';
    }
    return $return;
}
?>