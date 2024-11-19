<?php
$temp_post = array();
foreach($_POST as $key => $value){
    $temp_post[$key] = str_replace('&amp;','',htmlspecialchars($value));
}
$submitted_count = count(explode(',',$_POST['fbolist']));
$request_URL = $prefix.'.com/'.$website_id.'/leadmanagement2.php';
$ua=get_browser();
if($_POST['investment_toggle'] == 'on'){
    $investment_mapping = [/*50,100,150,200,250,300,350,400,450,500,600,700,800,900,*/1000,1500,2000,2500,3000,3500,4000,4500,5000,6000,7000,8000,9000,10000,20000,30000,40000,50000,60000,70000,80000,90000,100000,150000,200000,250000,300000,350000,400000,450000,500000,550000,600000,650000,700000,750000,800000,850000,900000,950000,1000000,1100000,1200000,1300000,1400000,1500000,1600000,1700000,1800000,1900000,2000000];
    foreach($investment_mapping as $key => $value){
        if($value >= $_POST['highest']){
            $request_investment = $value;
            break;
        }
    }
}else{
    $request_investment = $_POST['investment'];            
}
if($_POST['newsletter'] == 'on'){
    $newsletter = 1;
}else{
    $newsletter = 0;            
}
if($temp_post['fullname']){
    $temp_fullname = ltrim($temp_post['fullname']);
    $names = explode(" ", $temp_fullname);
    $fields_firstname = $names[0];
    unset($names[0]);
    $fields_lastname = implode(" ",$names);
}else{
    $fields_firstname = $temp_post['firstname'];
    $fields_lastname = $temp_post['lastname'];
}
if(isset($_POST['guidant'])){
    $temp_post['fbolist'] .= ','.$_POST['guidant'];
    setcookie('gc',1, time() + (3 * 24 * 60 * 60), "/", $matches[0] , $isSecure, true); //Expires in 3 days
}

// debug($_POST);die;
$fields = array(
    'firstname' => $fields_firstname,
    'lastname' => $fields_lastname,
    'email' => $temp_post['email'],
    'address1' => $temp_post['address1'],
    'phone' => $temp_post['phone'],
    'zipcode' => $temp_post['zipcode'],
    'ip_address' => $temp_post['ip_address'],
    'country' => $temp_post['country'],
    'preferred_state' => $temp_post['preferred_state'],
    'fbolist' => $temp_post['fbolist'],
    'investment' => $request_investment,
    'newsletter' => $newsletter,
    'do_not_disclose' => $_POST['investment_toggle'],
    'lead_type' => 'multi',
    'udid' => $temp_post['session_id'],
    'session_id' => $temp_post['session_id'],
    'abandon_cart' => $temp_post['abandon_cart'],
    'source' => 'web',
    'lead_source' => 'WEB',
    'device_type' => $temp_post['device_type'],
    'device_name' => $ua['name'],
    'version' => $ua['version'],
    'browser_type' => is_mobile(),
    'submission_url' => submission_url($temp_post['submission_url']), 
    'form_url' => submission_url($temp_post['form_url']),
    'landing_id' => $landing_id, 
    'utm_source' => $temp_post['utm_source'],
    'utm_medium' => $temp_post['utm_medium'],
    'utm_campaign' => $temp_post['utm_campaign'],
    'utm_type' => $temp_post['utm_type'],
    'gclid_mlclkid' => $temp_post['gclid_mlclkid'],
    'google_id' => $temp_post['google_id'],
    'sitecat_id' => $temp_post['sitecat_id'],
    'email_blast' => $temp_post['email_blast'],
    'contracts_id' => $temp_post['contracts_id'],
    'lrq' => 1,
    'NEWLEADSYS' => 1,
    'platform_type' => 'WEB',
    'paid' => is_paid(),
    'work_phone' => $temp_post['work_phone']
);	
foreach($fields as $key => $value) {
    $fields_string[] = $key . "=". $value;
}
$fields_string = implode("&", $fields_string);

//Reset UDID
setcookie('quiz_udid',$udid, time() + (10 * 365 * 24 * 60 * 60), "/", $matches[0] , $isSecure, true); //Expires in 10 years	
$udid = uniqid(rand(), true);
setcookie('udid',$udid, time() + (10 * 365 * 24 * 60 * 60), "/", $matches[0] , $isSecure, true); //Expires in 10 years	

//open connection
$ch = curl_init();
	
//set the url, number of POST vars, POST data
curl_setopt($ch,CURLOPT_URL,$request_URL);
curl_setopt($ch,CURLOPT_POST, count($fields));
curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0); 
curl_setopt($ch, CURLOPT_TIMEOUT, 15); //timeout in seconds
	
//execute post
$response = curl_exec($ch);
//close connection
curl_close($ch);
	
//Convert to array
$response = verify_submission_data($response);
?>