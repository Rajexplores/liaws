<?php
$host = $_SERVER['HTTP_HOST'];
preg_match("/[^\.\/]+\.[^\.\/]+$/", $host, $matches);
$isSecure = false;
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
    $isSecure = true;
}else if(!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on'){
    $isSecure = true;
}
if (!isset($_COOKIE['udid'])) {
    $udid = uniqid(rand(), true);
    setcookie('udid',$udid, time() + (10 * 365 * 24 * 60 * 60), "/", $matches[0] , $isSecure, true); //Expires in 10 years
} else {
    $udid = htmlspecialchars($_COOKIE['udid'], ENT_QUOTES);
}
if (!isset($_COOKIE['quiz_udid'])) {
    setcookie('quiz_udid',$udid, time() + (10 * 365 * 24 * 60 * 60), "/", $matches[0] , $isSecure, true); //Expires in 10 years	
}
//Checking to see if visitor already has UTM variables set
$campaign_check = ['utm_medium','utm_source','utm_campaign','utm_type','gclid','mlclkid','msclkid','fbclid','wbraid'];
$current_get = array_keys($_GET);
$intersect = array_intersect($campaign_check, $current_get);

if(!empty($intersect)){
    //declaring the UTM cookie arrays
    $fv_campaign = array();
    $gclid_array = ['gclid','mlclkid','msclkid','fbclid','wbraid'];

    //Clearing existing cookie
    if (isset($_COOKIE['fv_campaign'])) {
        unset($_COOKIE['fv_campaign']); 
        setcookie('fv_campaign', null, -1, '/'); 
    }
    foreach ($campaign_check as $key => $value) {
        if(strpos($_GET[$value], '_ten')!=false){
            setcookie('perfmax',10, time() + (1 * 60 * 24 * 60 * 60), "/", $matches[0] , $isSecure, false);
        }
        if (isset($_GET[$value])) {
            $$value = htmlspecialchars($_GET[$value]);
            $fv_campaign[$value] = $_GET[$value];
        }
    }
    foreach ($gclid_array as $key => $value) {
        if (isset($_GET[$value])) {
            $gclid_mlclkid = htmlspecialchars($_GET[$value]);
            $fv_campaign['gclid_mlclkid'] = $_GET[$value];
        }
    }
    setcookie('fv_campaign',json_encode($fv_campaign), time() + (1 * 24 * 60 * 60), "/", $matches[0] , $isSecure, true); //Expires in 1 day
}else{
    if(isset($_COOKIE['fv_campaign'])){
        $temp_fv_campaign = json_decode($_COOKIE['fv_campaign'],true);
        foreach ($temp_fv_campaign as $key => $value) {
            $$key = htmlspecialchars($value, ENT_QUOTES);
        }        
    }
}
if (isset($_COOKIE['landing_id'])) {
    $landing_id = $_COOKIE['landing_id'];
}
if (isset($_COOKIE['abandon_cart'])) {
    $abandon_cart = $_COOKIE['abandon_cart'];
}

$cookie_array = ['fullname','firstname','lastname','email','phone','zipcode','investment','city','country'];
foreach ($cookie_array as $key => $value) {
    if (!isset($_COOKIE[$value])) {
        if(isset($_REQUEST[$value])){
            $$value = htmlspecialchars($_REQUEST[$value]);
            setcookie($value,$_REQUEST[$value], time() + (1 * 365 * 24 * 60 * 60), "/", $matches[0] , $isSecure, true); //Expires in 1 years	
        }
    }else{
        if(isset($_REQUEST[$value])){
            setcookie($value,$_POST[$value], time() + (1 * 365 * 24 * 60 * 60), "/", $matches[0] , $isSecure, true); //Expires in 1 years
            $$value = $_REQUEST[$value];   	            
        }else{
            $$value = $_COOKIE[$value];   
        }       
    }
}
if(isset($_GET['state_name'])){
    setcookie('state_name',$_GET['state_name'], time() + (1 * 24 * 60 * 60), "/", $matches[0] , $isSecure, true); //Expires in 30 days	        
}
if (!isset($_COOKIE['newsletter'])) {
    if(isset($_POST['newsletter']) || isset($_POST['pre_newsletter'])){
        $newsletter = $_POST['newsletter'];
        if(isset($_POST['pre_newsletter'])){
            $newsletter = $_POST['pre_newsletter'];
        }
        setcookie('newsletter',$newsletter, time() + (30 * 24 * 60 * 60), "/", $matches[0] , $isSecure, true); //Expires in 30 days	        
    }
}else{
    $newsletter = htmlspecialchars($_COOKIE['newsletter'], ENT_QUOTES);
}
if (!isset($_COOKIE['requested'])) {
    if(isset($_POST['fbolist'])){
        $requested = explode(',',$_POST['fbolist']);
        $requested = array_unique($requested);
        $requested = implode(',',$requested);
        setcookie('requested',$requested, time() + (30 * 24 * 60 * 60), "/", $matches[0] , $isSecure, true); //Expires in 30 days	        
    }
}else{
    if(isset($_POST['fbolist'])){
        $requested = $_POST['fbolist'].','.$_COOKIE['requested'];
        $requested = explode(',',$requested);
        $requested = array_unique($requested);
        $requested = implode(',',$requested);
        setcookie('requested',$requested, time() + (30 * 24 * 60 * 60), "/", $matches[0] , $isSecure, true); //Expires in 30 days
    }else{
        $requested = $_COOKIE['requested'];        
    }
}
if (isset($_COOKIE['previous'])) {
    $previous_cart = $_COOKIE['previous'];
}else{
    $previous_cart = '';
}


