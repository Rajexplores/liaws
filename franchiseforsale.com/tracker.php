<?php
header('Content-Type: image/png');
echo base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABAQMAAAAl21bKAAAAA1BMVEUAAACnej3aAAAAAXRSTlMAQObYZgAAAApJREFUCNdjYAAAAAIAAeIhvDMAAAAASUVORK5CYII=');

session_start();
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
$site_id = 55; //FranchiseForSale.com site_id
include_once('includes/visitor_cookie_class.php');
$visitor = new visitor_cookie($api_url);
$ipAddress = $visitor->get_ip(); 
$is_bot = $visitor->detect_bots($ipAddress);
if($_SESSION['visitor_id']) {
    $visitor_id = $_SESSION['visitor_id'];
} else if($_COOKIE['fvvisitor']) {
    $visitor_id = $visitor->find_visitor_id($_COOKIE['fvvisitor'], $site_id);
} else {
    $visitor_id = 0;
}

if(
    $is_bot
    /*|| !$visitor->is_recent_visitor($_COOKIE['fvvisitor'], $site_id)*/ 
    || !$visitor_id //if there is no visitor_id, then they haven't visited this site before.
) {
    exit(); //don't record visits or conversions if they were not a previous visitor TO THIS SITE.
}

$data = array();
//TODO: can we accommodate old gator_links pixels?
$data['customer_id'] = $_GET['cid'] ? $_GET['cid'] : 0;
$data['concept_id'] = $_GET['cpid'] ? $_GET['cpid'] : 0;
$data['site_id'] = $site_id;
$data['ip'] = $ipAddress;
$data['visitor_id'] = $visitor_id;
$landing_page_value = $_GET['landing_page'] ? $_GET['landing_page'] : "blank";
$data['landing_page'] = $_GET['landing_page']; 
//print_r($data);
//print_r($_SESSION);
if($_SESSION['visit_tracked'][$data['customer_id'] ."_". $data['concept_id']][$landing_page_value]) {
    exit(); //don't track dupe visits in same session.
}

if($data['visitor_id'] && $data['customer_id'] > 0 && $data['concept_id'] > 0) {
    $success = $visitor->record_franlinks_visit($data);
    if($success) {
        $_SESSION['visit_tracked'][$data['customer_id'] ."_". $data['concept_id']][$landing_page_value] = true; //only store in session if recording was successful!
    }
}
?>