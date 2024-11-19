<?php
/*--------------------------------------------------*/
/*------------------DO NOT CHANGE-------------------*/
// If you need to make changes to any of these files, please verify with others first.
// This file is in sync between the following websites:
// BBN, FF, FCOM, FFS, FG, FO, FS, SBS
// This will help when migrating all websites onto a singular tool.
/*--------------------------------------------------*/
$host = $_SERVER['HTTP_HOST'];
preg_match("/[^\.\/]+\.[^\.\/]+$/", $host, $matches);
$isSecure = false;
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
    $isSecure = true;
}else if(!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on'){
    $isSecure = true;
}
$subscribed = ['status'=>'failure'];
$path = $_SERVER['DOCUMENT_ROOT'];
$path .= '/includes/branding.php';
include_once($path);
$website_id = $brand_array['website_id'];
$prefix = 'https://www.franchiseportals';
$api_url = 'https://franchiseinsights.franchiseportals.com';
$subdomain_check = explode('.',$_SERVER['HTTP_HOST']);
if (!in_array($subdomain_check[0],['www','www2'])){
    $sub = 'www';
    if(!is_numeric($subdomain_check[0])){
        $sub = $subdomain_check[0];
    }
    $api_url = 'https://'.$sub.'.franchiseinsights.franchiseportals.com';  
    $prefix = 'https://'.$sub.'.franchiseportals';  
}
if(strlen($_REQUEST['subscribe'])){
    $sanitized_a = filter_var($_REQUEST['subscribe'], FILTER_SANITIZE_EMAIL);
    $email_check = $api_url.'/api/newsletter_check?email='.$sanitized_a.'&site_id='.$website_id;
    $email_check_status = file_get_contents($email_check);
    $email_check_array = json_decode($email_check_status,true);
    $status = $email_check_array['status'];
    if($status == 'success'){
        $subscribed = ['status'=>'duplicate'];
        if (filter_var($sanitized_a, FILTER_VALIDATE_EMAIL)) {
            setcookie('email',$sanitized_a, time() + (1 * 365 * 24 * 60 * 60), "/", $matches[0] , $isSecure, true); //Expires in 1 years	
        }
    }else if (filter_var($sanitized_a, FILTER_VALIDATE_EMAIL)) {
        $fields['session_id'] = htmlspecialchars($_COOKIE['udid'], ENT_QUOTES);
        if(!is_null($_REQUEST['session_id']) && $_REQUEST['session_id'] != ''){
            $fields['session_id'] = htmlspecialchars($_REQUEST['session_id'], ENT_QUOTES);
        }
        if(!is_null($_REQUEST['form_type']) && $_REQUEST['form_type'] != ''){
            $fields['form_type'] = htmlspecialchars($_REQUEST['form_type'], ENT_QUOTES);
        }
        $fields['email'] = $sanitized_a;
        if (filter_var($sanitized_a, FILTER_VALIDATE_EMAIL)) {
            setcookie('email',$sanitized_a, time() + (1 * 365 * 24 * 60 * 60), "/", $matches[0] , $isSecure, true); //Expires in 1 years	
        }
        $cookie_check = ['fullname','first_name','last_name','phone','newsletter','landing_id'];
        foreach($cookie_check as $field){
            if($field == 'fullname'){
                if(is_null($_REQUEST['first_name']) && is_null($_REQUEST['last_name'])){
                    $temp_fullname = explode(" ", $_COOKIE['fullname']);
                    $_REQUEST['first_name'] = $temp_fullname[0];
                    unset($temp_fullname[0]);
                    $_REQUEST['last_name'] = implode(" ",$temp_fullname);
                }
            }else{
                if(!is_null($_REQUEST[$field]) && $_REQUEST[$field] != ''){
                    $fields[$field] = htmlspecialchars($_REQUEST[$field], ENT_QUOTES);
                    $$field = htmlspecialchars($_REQUEST[$field]);
                    setcookie($field,$_REQUEST[$field], time() + (1 * 365 * 24 * 60 * 60), "/", $matches[0] , $isSecure, true); //Expires in 1 years	
                }else if(isset($_COOKIE[$field])){
                    $fields[$field] = htmlspecialchars($_COOKIE[$field], ENT_QUOTES);
                }
                    
            }
        }
        // if ($status == 0 && in_array($website_id,[6,7])) {
        //     $email_hygeiene = "https://app.emailhygiene.com/api.v4/task/express?api_key=f973a7330501122c5a20dde428fb6e71088849f8&profile=FGN_HVI&emails=".$sanitized_a;
        //     $EH_ch = curl_init();
        //     curl_setopt($EH_ch, CURLOPT_URL, $email_hygeiene);
        //     curl_setopt($EH_ch, CURLOPT_RETURNTRANSFER, 1);
        //     $EH_data = curl_exec($EH_ch);
        //     $EH_result = curl_exec($EH_ch);
        //     curl_close($EH_ch);
        //     $email_status = json_decode($EH_result,true);
        //     if(is_array($email_status['emails'])){
        //         $check = $email_status['emails'][0]['result'];
        //         $fields['cloud_hygiene'] = $email_status['emails'][0]['result'];
        //     }
        // }
        $url = $prefix.'.com/'.$website_id.'/newsletter.php';
        //url-ify the data for the POST
        $fields_string = http_build_query($fields);
        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

        //So that curl_exec returns the contents of the cURL; rather than echoing it
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 

        //execute post
        $result = curl_exec($ch);
        $returned = json_decode($result,true);
        $subscribed = ['status'=>'success'];
        if($fields['newsletter']){
            setcookie('newsletter','on', time() + (30 * 24 * 60 * 60), "/", $matches[0] , $isSecure, true); //Expires in 30 days
        }
    }else{
        $subscribed = ['status'=>'failure'];
    }
}
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");
header('Content-Type: application/json');
echo json_encode($subscribed);
?>