session_start();
include_once('visitor_cookie_class.php');
if(@$_SESSION['visitor_id'] || @$_SESSION['franlinks_cookie_attempted']) {
    //we already checked this previously in this session. skip cookie check altogether.
    //if no visitor_id, then tried to set cookie earlier, but it didn't take. Likely undetected bot or cookies are off/disabled. Don't try further (b/c this will just generate add'l unneccesary entries in visitors table) -MLK, 2/17/2022

    //echo "visitor ID exists, don't do cookie check";
} else {
    $visitor = new visitor_cookie($api_url);

    $set_cookie = false;
    $is_bot = $visitor->detect_bots($visitor->get_ip());
    if($is_bot) {
        //is a bot? don't set visitor cookie.
        //echo "bot detected, don't set cookie.";
        $set_cookie = false;
    } else if(isset($_COOKIE['fvvisitor'])) {
        //cookie already set previously. let's check if we have it in record in the DB for this site_id.
        $visitor_id = $visitor->find_visitor_id($_COOKIE['fvvisitor'], $brand_array['website_id']);
        if($visitor_id) {
            $_SESSION['visitor_id'] = $visitor_id;
            $set_cookie = false;
        } else {
            $set_cookie = true; //no matching visitor id? then we need to set a new cookie.
        }
    } else {
        //not a bot and no cookie set? then let's set it. 
        $set_cookie = true;
    }
    //echo "set cookie: " . $set_cookie;
    if($set_cookie) {
        $cookie_info = [
            "domain" => $matches[0],
            "isSecure" => $isSecure
        ];
        $visitor->set_visitor_cookie($cookie_info, $brand_array['website_id']);
        $visitor_id = $visitor->find_visitor_id($_COOKIE['fvvisitor'], $brand_array['website_id']);
        if($visitor_id) {
            $_SESSION['visitor_id'] = $visitor_id;
        }
    }
    if(!isset($_SESSION['franlinks_cookie_attempted'])) {
        $_SESSION['franlinks_cookie_attempted'] = "Y";
    }

    //record regular visit
    //NOTE: NO NEED TO STORE INDIV SESSIONS TO MAKE FRANLINKS WORK. SAVE THIS FOR FUTURE IF WE DECIDE TO KEEP MORE DETAILED RECORDS OF SITE VISITS.
    //MEANWHILE WE WILL KEEP THIS COMMENTED OUT BECAUSE THERE'S NO NEED TO CLUTTER UP DB WITH LOTS OF SESSION RECORDS UNLESS WE ARE USING THEM FOR SOMETHING. - MLK, 12/19/21
    //SOMETIMES WE WILL COMMENT BACK IN VISITOR SESSIONS IN TO DIAGNOSE BOT TRAFFIC. -MLK, 2/18/2022
    //if($visitor_id) {
    //    $visitor->record_site_visit($visitor_id, $brand_array['website_id'], session_id(), $visitor->get_ip());
    //}
    unset($visitor,$set_cookie,$is_bot); //can optionally not unset $is_bot for later settings later if we need it.
}
//print_r($_SESSION);
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
        track_netacuity(55,$netacuity,$geoLocation);
        $geo['city'] = $geoLocation['response']['pulse-city'];
        $geo['state'] = $geoLocation['response']['pulse-region'];
        $geo['country'] = $geoLocation['response']['pulse-two-letter-country'];
        $geo['country3'] = $geoLocation['response']['pulse-country'];
        $geo['continent'] = $geoLocation['response']['pulse-continent-code'];
        setcookie('geo',json_encode($geo), time() + (1 * 24 * 60 * 60), "/", $matches[0] , $isSecure, true); //Expires in 1 day
    }
}