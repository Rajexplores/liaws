<?php

// ini_set('display_errors', '1');
// ini_set('display_startup_errors', '1');
// error_reporting(E_ALL);

// PERSISTENT FUNCTIONS
include_once('do_not_edit.php');
include_once('includes/redirects.php');
$global_uri = $_SERVER['REQUEST_URI'];
$uri_parts = explode('/', $global_uri); 
$uri_parts[1] = strtok($uri_parts[1], '?');
$prepend_url = '';
$top50Franchises = null;
$paid = is_paid();
$landing = false;
$landing_interiors = true;
$tiny_src = 'data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==';
$spinner = 'https://franchise-ventures-general.s3.amazonaws.com/global/images/spin.svg';
if (in_array($uri_parts[1],['find-a-franchise','great-franchises-a','great-franchises-b','great-franchises-c','great-franchises-d','great-franchises-q','great-franchises-r','great-franchises-demo','great-franchises-demo'])) {
    $landing = true;
}
if(isset($_GET['landing']) || $landing){
    $_GET['landing'] = true;
    if(in_array($uri_parts[1],['find-a-franchise','great-franchises-a','great-franchises-b','great-franchises-c','great-franchises-d','great-franchises-q','great-franchises-r','great-franchises-demo','great-franchises-demo'])){
        $prepend_url = $uri_parts[1].'/';
    }
    $homeUrl = $prepend_url.'search'; 
    if (!in_array($_GET['landing_url'], ['find-a-franchise'])) {
        $homeUrl = $prepend_url;
    }
}

$search_header_results_array = array(); //Just setting this array for later.
$banner_background = ''; //Banners for search results.
$baseURL = '//'.$_SERVER['HTTP_HOST'].'/';
$global_rate = 0;
$storage = '';
$submitted_count = 0;
$gtm = 'GTM-MGPN999'; //Google Tag Manager ID
$add_page_type = null;
$tracking = [
    'bing' => '4030231',
    'meta' => '885342675643204',
    'aw' => 'AW-1071346778',
    'aw_label' => '4-9ZCN7KyAIQ2ujt_gM'
];
$async_load = 'true';
if(strlen(@$_GET['async'])){
    $async_load = 'false';
}
 
//Checks for live or dev
$prefix = 'https://www.franchiseportals';
$api_url = 'https://franchiseinsights.franchiseportals.com';
$not_dev = true;
$global_robots = 'index, follow';
$random_query = null;
$subdomain_check = explode('.',$_SERVER['HTTP_HOST']);

if ($subdomain_check[0] != 'www' || isset($_GET['landing'])){
    $global_robots = 'noindex, nofollow';
}
if (!in_array($subdomain_check[0],['www','www2'])){
    $sub = 'www';
    if(!is_numeric($subdomain_check[0])){
        $sub = $subdomain_check[0];
    }
    $prefix = 'https://'.$sub.'.franchiseportals';    
    $api_url = 'https://'.$sub.'.franchiseinsights.franchiseportals.com';  
    $random_query = '?random='.mt_rand(100000, 999999);
    $not_dev = false;
}

if (isset($_SERVER['HTTP_REFERER'])) { 
    $relative_path = $_SERVER['HTTP_REFERER']; 
}else{
    $relative_path = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
}

//Include Branding
include_once('branding.php');

//Setting Cookies
include_once('cookies.php');

//EU Check
if(isset($_GET['eucheck']) || $geo['continent'] == 5){
    $eucheck = true;
}

global $isSecure,$matches;

if (!isset($_COOKIE['ip_address']) || $_COOKIE['ip_address'] != $ipAddress) {
    setcookie('ip_address',$ipAddress, time() + (10 * 365 * 24 * 60 * 60), "/", $matches[0] , $isSecure, true); //Expires in 10 years	
} 

//SHOW SYNDICATED CSS
function showsynd($website_id){
    if(isset($_GET['showsynd'])){
        if($_GET['showsynd'] == 'yes'){
            return '<style>
            .listing:not([data-id="'.$website_id.'"]) {position:relative;}
            .listing:not([data-id="'.$website_id.'"]):before {content:"site_id: "attr(data-id);position: absolute;top:15px;left:14.6px;z-index: 1;background-color:#0047AB;color:#FFF;padding: 15px;line-height: 1;}
            .listing:not([data-id="'.$website_id.'"]) .item {border-color:#0047AB !important;background: #A7C7E7 !important;}
        </style>';
        }
    }
}

$site_id = $brand_array['site_id'];
$website_id = $brand_array['website_id'];

//ON FIRST TIME
if (isset($_SERVER['HTTP_REFERER'])){
    $temp_server_name = preg_replace('/^www\./', '', strtolower($_SERVER['SERVER_NAME']));
    if (strpos($_SERVER['HTTP_REFERER'], $temp_server_name) !== false){
        // User has come from another page on my site
    }else{
        first_time();
    }
}

$filterstate = ["AL" => "alabama","AK" => "alaska","AZ" => "arizona","AR" => "arkansas","CA" => "california","CO" => "colorado","CT" => "connecticut","DE" => "delaware","FL" => "florida","GA" => "georgia","HI" => "hawaii","ID" => "idaho","IL" => "illinois","IN" => "indiana","IA" => "iowa","KS" => "kansas","KY" => "kentucky","LA" => "louisiana","ME" => "maine","MD" => "maryland","MA" => "massachusetts","MI" => "michigan","MN" => "minnesota","MS" => "mississippi","MO" => "missouri","MT" => "montana","NE" => "nebraska","NV" => "nevada","NH" => "new-hampshire","NJ" => "new-jersey","NM" => "new-mexico","NY" => "new-york","NC" => "north-carolina","ND" => "north-dakota","OH" => "ohio","OK" => "oklahoma","OR" => "oregon","PA" => "pennsylvania","RI" => "rhode-island","SC" => "south-carolina","SD" => "south-dakota","TN" => "tennessee","TX" => "texas","UT" => "utah","VT" => "vermont","VA" => "virginia","WA" => "washington","WV" => "west-virginia","WI" => "wisconsin","WY" => "wyoming", "CAN" => "canada", "INT" => "international"];
$filtercat = [1=>"Advertising Franchises",2=>"Auto Franchises",3=>"Beauty Franchises",4=>"Business Opportunities",5=>"Business Services Franchises",6=>"Child Related Franchises",7=>"Cleaning Franchises",8=>"Computer&#44; Ecommerce &amp; Internet Franchises",9=>"Education Franchises",292=>"Entertainment Franchises",10=>"Financial Franchises",11=>"Food Franchises",12=>"Green Franchises",13=>"Health & Fitness Franchises",14=>"Home Based Franchises",15=>"Home Services & Repair Franchises",16=>"Low Cost Franchises",18=>"Manufacturing Franchises",17=>"Mobile Franchises",19=>"Pet Franchises",20=>"Photography & Video Franchises",21=>"Printing, Copying, Shipping, Signs Franchises",22=>"Restoration, Disaster Recovery Franchises",23=>"Retail Franchises",24=>"Seasonal Franchises",25=>"Security & Alarm Franchises",26=>"Senior Care Franchises",27=>"Sports & Recreation Franchises",29=>"Travel Franchises",30=>"Vending Franchises",291=>"Veteran's Franchises",288=>"Wholesale - Distribution Franchises",297=>"Women's Franchises"];
$skippedCategories=['Green Franchises','Manufacturing Franchises','Mobile Franchises','Photography & Video Franchises','Restoration, Disaster Recovery Franchises','Seasonal Franchises','Security & Alarm Franchises','Wholesale - Distribution','Wholesale   Distribution Franchises','Wholesale - Distribution Franchises'];
$flipStates = array_flip($filterstate);
$investment_array = [10000,20000,30000,40000,50000,60000,70000,80000,90000,100000,150000,200000,250000,300000,350000,400000,450000,500000,500001];
if (@$affordable) {
    $filterstate = ["AL" => "alabama","AK" => "alaska","AZ" => "arizona","AR" => "arkansas","CA" => "california","CO" => "colorado","CT" => "connecticut","DC" => "district-of-columbia","DE" => "delaware","FL" => "florida","GA" => "georgia","HI" => "hawaii","ID" => "idaho","IL" => "illinois","IN" => "indiana","IA" => "iowa","KS" => "kansas","KY" => "kentucky","LA" => "louisiana","ME" => "maine","MD" => "maryland","MA" => "massachusetts","MI" => "michigan","MN" => "minnesota","MS" => "mississippi","MO" => "missouri","MT" => "montana","NE" => "nebraska","NV" => "nevada","NH" => "new-hampshire","NJ" => "new-jersey","NM" => "new-mexico","NY" => "new-york","NC" => "north-carolina","ND" => "north-dakota","OH" => "ohio","OK" => "oklahoma","OR" => "oregon","PA" => "pennsylvania","RI" => "rhode-island","SC" => "south-carolina","SD" => "south-dakota","TN" => "tennessee","TX" => "texas","UT" => "utah","VT" => "vermont","VA" => "virginia","WA" => "washington","WV" => "west-virginia","WI" => "wisconsin","WY" => "wyoming"];
    $filtercat = [1 => 'Automotive',4 => 'Business Opportunities',3 => 'Business Services',5 => 'Child-Related',6 => 'Cleaning',7 => 'Computer&#44; Ecommerce &amp; Internet',8 => 'Education',9 => 'Financial Services',11 => 'Food & Restaurant',10 => 'Health & Fitness',16 => 'Healthcare & Senior Care',12 => 'Home Services',17 => 'Home-Based',21 => 'Low Cost',2 => 'Personal Care',13 => 'Pet Care',20 => 'Real Estate',14 => 'Retail',18 => 'Sports and Recreation',19 => 'Staffing and Personnel',15 => 'Travel & Lodging',22 => 'Vending Machine'];
    $seocat = ['automotive' => 1,'business-opportunities' => 4,'business-services' => 3,'child-related' => 5,'cleaning' => 6,'computers-and-internet' => 7,'education' => 8,'financial-services' => 9,'food-and-restaurant' => 11,'health-and-fitness' => 10,'healthcare-and-senior-care' => 16,'home-services' => 12,'home-based' => 17,'low-cost' => 21,'personal-care' => 2,'pet-care' => 13,'real-estate' => 20,'retail' => 14,'sports-and-recreation' => 18,'staffing-and-personnel' => 19,'travel-and-lodging' => 15,'vending-machine' => 22];
    // $investment_array = ['0-50000'=>'Under $50K','50000-100000'=>'$50K &dash; $100K','more-than-100000'=>'Over $100K'];
}
$units = [4 =>'FG',5 =>'FON',6 =>'FCN',7 =>'FCN'];
$mapped_categories = [
    'Advertising Franchises' => 'advertising-franchises',
    'Auto Franchises' => 'auto-franchises',
    'Beauty Franchises' => 'beauty-franchises', 
    'Business Opportunities' => 'business-opportunities',
    'Business Services Franchises' => 'business-services-franchises',
    'Child Related Franchises' => 'child-related-franchises',
    'Cleaning Franchises' => 'cleaning-franchises',
    'Computer&#44; Ecommerce &amp; Internet Franchises' => 'computer-internet-franchises',
    'Education Franchises' => 'education-training-franchises',
    'Entertainment Franchises' => 'entertainment-franchises',
    'Financial Franchises' => 'financial-franchises',
    'Food Franchises' => 'food-franchises',
    'Green Franchises' => 'green-eco-friendly-franchises', //
    'Health & Fitness Franchises' => 'health-fitness-franchises',
    'Home Based Franchises' => 'home-based-franchises',
    'Home Services & Repair Franchises' => 'home-services-repair-franchises',
    'Low Cost Franchises' => 'low-cost-franchises', 
    'Manufacturing Franchises' => 'manufacturing', //
    'Mobile Franchises' => 'mobile-franchises', //
    'Pet Franchises' => 'pet-franchises',
    'Photography & Video Franchises' => 'photography-video', //
    'Printing, Copying, Shipping, Signs Franchises' => 'printing-copy-shipping-signs',
    'Restoration, Disaster Recovery Franchises' => 'restoration-disaster-recovery-franchises',
    'Retail Franchises' => 'retail-franchises',
    'Seasonal Franchises' => 'seasonal-franchises', //
    'Security & Alarm Franchises' => 'security-alarm-franchises',
    'Senior Care Franchises' => 'senior-care-franchises',
    'Sports & Recreation Franchises' => 'recreation-sports-franchises',
    'Travel Franchises' => 'travel-franchises',
    'Vending Franchises' => 'vending-franchises',
    'Veteran\'s Franchises' => 'veteran-franchises-for-sale',
    'Wholesale - Distribution Franchises' => 'wholesale-distribution-franchises' //
];

$flipMappedCategories = array_flip($mapped_categories);

$menu_categories = [
    'Advertising Franchises' => 'advertising-franchises',
    'Auto Franchises' => 'auto-franchises',
    'Beauty Franchises' => 'beauty-franchises',
    'Business Opportunities' => 'business-opportunities',
    'Business Services Franchises' => 'business-services-franchises',
    'Child Related Franchises' => 'child-related-franchises',
    'Cleaning Franchises' => 'cleaning-franchises',
    'Computer&#44; Ecommerce &amp; Internet Franchises' => 'computer-internet-franchises',
    'Education Franchises' => 'education-training-franchises',
    'Entertainment Franchises' => 'entertainment-franchises',
    'Financial Franchises' => 'financial-franchises',
    'Food Franchises' => 'food-franchises',
    'Health & Fitness Franchises' => 'health-fitness-franchises',
    'Home Based Franchises' => 'home-based-franchises',
    'Home Services & Repair Franchises' => 'home-services-repair-franchises',
    'Low Cost Franchises' => 'low-cost-franchises', 
    'Pet Franchises' => 'pet-franchises',
    'Printing, Copying, Shipping, Signs Franchises' => 'printing-copy-shipping-signs',
    'Retail Franchises' => 'retail-franchises',
    'Security & Alarm Franchises' => 'security-alarm-franchises',
    'Senior Care Franchises' => 'senior-care-franchises',
    'Sports & Recreation Franchises' => 'recreation-sports-franchises',
    'Travel Franchises' => 'travel-franchises',
    'Vending Franchises' => 'vending-franchises'
];


//Master Categories
$master_category_mapping = [
    1 => 40, //Advertising
    2 => 1, //Automotive
    3 => 2, //Beauty
    4 => 4, //Business Opportunities
    5 => 3, //Business Services
    6 => 5, //Child-related
    7 => 6, //Cleaning & Maintenance
    8 => 7, //Computer and Internet
    9 => 8, //Education
    292 => 39, //Entertaiment Franchises
    10 => 9, //Financial Services
    11 => 11, //Food & Restaurant
    12 => 26, //Green/Earth Friendly
    13 => 10, //Health & Fitness
    14 => 17, //Home-Based
    15 => 12, //Home Services
    16 => 21, //Low Cost
    18 => 41, //Manufacturing
    17 => 25, //Mobile
    19 => 13, //Pet Services
    20 => 33, //Photography
    21 => 24, //Printing, Copying, Shipping, Signs
    22 => 42, //Restoration, Disaster Recovery Franchises
    23 => 14, //Retail
    24 => 43, //Seasonal Franchises
    25 => 44, //Security & Alarm Franchises
    26 => 16, //Senior and Healthcare
    27 => 18, //Sports and Recreation
    29 => 15, //Travel & Lodging
    30 => 22, //Vending
    291 => 23, //Veterans
    288 => 45, //Wholesale Distribution Franchises
    297 => 19, //Woman's
];

function master_mapping($cat){
    global $master_category_mapping;
    if (array_key_exists($cat, $master_category_mapping)) {
        return $master_category_mapping[$cat];
    }
}

function city_urls($city){
    $string = strtolower($city);
    $string = preg_replace("/[^A-Za-z0-9\s-]/", "", $string);
    $string = preg_replace("/[[:space:]]+/", "-", $string);
    $string = preg_replace('/-+/', '-', $string);
    return $string;
}

function url_to_title($url){
    $string = preg_replace("/[^a-zA-Z0-9]/", " ", $url);
    return ucwords($string);
}

//State Information
$homeState = return_home_state();
$home_stateurl = $filterstate[$homeState];
$home_statename = ucwords(str_replace("-", " ", $home_stateurl));

function footer_links(){
    $return = '';
    global $footer_array;
    foreach ($footer_array as $key => $value) {
        $return .= '<li><a href="'.$value['url'].'" target="'.$value['target'].'" rel="noopener">'.$value['title'].'</a></li>';
    }
    echo $return;
}

//Country Form Select
function form_country(){
    global $filterstate,$homeState;
    $countries = ['USA'=>'United States','CAN'=>'Canada','GBR'=>'United Kingdom','IND'=>'India','VCS'=>'Other'];
    $return = '';   
        foreach ($countries as $key => $value) {
            $selected = '';
            if($key == 'CAN' && $homeState == 'CAN' || in_array($key,$filterstate)){
                $selected = ' selected';
            }
            $return .= '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
    }
    echo $return;    
}

//State Form Select
function form_states(){
    global $filterstate,$homeState;
    $return = '<option value="">Select a State</option>';   
        foreach ($filterstate as $key => $value) {
            $selected = '';
            if($key == $homeState){ 
                $selected = ' selected';
            }
            $stateName = ucwords(str_replace("-", " ", $filterstate[$key]));
            $return .= '<option value="'.$key.'" '.$selected.'>'.$stateName.'</option>';
    }
    echo $return;    
}

//Investment Form Select
function form_investment(){
    global $investment_array;
    $return = '<option disabled value="">Select an Investment Level</option>';   
        foreach ($investment_array as $key => $value) {
            $return .= '<option value="'.$value.'">';
            if($value == 500001){
                $return .= 'Over $500,000';             
            }else{
                $return .= '$'.number_format($value);
                
            }
            $return .= '</option>';
    }
    echo $return;    
}

if (strlen(@$_GET['state'])) {
    foreach($filterstate as $key => $value){
        if($_GET['state'] == $value){
            $_POST['state'] = $key;
        }
    }
    $stateName = ucwords(str_replace("-", " ", $filterstate[$_POST['state']]));
}
if (strlen(@$_GET['category'])) {
    if($_GET['category'] == 'all-categories'){
        $_POST['category'] = ''; 
        $catName = 'All Categories';
    }else{
        $_POST['category'] = $seocat[$_GET['category']];   
        $catName = $filtercat[$seocat[$_GET['category']]];     
    }
}
if (strlen(@$_GET['investment_filter'])) {
    if($_GET['investment_filter'] == 'any-level'){
        $_POST['investment_filter'] = '';  
        $investmentName = 'All Investment Levels';
    }else{
        $_POST['investment_filter'] = $_GET['investment_filter']; 
        $investmentName = $investment_array[$_POST['investment_filter']];          
    }
}
function create_select($array,$select,$post,$skip=[]){
    global $mapped_categories;
    if($select == 'State'){
        $return = '<option disabled>Select a '.$select.'</option>';        
    }else{
        $return = '<option>All Industries</option>';            
    }
    foreach ($array as $key => $value) {
        if ($value == 'canada' || $value == 'international') {
            continue;
        }
        if($key != 297 && !in_array($value,$skip)){ 
            if($select == 'Industry'){
                $return .= '<option value="'.$mapped_categories[$value].'"';
            }else{
                $return .= '<option value="'.$key.'"';
            }
            if($key == $post){
                $return .= ' selected';            
            }
            $return .= '>'.ucwords(str_replace("-", " ", $value)).'</option>';
        }
    }
    echo $return;
}
function investment_select($post){
    global $investment_array;
    $temp_investment = $investment_array;
    // array_push($temp_investment, 'over');
    $return = '<option value="all-investment-amounts">All Investment Amounts</option>';   
        foreach ($temp_investment as &$value) {
        $return .= '<option value="'.$value.'"';
        if($value == $post){
            $return .= ' selected';            
        }
        if($value == 'over' || $value == 500001){
            $temp_text = 'Over $500,000';
        }else{
            if($value != 500001){
                $temp_text = 'Under $'.number_format($value);
            }else{
                continue;
            }
        }
        $return .= '>'.$temp_text.'</option>';
    }
    echo $return;
    
}

function cleanfix($text,$start = true,$description = false){
    $return = strip_tags(str_replace('<', ' <', $text));
    $unwanted_array = array(    'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
                            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
                            'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
                            'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
                            'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y' );
    $return = strtr( $return, $unwanted_array );
    // if($description == false){
    //     $return = preg_replace("/[^a-zA-Z0-9&°.'-_\s ]+/", "", $return);
    // }
    $return = str_replace("&39;","&apos;",$return);
    $return = str_replace("'","&apos;",$return);
    $return = str_replace("&2013266094;","&reg;",$return);
    if($start == true){
        $return = stripslashes($return);                   
    }else{
        $return = htmlspecialchars_decode($return);              
    }
    $return = strip_tags($return);
    $return = preg_replace( "/\r|\n/", "", $return );
    return $return;
}

function cleanReverse($text){
    $return = preg_replace("/[^a-zA-Z0-9&.'-_\s ]+/", "", $text);
    $return = str_replace("'","&apos;",$return);
    $return = str_replace("&2013266094;","&reg;",$return);
    if($start == true){
        $return = stripslashes($return);                   
    }else{
        $return = htmlspecialchars_decode($return);              
    }
    $return = strip_tags($return);
    $return = preg_replace( "/\r|\n/", "", $return );
    return $return;
}

function safe_logos($image_url){
    $image_url = str_replace("'","%27",$image_url);
    $image_url = str_replace(" ","%20",$image_url);
    return $image_url;
}

function first_letter($word){
    $vowels = array('a','e','i','o','u','A','E','I','O','U');
	$firstLetter = substr(strtolower($word), 0, 1);
    $begin = "a ";
	if(in_array($firstLetter, $vowels)){
		$begin = "an ";
	}
    return $begin;
}

function createCategoryList(){
    global $investment_array,$cat_array;
    if(!empty($cat_array['subcat']) || isset($_GET['investment'])){
        $temp_list = '';
        $link_text_prefix = '';
        if($_GET['investment']){
            $link_text = 'Other Investment Levels';
            foreach($investment_array as $key => $value){
                $investment_adj = 'under';
                if($value == 500001){
                    $investment_adj = 'over';
                    $value = 500000;
                }
                $temp_text = 'Franchises '.ucwords($investment_adj).' $'.number_format($value);
                $temp_url = '/investment-level/franchises-'.$investment_adj.'-'.$value;
                $temp_list .= '<li><a href="'.$temp_url.'">'.$temp_text.'</a></li>';
            }
        }else if($_GET['cat_name']){
            $link_text = 'of '.$cat_array['data']['category_name'];
            $link_text_prefix = ' Subcategories';
            foreach($cat_array['subcat'] as $key => $value){
                $temp_list .= '<li><a href="/industry/'.$_GET['cat_name'].'/'.$key.'">'.$value.'</a></li>';
            }
        }
        $return = '<p class="subCatLinkText">&nbsp;&nbsp;<a class="l2Link gold-text" onclick="toggleCls();"><span class="l2Link-span">Show</span>'.$link_text_prefix.'</a>&nbsp;&nbsp;<span class="white-text">'.$link_text.'</span></p>';
        $return .= '<div class="subCatList hide"><ul>'.$temp_list.'</ul></div>';
        echo $return;
    }
}

function createStateList(){
    global $filterstate, $prepend_url;

    $tempStates = '<ul>';
    foreach ($filterstate as $key => $value) {
        $tempStates .= '<li><a href="/'.$prepend_url.'state/'.city_urls($value).'-franchises">'.ucwords(str_replace("-", " ", $value)).'</a></li>';
    }
    $tempStates .= '</ul>';

    echo $tempStates;
}

function createInvestmentList(){
    global $investment_array, $prepend_url;
    $temp_list = '';
    foreach($investment_array as $key => $value){
        if($_GET['landing'] && ($_GET['landing_url'] == 'find-a-franchise')){
            if($value == 10000 || $value == 350000 || $value == 400000 || $value == 450000){
                continue;
            }
        }elseif ($_GET['section'] == 'investment-level' && !$_GET['landing']) {
            if($value == 350000 || $value == 400000 || $value == 450000){
                continue;
            }
        }
        $investment_adj = 'under';
        if($value == 500001){
            $investment_adj = 'over';
            $value = 500000;
        }
        $temp_text = 'Franchises '.ucwords($investment_adj).' $'.number_format($value);
        $temp_url = '/'.$prepend_url.'investment-level/franchises-'.$investment_adj.'-'.$value;
        $temp_list .= '<li><a href="'.$temp_url.'">'.$temp_text.'</a></li>';
    }
    echo '<ul>'.$temp_list.'</ul>';
}

function createIndustryList(){
    global $prepend_url,$mapped_categories;
    $tempStates = '<ul>';
    foreach ($mapped_categories as $key => $value) {
        $tempStates .= '<li><a href="/'.$prepend_url.'industry/'.$value.'">'.$key.'</a></li>';
    }
    $tempStates .= '</ul>';

    echo $tempStates;
}

function menu_industry(){
    global $menu_categories,$prepend_url;
    $return = '';
    foreach ($menu_categories as $key => $value) {
        $return .= '<li class="is-submenu-item is-dropdown-submenu-item"><a href="/'.$prepend_url.'industry/'.$value.'">'.$key.'</a></li>';
    }

    echo $return;
}

function create_city_search($cityArray){
    $cityReturn = '';

    $cityName = $cityArray['city']['city_name'];
    $stateName = $cityArray['city']['state_name'];
    $population = $cityArray['city']['population'];
    $age = number_format($cityArray['city']['age'], 1);
    $maleAge = number_format($cityArray['city']['median_male_age'], 1);
    $femaleAge = number_format($cityArray['city']['median_female_age'], 1);
    $individualIncome = number_format($cityArray['city']['income_per_capita']);
    $householdIncome = number_format($cityArray['city']['median_income']);
    $zip = $cityArray['zip_code'];
    $zipCode = $zip['lowest'].'-'.$zip['highest'];
    if (is_null($zip['highest'])) {
        $zipCode = $zip['lowest'];
    }
    $countryName = $cityArray['city']['county_name'];
    $medianAge = number_format(($cityArray['state']['age'] - $cityArray['city']['age']), 1);
    $medianIncome = number_format(($cityArray['state']['income_per_capita'] - $cityArray['city']['income_per_capita']));

    $cityReturn .= '<h2 class="subSection text-center">City Statistics and Other Information About '.$cityName.'</h2>';
    $cityReturn .= '<div class="flex city-info">
    <div class="city-info-item">
    <div class="gray-section"><h3>Demographics</h3>
    <p>From a total population of '.$population.', '.$cityName.' has a median age of <strong>'.$age.'</strong>. The average age of males, in '.$cityName.', is <strong>'.$maleAge.'</strong> while the average age of females is <strong>'.$femaleAge.'</strong>. When you compare this to median age of '.$stateName.', '.$cityName.' is <strong class="minus">'.$medianAge.'</strong> year younger.</p>
    </div></div>
    <div class="city-info-item">
    <div class="gray-section"><h3>Income</h3>
    <p>The median income of individuals, in '.$cityName.', is <strong>$'.$individualIncome.'</strong> while the average household income is <strong>$'.$householdIncome.'</strong>. When comparing the median income of people living in '.$cityName.' to the rest of '.$stateName.', the average income in '.$cityName.' is <strong class="minus">$'.$medianIncome.'</strong> less.</p>
    </div></div>
    <div class="city-info-item">
    <div class="gray-section"><h3>Additional Information</h3>';
    if (!is_null($zipCode)) {
        $cityReturn .= '<label>ZIP code : </label> '.$zipCode.'<br>';
    }
    if ($countryName != '') {
        $cityReturn .= '<label>County Name : </label> '.$countryName.'<br>';
    }
    if (is_null($zipCode) && $countryName == '') {
        $cityReturn .= 'No additional information.';
    }
    $cityReturn .= '<p></p>
    </div></div></div>';

    $cityReturn .= '<div class="city-explore-btn">
    <a href="/'.strtolower($stateName).'" class="button explore" title="Explore Franchises For Sale in '.$stateName.' Now">Explore Franchises For Sale in '.$stateName.' Now</a>
    <h2>Other Franchises Looking For Owners Like You in '.$stateName.'</h2>
    </div>';

    return $cityReturn;
}

//Make Category URLs
function make_categories($cat){
    $temp_key = strtolower($cat);
    $temp_key = preg_replace("/[^A-Za-z0-9\s-]/", "", $temp_key);
    $temp_key = preg_replace("/[[:space:]]+/", "-", $temp_key);
    $temp_key = preg_replace('/-+/', '-', $temp_key);
    if($temp_key == 'education-franchises'){
        $temp_key = 'education-training-franchises';
    }
    if($temp_key == 'green-franchises'){
        $temp_key = 'green-eco-friendly-franchises';
    }
    if($temp_key == 'printing-copying-shipping-signs-franchises'){
        $temp_key = 'printing-copy-shipping-signs';
    }
    if ($temp_key == 'manufacturing-franchises') {
        $temp_key = 'manufacturing';
    }
    if ($temp_key == 'photography-video-franchises') {
        $temp_key = 'photography-video';
    }
    if ($temp_key == 'veterans-franchises') {
        $temp_key = 'veteran-franchises-for-sale';
    }
    if($temp_key == 'NORFOLK'){
        $temp_key = 'UNIT';
    }
    if ($temp_key == 'sports-recreation-franchises') {
        $temp_key = 'recreation-sports-franchises';
    }
    return $temp_key;
}

function getSeoData($type){
    global $api_url,$website_id;
    if ($type == 'state') {
        $seo_url_temp = 'state/'.$_GET['state_name'].'-franchises';
    }elseif(($type == 'industry')){
        $seo_url_temp = 'industry/'.$_GET['cat_name'];
    }elseif ($type == 'sub_cat') {
        $seo_url_temp = 'industry/'.$_GET['cat_name'].'/'.$_GET['sub_cat_name'];
    }
    $seo_url = $api_url.'/api/seo/'.$website_id.'/url/'.$seo_url_temp;
    $seo_url_json = file_get_contents($seo_url);
    $seo_url_array = json_decode($seo_url_json, true); 
    $seo_url_array = $seo_url_array['data'];
    foreach ($seo_url_array as $key => $value) {
        $seo_array[] = $value;
    }
    return $seo_array;
}

function searchBanner(){
    global $secondary_array;
    $subTitleClass = 'hidden-sm';
    $hideMore = 'hidden-md';
    $hideHide = 'hide';
    $subTitleClass = 'hidden-sm'; 
    $hideMoreClass = 'bannerDesc'; 
    $hideTextClass = 'hide';
    $customTitle = '';
    $customDesc = '';
    $customClass = '';
    if(@$_GET['landing_url'] == 'great-franchises-a'){
        $customImg = '/images/custom-landing-page/barowner.jpg';
        $customDesc = '<span style="font-size:18pt">Search for your new franchise today! </span>';
    }elseif(@$_GET['landing_url'] == 'great-franchises-b'){
        $customImg = '/images/custom-landing-page/patterntoplady.jpg'; 
        $customDesc = '<span style="font-size:22pt">Start your own franchise today!</span>';
    }elseif(in_array(@$_GET['landing_url'], ['great-franchises-c','great-franchises-d','great-franchises-q','great-franchises-r','great-franchises-demo'])){
        $customImg = '/images/custom-landing-page/baristaopen.jpg';
        $customTitle = 'Start Your Franchise Search Today!';
        $customDesc = '<span></span>';
    }
    if(@$_GET['section'] == 'alphabetical-company-search'){
        $class = 'w-auto';
        $bannerImg = 'use_bg';
        $banner_background = 'style="--bg-tablet: url(\'/images/hero-images/ffs/alphabetmed.jpg\'); --bg-desktop: url(\'/images/hero-images/ffs/alphabetlrg.jpg\');"';
        $descClass = '';
        $bTitle = 'Franchises Starting With \''.ucwords($_GET['alphabet']).'\'';
        if($_GET['alphabet'] == 'non-alpha'){
            $bTitle = "Franchises Starting With 'Numbers Or Special Characters'";
        }
        $bReadMore = '';
        $subTitleClass = '';
        $bSubTitle = '<p class="sub-title large">Search results by letter in all categories, all states and all investment levels.</p>';
    }elseif(@$_GET['section'] == 'name-search'){
        $class = 'w-auto';
        $bannerImg = 'use_bg';
        $banner_background = 'style="--bg-tablet: url(\'/images/hero-images/ffs/alphabetmed.jpg\'); --bg-desktop: url(\'/images/hero-images/ffs/alphabetlrg.jpg\');"';
        $descClass = '';
        $bTitle = 'Franchises With \''.ucwords($_GET['name']).'\' In The Name';
        $bReadMore = '';
        $subTitleClass = '';
        $bSubTitle = '<p class="sub-title large">Search results by name in all categories, all states and all investment levels.</p>';
    }elseif(@$_GET['section'] == 'directory'){
        $class = 'w-100';
        $bannerImg = 'use-bg';
        $descClass = 'hidden-sm';
        $bigtext = 'big-text';
        $bTitle = "Franchise Directory";
        $bSubTitle = '<p>Begin your exploration of franchise opportunities with our extensive directory. Select franchises that pique your interest with a click or tap, adding them to your personal list. Ready to learn more? Click the \'request info\' button, complete the necessary form, and receive comprehensive details about your chosen franchises, guiding you towards making the perfect choice for your future business venture.</p>';
    }elseif(@$_GET['section'] == 'featured-franchises'){
        $class = 'w-100';
        $descClass = 'hide'; 
        $bannerImg = 'use_bg';
        $temp_banner = $secondary_array[$_GET['section']]['banner'];
        $banner_background = 'style="--bg-tablet: url(\'/images/hero-images/ffs/'.$temp_banner.'med.jpg\'); --bg-desktop: url(\'/images/hero-images/ffs/'.$temp_banner.'lrg.jpg\');"';
        $bTitle = 'Featured Franchises';
        $factsText = 'Hide More';
        $hideHide = '';
        $subTitleClass = '';
        $bSubTitle = '<p>Thank you for visiting our Featured Franchises page. The franchise companies we have featured here represent some of the leaders in their industry. </p>';
        $bDescription = '<span class="hidden-md">Thank you for visiting our Featured Franchises page. The franchise companies we have featured here represent some of the leaders in their industry.</br></span>As you browse the selections, you may view additional information about any of the franchises by clicking on either the logo or the franchise name. Once on the franchise\'s "brochure" page, we provide in-depth overview of the concept so you can learn more. For those that peak your interest, we would encourage you to request more information by completing the form on the bottom of the brochure page or simply add them to your request basket and "check out" when you are ready! We wish you all the best in finding the perfect franchise opportunity for you!';
        // $bMore = '<a class="readMoreToggleMedUp gold-text" onclick="toggleBanner(\''.$descClass.'\',\''.$_GET['section'].'\');">Read More »</a>';
        $bMore = '<a id="readMoreTop" class="readMoreToggleMedUp gold-text" onclick="toggleHidden(\'bannerDesc\',\''.$descClass.'\');">Read More »</a>';
        // $bReadMore = '<div id="readMoreTopMore" class="hidden-md"><a class="white-text readMoreToggle" onclick="toggleHidden(\'bannerDesc\',\''.$descClass.'\');">
        // <span></span>Read More About '.$bTitle.'</a></div>';
        $bReadMore = '';
        $subTitleClass = '';
    }elseif(@$_GET['section'] == 'low-cost-franchises'){
        $class = 'w-100';
        $descClass = 'hide';
        $bannerImg = 'use_bg';
        $temp_banner = $secondary_array[$_GET['section']]['banner'];
        $banner_background = 'style="--bg-tablet: url(\'/images/hero-images/ffs/'.$temp_banner.'med.jpg\'); --bg-desktop: url(\'/images/hero-images/ffs/'.$temp_banner.'lrg.jpg\');"';
        $bTitle = 'Low Cost Franchises';
        $hideHide = ''; 
        $bReadMore = '';
        $subTitleClass = '';
        $factsText = 'Hide More About '.$bTitle;
        $bSubTitle = '<p class="sub-title large">Check out these great low cost franchises and business opportunities that will not break the bank! Find a great franchise to fit your budget.</p>';
    }elseif(@$_GET['section'] == 'high-investment-franchises'){
        $class = 'w-100';
        $descClass = 'hidden-sm';
        $bannerImg = 'use_bg';
        $hideHide = 'hide'; 
        $bgSize = 'bg-cover';
        $banner_background = 'style="--bg-tablet: url(\'/images/hero-images/ffs/highinvestmed.jpg\'); --bg-desktop: url(\'/images/hero-images/ffs/highinvestlrg.jpg\');"';
        $bTitle = 'High Investment Franchises';
        $bSubTitle = 'Welcome to our High Investment Franchise page. Below are franchises that require an investment of $250,000 or more. These franchises are well worth their investment value as they represent high-quality leaders in their industry and can provide a great return on investment.';
        $bMore = null;
        $bReadMore = '';
        $subTitleClass = '';
        if ($_GET['landing_url'] == 'find-a-franchise' && isset($_GET['invest'])) {
            $class = 'w-auto';
            $bigtext = 'f14';
            $bTitle = 'Search Results';
            $bDescription = 'High Investment Franchises';
        }
    }elseif(@$_GET['section'] == 'hot-and-trendy-franchises'){
        $class = 'w-100'; 
        $descClass = 'hide'; 
        $bannerImg = 'use_bg';
        $subTitleClass = '';
        $temp_banner = $secondary_array[$_GET['section']]['banner'];
        $banner_background = 'style="--bg-tablet: url(\'/images/hero-images/ffs/featuredmed.jpg\'); --bg-desktop: url(\'/images/hero-images/ffs/featuredlrg.jpg\');"';
        $bTitle = 'Hot & Trendy Franchises';
        $bSubTitle = '<p class="sub-title">Check out the hottest and most exciting franchise concepts available! There is always something new and exciting brewing in the business world. Picture yourself as the owner of one of these exciting franchises or business opportunities - it is time to make your dreams a reality and become a successful entrepreneur!</p>';
        $bDescription = 'Check out the hottest and most exciting franchise concepts available! There is always something new and exciting brewing in the business world. Picture yourself as the owner of one of these exciting franchises or business opportunities - it is time to make your dreams a reality and become a successful entrepreneur!';
        // $bReadMore = '<div id="readMoreTopMore" class="hidden-md"><a class="white-text readMoreToggle" onclick="toggleHidden(\'bannerDesc\',\''.$descClass.'\');">
        // <span></span>Read More About '.$bTitle.'</a></div>';
        $bMore = '<a class="readMoreToggleMedUp gold-text" onclick="toggleBanner(\''.$descClass.'\');"> Read More »</a>';
        $bReadMore = '';
        $subTitleClass = '';
    }elseif(@$_GET['section'] == 'hot-and-trendy' && isset($_GET['landing'])){
        $class = 'md-w-50'; 
        $descClass = 'hide'; 
        $bannerImg = 'use_bg';
        $subTitleClass = '';
        $temp_banner = $secondary_array[$_GET['section']]['banner'];
        $banner_background = 'style="--bg-tablet: url(\'/images/hero-images/ffs/trendingmed.jpg\'); --bg-desktop: url(\'/images/hero-images/ffs/trendinglrg.jpg\');"';
        $bTitle = 'Hot & Trendy Franchises';
        $bSubTitle = '<p class="sub-title larger">Check out the hottest and most exciting franchise concepts available! </p>';
        $bDescription = 'There is always something new and exciting brewing in the business world. Picture yourself as the owner of one of these exciting franchises or business opportunities - it is time to make your dreams a reality and become a successful entrepreneur!';
        $bMore = '<a class="readMoreToggleMedUp gold-text" onclick="toggleBanner(\''.$descClass.'\');"> Read More »</a>';
        $bReadMore = '';
        $subTitleClass = '';
        // $bReadMore = '<div id="readMoreTopMore" class="hidden-md"><a class="white-text readMoreToggle" onclick="toggleHidden(\'bannerDesc\',\''.$descClass.'\');">
        // <span></span>Read More About '.$bTitle.'</a></div>';
    }elseif(@$_GET['investment']){
        global $search_header_results_array, $filterstate;
        $class = 'w-100';
        $hideHide = 'hide'; 
        $descClass = '';
        $bTitle = 'Franchises';
        $investmentBannerCls = 'investment-banner';
        $bgSize = 'bg-auto mh-auto';
        // $subTitleClass = '';
        $bannerImg = 'use_bg';
        $flippedstates = array_flip($filterstate);
        $state_title = ucwords(str_replace("-", " ", $_GET['state_name']));
        $bSubTitle = '<h2 class="white-text">'.ucwords($_GET['range']).' $'.number_format($_GET['investment']).'</h2><p class="white-text headerParagraph larger">Below find franchises and business opportunities under $'.number_format($_GET['investment']).'.</p>';
        // $bSubTitle = '<p class="white-text headerParagraph larger f14 big-text">Searching for franchises with investment '.$_GET['range'].' $'.number_format($_GET['investment']).' and in '.$state_title.'</p>';
        $banner_background = 'style="--bg-tablet: url(\'/images/hero-images/ffs/investment-levelmed.jpg\'); --bg-desktop: url(\'/images/hero-images/ffs/investment-levellrg.jpg\');"';
        $hideMoreClass = 'sub-text';
        $hideTextClass = 'hidden-sm';
        $bReadMore = '';
        $subTitleClass = '';
        // $bReadMore = '<div id="readMoreTop" class="hidden-md"><a class="white-text readMoreToggle" onclick="toggleHidden(\'sub-text\',\'hidden-sm\');">
        // <span></span>Read More About Search Results</a></div>';
        if($_GET['landing']){
            $investment_adj = 'under'; 
            $class = 'w-auto landingIntro';
            if(($_GET['investment'] == 500000 && $_GET['range'] == 'over') || $_GET['investment'] == 500001){
                $investment_adj = 'over';
            }
            $meta_title = 'Franchises '.$investment_adj.' '.investment_k($_GET['investment']).' | FranchiseOpportunities.com';
            $bTitle = 'Search Results';
            $bSubTitle = 'Franchises with investment '.$investment_adj.' $'.number_format($_GET['investment']).'.';
            $bMore = '';
            $bDescription = '';

            if (in_array($_GET['landing_url'],['great-franchises-a','great-franchises-b','great-franchises-c','great-franchises-d','great-franchises-q','great-franchises-r','great-franchises-demo'])) {
                $customTitle = 'Search results';
                $customDesc = 'Searching for franchises with investment '.$investment_adj.' $'.number_format($_GET['investment']).' and in '.$state_title;
                $customClass = 'text-left';
            }
        }
    }else if(@$_GET['cat_name']){
        global $search_header_results_array,$cat_array,$subcat_array;
        $bannerImg = 'use_bg';
        $img_desktop = str_replace('.jpg','lrg.jpg',$search_header_results_array['hero_image']);
        $img_tablet = str_replace('.jpg','med.jpg',$search_header_results_array['hero_image']);
        $banner_background = 'style="--bg-tablet: url(\'/images/hero-images/industry/'.$img_tablet.'\'); --bg-desktop: url(\'/images/hero-images/industry/'.$img_desktop.'\');"';
        $class = 'w-100';
        $descClass = 'hide';
        $bgSize = 'bg-cover';
        $bTitle = $search_header_results_array['seo_h1tag'];
        $bSubTitle = $search_header_results_array['intro_text'];
        $bDescription = $search_header_results_array['into_text3'];
        $hideMoreClass = 'sub-text';
        $hideTextClass = 'hidden-sm';
        $subTitleClass = '';
        if($_GET['sub_cat_name']){
            $bgSize = 'bg-repeat bg-auto';
            $banner_background = 'style="--bg-tablet: url(\'/images/hero-images/industry/default-heromed.jpg\'); --bg-desktop: url(\'/images/hero-images/industry/default-herolrg.jpg\');"';
            $bDescription = $search_header_results_array['intro_text2'];
        }

        if(!is_null($search_header_results_array['intro_text2'])){
            $bDescription .= $search_header_results_array['intro_text2'];
        }
        //from Unstructured SEO
        $seo_array = getSeoData('industry');
        if($_GET['sub_cat_name']){
            $seo_array = getSeoData('sub_cat');
        }
        // debug($seo_array);die;
        
        foreach ($seo_array as $key => $value) {
            // Content
            if ($value['master_types_id'] == 218) {
                $us_seo_content = $value['content']; 
            }
            // List
            if ($value['master_types_id'] == 219) {
                $seo_list_arr = $value['children'];
            }

            if ($value['master_types_id'] == 250) {
                $bSubTitle = $value['content'];
            }
        }
        //end from Unstructured SEO
        $seo_list = '';$seo_list_1 = '';
        if ($seo_list_arr) {
            foreach ($seo_list_arr as $key => $value) {
                $seo_list .= '<li>'.urldecode($value['content']).'</li>';
            }
            $seo_list_1 .= '<div class="black-shade-bg white-border factsPoints">
                <ul class="white-text">'.$seo_list.'</ul>
            </div>';
        }
        if ($us_seo_content) {
            $seo_list_1 .= '<div class="black-shade-bg white-border factsPoints mgt-1">
                <div class="white-text">'.$us_seo_content.'</div>
            </div>';
        }

        if ($seo_list_1) {
            $bDescription = '<div class="stateFacts" id="stateFacts">
                '.$seo_list_1.'
                <br>  
            </div>';
        }elseif(!is_null($search_header_results_array['into_text3'])){
            $bDescription = $search_header_results_array['into_text3'];
        }
        $industryUrl = $search_header_results_array['industry_url'];
        if($_GET['sub_cat_name']){
            $bgSize = 'bg-repeat bg-auto';
            $banner_background = 'style="--bg-tablet: url(\'/images/hero-images/industry/default-heromed.jpg\'); --bg-desktop: url(\'/images/hero-images/industry/default-herolrg.jpg\');"';
        }
        $factsText = 'Hide More About '.$bTitle;
        $hideHide = '';
        $bMore = '<br><a id="readMoreTopMore" class="readMoreToggleMedUp gold-text" onclick="toggleBanner(\'hide\',\'\');">Read More »</a>';
        // $bReadMore = '<div id="readMoreTop" class="hidden-md"><a class="white-text readMoreToggle" onclick="toggleHidden(\'sub-text\',\'hidden-sm\');">
        //         <span></span>Read More About '.$bTitle.'</a></div>';
        $bReadMore = '';
        if (empty($bDescription) || trim($bDescription) == 'NULL') {
            $bMore = '';
            $bReadMore = '';
        }
        if($_GET['landing']){
            global $home_statename;
            $class = 'w-auto landingIntro';
            $bTitle = 'Search Results';
            $hideHide = '';
            $factsText = 'Hide More About Search Results';
            // $bSubTitle = 'Franchises in '.$home_statename.' and the '.$search_header_results_array['seo_h1tag'].' Industry<br>';
            $bSubTitle = '<p class="sub-title larger">Franchises in the '.$search_header_results_array['seo_h1tag'].' Industry</p>';
            $bMore = '';
            $bDescription = '';
            // $bReadMore = '<div id="readMoreTop" class="hidden-md"><a class="white-text readMoreToggle" onclick="toggleHidden(\'sub-text\',\'hidden-sm\');">
            //     <span></span>Read More About Search Results</a></div>';
            if($_GET['cat_name'] == 'low-cost-franchises'){
                $hideHide = 'hidden-sm';
                // $bSubTitle = '<p class="sub-title larger">Franchises in the '.$search_header_results_array['seo_h1tag'].' Industry</p>';
                $bReadMore = '<div id="readMoreTop" class="hidden-md"><a class="white-text readMoreToggle" onclick="toggleHidden(\'sub-text\',\'hidden-sm\',1);">
                <span></span>Read More About Search Results</a></div>';
                $bMore = '<a class="readMoreToggleMedUp hidden-sm gold-text" onclick="toggleBanner(\''.$descClass.'\',\'landing-industry\');"> Read More »</a>';
                $bDescription = 'You don\'t have to be rich to open your own franchise or operate your own business! There are many low cost franchises and business opportunities that allow you to buy-in with a relatively low initial investment. We have great options at many different price points. Explore these low-cost opportunities today!';
            }
            
            if (in_array($_GET['landing_url'],['great-franchises-a','great-franchises-b','great-franchises-c','great-franchises-d','great-franchises-q','great-franchises-r','great-franchises-demo'])) {
                $customTitle = 'Search Results';
                $customDesc = 'Franchises in '.$home_statename.' and the '.$search_header_results_array['seo_h1tag'].' Industry';
                $cat_profile = ''; 
                $customClass = 'text-left';
            }
        }
    }else if(@$_GET['section'] == 'new-franchises'){
        $class = 'md-w-50';
        $hideHide = 'hide';
        $descClass = 'hide';
        $bannerImg = 'use_bg';
        $temp_banner = $secondary_array[$_GET['section']]['banner'];
        $banner_background = 'style="--bg-tablet: url(\'/images/hero-images/ffs/'.$temp_banner.'med.jpg\'); --bg-desktop: url(\'/images/hero-images/ffs/'.$temp_banner.'lrg.jpg\');"';
        $bTitle = 'New To Site - Recently Added Franchises';
        $bReadMore = '';
        $subTitleClass = '';
        $bSubTitle = '<p class="sub-title">Check out the newest franchises posted to our site! We are excited to showcase these exciting new concepts and we hope that you find something that interests you.</p>';
    }else if(@$_GET['state_name']){
        global $filterstate,$search_header_results_array,$banner_background,$stateTitle,$stateDesc;
        // echo $stateTitle;die;
        $bannerImg = 'use_bg';
        $banner_background = 'style="--bg-tablet: url(\'/images/hero-images/'.$_GET['state_name'].'-plotmed.jpg\'); --bg-desktop: url(\'/images/hero-images/'.$_GET['state_name'].'-plotlrg.jpg\');"';
        $flippedstates = array_flip($filterstate);
        $state_title = ucwords(str_replace("-", " ", $_GET['state_name']));
        $state_abbr = $flippedstates[$_GET['state_name']];
        $state_cities = explode(',',$array['cities']);
        $class = 'md-w-50';
        $factsText = 'Hide Fun Facts';
        $descClass = 'hide'; 
        $bgSize = 'bg-auto';
        $hideMoreClass = 'sub-text';
        $hideTextClass = 'hidden-sm';
        $subTitleClass = '';
        $bTitle = $stateTitle;
        $bSubTitle = '<p class="white-text headerParagraph">'.$stateDesc.'</p>';
        $hideHide = '';

        // $bTitle = $state_title.' Franchises';
        // $bSubTitle = '<p class="white-text headerParagraph">Find great franchises and business opportunities for sale in '.$state_title.'. FranchiseForSale.com offers a wide selection of available franchises in '.$state_title.' at many different price points.</p>';
        $bReadMore = '<div id="readMoreTop" class="hidden-md"><a class="white-text readMoreToggle" onclick="toggleHidden(\'sub-text\',\'hidden-sm\');">
                <span></span>Read More About '.$state_title.' Franchises</a></div>';

        //from Unstructured SEO
        $seo_array = getSeoData('state');
        
        foreach ($seo_array as $key => $value) {
            // List with icons 
            if ($value['master_types_id'] == 235) {
                $seo_icons = $value['children'];
            }
            // List
            if ($value['master_types_id'] == 219) {
                $seo_list_arr = $value['children'];
            }
            // Content
            if ($value['master_types_id'] == 218) {
                $us_seo_content = $value['content'];
            }

            if ($value['master_types_id'] == 250) {
                $bSubTitle = $value['content'];
            }
        }
        //end from Unstructured SEO
        $seo_list = '';
        $tmp_seo_list = '';
        if ($seo_list_arr) {
            $bMore = '<br><a id="readMoreTopMore" class="white-text readMoreToggle mt-1" onclick="toggleBanner(\''.$descClass.'\');">
                    <span></span>Click Here to <span>Show</span> fun facts about '.$state_title.'</a>';
            foreach ($seo_list_arr as $key => $value) {
                $seo_list .= '<li>'.urldecode($value['content']).'</li>';
            }
            $tmp_seo_list = '<div class="black-shade-bg white-border factsPoints">
                <ul class="white-text">'.$seo_list.'</ul>
            </div>';
        }elseif($search_header_results_array && $search_header_results_array['bullets']){
            $seo_list = $search_header_results_array['bullets'];
            $tmp_seo_list = '<div class="black-shade-bg white-border factsPoints">
                <ul class="white-text">'.$seo_list.'</ul>
            </div>';
        }
        $seo_content_temp = '';
        if ($us_seo_content) {
            $bMore = '<br><a id="readMoreTopMore" class="white-text readMoreToggle mt-1" onclick="toggleBanner(\''.$descClass.'\');">
                    <span></span>Click Here to <span>Show</span> fun facts about '.$state_title.'</a>';
            $seo_content_temp = '<div class="black-shade-bg white-border factsPoints mgt-1">
                <div class="white-text">'.$us_seo_content.'</div>
            </div>';
        }
        $state_facts = '';
        if ($seo_icons) {
            $bMore = '<br><a id="readMoreTopMore" class="white-text readMoreToggle mt-1" onclick="toggleBanner(\''.$descClass.'\');">
                    <span></span>Click Here to <span>Show</span> fun facts about '.$state_title.'</a>';
            $temp_facts = '';
            foreach ($seo_icons as $key => $value) {
                $temp_content = urldecode($value['content']);
                if (strpos($temp_content,'<cite>') !== false) {
                    $m = getTagValue($temp_content,'cite');
                    $sourceText = $m;
                    $seo_content = substr($temp_content, 0, strpos($temp_content, '<cite>'));
                    $seo_content = urldecode($seo_content);
                }
                $temp_facts .= '<tr class="white-border">
                <td><span class="'.$value['common_type'].'"></span></td>
                <td>'.$sourceText.'</td>
                <td>'.$seo_content.'</td>
            </tr>';
            }
            $state_facts_title = $search_header_results_array['title'] ? '<tr class="black-bg"><th colspan="3">'.$search_header_results_array['title'].'</th></tr>' : '';
            $state_facts = '<table class="white-text black-shade-bg"> 
                    <tbody>
                        '.$state_facts_title.'
                        '.$temp_facts.'
                    </tbody>
                </table>';
        }elseif($search_header_results_array){
            $bMore = '<br><a id="readMoreTopMore" class="white-text readMoreToggle mt-1" onclick="toggleBanner(\''.$descClass.'\');">
                    <span></span>Click Here to <span>Show</span> fun facts about '.$state_title.'</a>';
            $temp_facts = $search_header_results_array['capital'] ? '<tr class="white-border">
                    <td><span class="capital"></span></td>
                    <td>State Capital</td>
                    <td>'.$search_header_results_array['capital'].'</td>
                </tr>' : '';
            $temp_facts .= $search_header_results_array['population'] ? '<tr>
                    <td><span class="population"></span></td>
                    <td>Population</td>
                    <td>'.$search_header_results_array['population'].'</td>
                </tr>' : '';
            $temp_facts .= $search_header_results_array['cities'] ? '<tr class="white-border">
                    <td><span class="largestCity"></span></td>
                    <td>5 Largest Cities</td>
                    <td>'.$search_header_results_array['cities'].'</td>
                </tr>' : '';
            $temp_facts .= '<tr>
                    <td><span class="tree"></span></td>
                    <td>State Tree</td>
                    <td>'.$search_header_results_array['tree'].'</td>
                </tr>';
            $temp_facts .= $search_header_results_array['nickname'] ? '<tr class="white-border">
                    <td><span class="nickname"></span></td>
                    <td>Nickname</td>
                    <td>'.$search_header_results_array['nickname'].'</td>
                </tr>' : '';

            $state_facts_title = $search_header_results_array['title'] ? '<tr class="black-bg"><th colspan="3">'.$search_header_results_array['title'].'</th></tr>' : '';

            if ($temp_facts) {
                $state_facts = '<table class="white-text black-shade-bg"> 
                    <tbody>
                        '.$state_facts_title.'
                        '.$temp_facts.'
                    </tbody>
                </table>';
            }

            $bReadMore = '';
        }

        $bDescription = '<div class="stateFacts" id="stateFacts">
                '.$state_facts.'
                '.$tmp_seo_list.'
                '.$seo_content_temp.'
                <br>  
            </div>';
        
        if($_GET['landing']){
            $bTitle = 'Search Results';
            $bSubTitle = '<h2>Franchises in '.$state_title.'</h2>Find great franchises and business opportunities for sale in '.$state_title.'. FranchiseOpportunities.com offers a wide selection of available franchises in '.$state_title.'.';
            $bMore = '';
            $class = 'w-auto landingIntro';
            $bDescription = '';

            if (in_array($_GET['landing_url'],['great-franchises-a','great-franchises-b','great-franchises-c','great-franchises-d','great-franchises-q','great-franchises-r','great-franchises-demo'])) {
                $customTitle = 'Franchises in '.$state_title;
                $customDesc = ' Find great franchises for sale in '.$state_title.'. Franchiseforsale.com maintains a great inventory of franchises and business opportunities in '.$state_title.' at many different price points. ';
                $customClass = 'text-left';
            }
        }
    }else if(@$_GET['verify'] == true){ 
        $class = 'w-100';
        $bannerImg = 'banner-bg-black';
        $hideHide = 'hide';
        $descClass = 'hide';
        $subTitleClass = '';
        $bTitle = 'Thank you. Your request has been submitted!';
        $bSubTitle = '<p class="sub-title"><p class="headerParagraph green-text"></strong>What\'s next?</strong></p><p> Look for info via text, voice, and email directly from each company.</p>
        <p><strong>Be responsive!</strong> This is more like applying for a job than buying a car, so you need to talk (or text or email) with each company to determine fit.</p>';
    }else if(@$_GET['section'] == 'thank-you'){
        $class = 'w-auto';
        $bannerImg = 'banner-bg-black';
        $hideHide = 'hide';
        $descClass = 'hide';
        $bTitle = 'Thank You';
        $subTitleClass = '';
        $bSubTitle = '<p class="sub-title"><p class="headerParagraph green-text normal-font"><strong>Your requests were successfully received.</strong></p><p class="headerParagraph white-text normal-font">Below are some other franchises you may be interested in based on your recent selections.</p></p>';
    }else if(@$_GET['section'] == 'top-franchises'){
        $class = 'wd-75';
        $hideHide = 'hide';
        $descClass = 'hide';
        $bannerImg = 'use_bg';
        $temp_banner = $secondary_array[$_GET['section']]['banner'];
        $banner_background = 'style="--bg-tablet: url(\'/images/hero-images/industry/'.$temp_banner.'med.jpg\'); --bg-desktop: url(\'/images/hero-images/industry/'.$temp_banner.'lrg.jpg\');"';
        $bTitle = 'The 50 Most Popular Franchises Of '.date('Y').': <br>Our Most Popular Franchises to Buy & Own';
        $bSubTitle = '<p class="sub-title">Based on the number of inquiries each of these franchise and business opportunities received, we have compiled this list of the 50 <b class="orange-text">best franchises to buy and own</b>. The economy is thriving in '.date('Y').'. The environment is right for new business ownership and perhaps this list will inspire you to look into owning your own business in '.date('Y').'! Have fun browsing the list of the most popular franchises of the year on Franchise Opportunities Network. We started with the initial list of the Top 50 franchises, <b class="orange-text">many of which cost less than $25,000</b>, but decided to also include the honorable mentions for the next 25 best franchises to own. <br>** <a href="#footnote" class="white-text"><small>See Footnote</small></a></p>';
        $bReadMore = '';
        $subTitleClass = '';
    } 
 
    if(@$_GET['state_name'] && @$_GET['cat_name'] && @$_GET['investment']){
        global $search_header_results_array;
        $descClass = 'hide';
        $bgSize = 'bg-cover';
        $class = 'w-100';
        $bTitle = 'Search Results'; 
        $bannerImg = 'use_bg';
        $bgSize = 'bg-cover';
        $state_title = ucwords(str_replace("-", " ", $_GET['state_name']));
        if (in_array($_GET['landing_url'],['great-franchises-a','great-franchises-b','great-franchises-c','great-franchises-d','great-franchises-q','great-franchises-r','great-franchises-demo'])) {
            $bgSize = 'bg-cover customInvest';
        }
        if ($_GET['landing_url'] == 'find-a-franchise') {
            $class = 'w-auto';
        }
        $img_desktop = str_replace('.jpg','lrg.jpg',$search_header_results_array['hero_image']);
        $img_tablet = str_replace('.jpg','med.jpg',$search_header_results_array['hero_image']);
        $investmentBannerCls = 'search-results-banner';
        $banner_background = 'style="--bg-tablet: url(\'/images/hero-images/industry/'.$img_tablet.'\'); --bg-desktop: url(\'/images/hero-images/industry/'.$img_desktop.'\');"';
        
        if(isset($_GET['state_name'])){
            $searchText1 = 'in '.ucwords(str_replace("-", " ", $_GET['state_name']));
        }
        if(isset($_GET['cat_name'])){
            $searchText2 = ucwords(str_replace("-", " ", $_GET['cat_name'])).' Industry';
        } 
        if(isset($_GET['investment'])){
            $invRange = 'under';
            if ($_GET['investment'] == 500001) {
                $invRange = 'over';
            }
            $searchText3 = 'with investment '.$invRange.' $'.number_format($_GET['investment']);
        }
        $subTitleClass = '';
        $bMore = ''; 
        $bReadMore = '';
        $bSubTitle = '<p class="sub-title f14 big-text">Franchises '.$searchText3.' and '.$searchText1.' and the '.$searchText2.'</p>';
        if ($_GET['cat_name'] == 'all-industries') {
            $class = 'w-auto';
            $bgSize = 'bg-auto';
            $banner_background = 'style="--bg-tablet: url(\'/images/hero-images/'.$_GET['state_name'].'-plotmed.jpg\'); --bg-desktop: url(\'/images/hero-images/'.$_GET['state_name'].'-plotlrg.jpg\');"';
            $bSubTitle = '<p class="sub-title f14 big-text">Franchises '.$searchText3.' and '.$searchText1.'</p>';
            if ($_GET['landing_url'] == 'find-a-franchise') {
                $class = 'w-49';
            }
        }
        if (in_array($_GET['landing_url'],['great-franchises-a','great-franchises-b','great-franchises-c','great-franchises-d','great-franchises-q','great-franchises-r','great-franchises-demo'])) {
            $customTitle = 'Search Results';
            $customDesc = 'Searching for franchises with investment '.$investment_adj.' $'.number_format($_GET['investment']).' and in '.$state_title.' and the '.$search_header_results_array['seo_h1tag'].' Industry';
            if ($_GET['cat_name'] == 'all-industries') {
                $customDesc = 'Searching for franchises with investment '.$investment_adj.' $'.number_format($_GET['investment']).' and in '.$state_title;
            }
            $customClass = 'text-left';
        }
    }
 
    if(isset($_GET['cat_name']) && !isset($_GET['landing'])){
        $displayImageCredit = '<div class="imageCredit hidden-sm"><a href="/photography-credits"><p class="text-center"><span>📷</span> <br>Photo Credit</p></a></div>';
    }
 
    if ((in_array(@$_GET['landing_url'],['great-franchises-a','great-franchises-b','great-franchises-c','great-franchises-d','great-franchises-q','great-franchises-r','great-franchises-demo'])) && @$_GET['section'] != 'thank-you') {
        $customTitle = $customTitle ? $customTitle : '';
        $customTitleH1 = $customTitle ? '<h1 class="h2 white-text">'.$customTitle.'</h1>' : '';
        $tempbanner = '<div id="banner" class="matter-bg custom-search-banner text-center">
            <div class="container">
                <div class="banner-row">';
                $landing_array = explode('/',$_SERVER['REQUEST_URI']);
                $landing_array = array_filter($landing_array);
                if(is_array($landing_array)){
                    if(count($landing_array) == 1){
                        global $landing_interiors;
                        $tempbanner .= '<div id="person_picture" class="columns medium-6 large-5 vertical-center-custom">
                                            <img class="float-center custom-hero" src="'.$customImg.'" alt="'.$customTitle.'">
                                        </div>';
                        $landing_interiors = false;
                    }
                }
        $tempbanner .= '<div class="columns medium-6 large-7 vertical-center-custom xy-padded"> 
                        <div class="start-align align-item"></div>
                        <div class="middle-align align-item '.$customClass.'">
                            '.$customTitleH1.'
                            <div class="white-text">'.$customDesc.'</div>
                            <div class="hide">
                                <a class="gold-text" id="toggleReviseSearch" onclick="toggleReviseSearch();"><u>Click Here</u></a>
                                <span class="white-text"> if you want to revise your search.</span>
                            </div>
                        </div>
                        <div class="end-align align-item"></div>
                    </div>
                </div>
            </div>
        </div>';
    }else{
        global $top_html;
        if ($top_html) {
            $bDescription = $top_html;
            $class = 'w-100';
            $bMore = '<a class="readMoreToggleMedUp gold-text" onclick="toggleBanner(\''.$descClass.'\',\'landing-industry\');"> Read More »</a>';
        }
        if($_GET['cat_state']){
            global $state_title;
            $commonwealths = array('Virginia','Massachusetts','Pennsylvania','Kentucky');
            $state_type = (in_array($state_title, $commonwealths)) ? 'commonwealth' : 'state';
            $bSubTitle = $bTitle.' for sale, near me, in the '.$state_type.' of '.$state_title;
            $bTitle .= ' in '.$state_title;
        }
        $tempbanner = '<div id="banner" class="results-banner-all '.$bannerImg.' '.$investmentBannerCls.' '.$bgSize.'" '.$banner_background.'>
            <div class="container">
                <div class="introBack banner-intro-bg '.$class.'">
                    <h1>'.$bTitle.'</h1>'.$bReadMore.'<div id="sub-text" class="'.$subTitleClass.'"><span>'.$bSubTitle.'</span>'.$bMore.'
                    <div id="bannerDesc" class="paragraphWrapper '.$descClass.'"> 
                        <div><div class="white-text headerParagraph '.$bigtext.'">'.$bDescription.'</div>
                        <a class="white-text readMoreHideSmall '.$hideHide.'" onclick="toggleBanner(\''.$descClass.'\',\''.$_GET['section'].'\');">'.$factsText.'</a>
                        </div>
                    </div>
                </div> 
            </div>
            '.$displayImageCredit.'
            </div>
        </div>';
    }

    echo $tempbanner;
}

function breadcrumbs($array = []){
    global $baseURL;
    $page_url = $baseURL.'/'.$state_name;  
    $return = '';
    $i = 1;
    $numItems = count($array);
    $j = 0;
    foreach($array as $key => $value){
        if(++$j !== $numItems) {
            $temp_url = $baseURL.$value['url'];
        }else{
            $temp_url = $value['url'];
        }
        
        $return .= '<li itemscope itemprop="itemListElement" itemtype="http://schema.org/ListItem">';
        $return .= '<a itemprop="item" href="'.$temp_url.'">';
        $return .= '<span itemprop="name">'.$value['title'].'</span>';
        $return .= '</a><meta itemprop="position" content="'.$i.'"></li>';
        $i++; 
    }
    // echo '<ol id="breadcrumbs" itemscope="" itemtype="http://schema.org/BreadcrumbList" class="clear">'.$return.'</ol>';
    echo $return;
}

$breadcrumbs = [
    0=>['title'=>'Home','url'=>@$homeUrl]
];

$stateTitle = '';
$stateDesc = '';
$top_html = '';
$bottom_html = '';
$stylesheet_includes = '';
$js_includes = '';
function header_meta($optional_title=null){
    global $site_id,$website_id,$filterstate,$search_header_results_array,$meta_title_override,$description_override,$keywords_override,$secondary_array,$checker,$profile,$stateTitle,$stateDesc,$top_html,$bottom_html,$stylesheet_includes,$js_includes;
    $flippedstates = array_flip($filterstate);
    $url = $_SERVER['REQUEST_URI'];
    $parts = explode('/', $url); 
    $total = count($parts)-1;
    $return = '';
    $return .= "\r\n";
    $meta_title = ($optional_title!=null)?$optional_title:'Franchise Opportunities: Find Franchises For Sale';
    $description = 'Search for franchises opportunities for sale. Browse franchises by industry, state or investment level on FranchiseOpportunities.com.';
    $keywords = 'franchise for sale,franchise opportunity,business opportunity,franchises,business for sale,search,find,locate,discover';
    if(isset($_GET['cat_name']) || isset($_GET['state_name'])|| isset($_GET['investment'])){
        if(isset($_GET['investment'])){
            $get_id = $_GET['investment'];
            if(($_GET['investment'] == 500000 && $_GET['range'] == 'over') || $_GET['investment'] == 500000){
                $get_id = 99999999;
            }
            $get_type = 'investment';   
        }
        if(isset($_GET['state_name'])){
            $get_id =  strtolower($flippedstates[$_GET['state_name']]);
            $get_type = 'state';
        }
        if(isset($_GET['cat_name'])){
            global $cat_array;
            $get_id = $cat_array['data']['category_id'];
            $get_type = 'category';
        }
        if(isset($_GET['sub_cat_name'])){
            global $subcat_array;
            $get_id = $subcat_array['data']['sub_cat_id'];
            $get_type = 'category';
        }
        // echo '/api/get_fo_meta/'.$get_type.'/'.$get_id.'/'.$website_id;die;
        $temp_array = get_json('/api/get_fo_meta/'.$get_type.'/'.$get_id.'/'.$website_id,true);
        $temp_array = json_decode($temp_array,true);
        $array = $temp_array['data']['0'];
        // echo '<pre>';
        // print_r($array);
        // die;
        $top_html = html_entity_decode($array['top_html']);
        $bottom_html = html_entity_decode($array['bottom_html']);
        $stylesheet_includes = $array['stylesheet_includes'];
        $js_includes = $array['js_includes'];
    }else{
        //DO NOTHING
    }
    if(@$blog){
        global $blog_tdescription, $blog_title;
        $og_title = $blog_title;
        $og_description = $blog_description;
    }
    if (isset($_GET['page'])) {
        $pageText = ' - Page '.$_GET['page'];
    }

    if (is_array(@$array)) {
        $search_header_results_array = $array;
    }

    if (in_array(@$_GET['landing_url'],['great-franchises-a','great-franchises-b','great-franchises-c','great-franchises-d','great-franchises-q','great-franchises-r','great-franchises-demo'])) {
        $meta_title = 'Search Franchises for Sale '.@$pageText.' | FranchiseForSale.com';
        $description = 'Search for franchises, franchise information and business opportunities on Franchiseforsale.com.   Great selection, search now!'.@$pageText;
        $keywords = 'franchise for sale,franchise opportunity,business opportunity,franchises,business for sale,search,find,locate,discover';
    }elseif(isset($_GET['state_name']) && !isset($_GET['cat_name'])){
        $temp_array = get_json('/api/meta_fv_states/'.$website_id.'/state/'.$_GET['state_name'],false);
        $temp_array = json_decode($temp_array,true);
        $temp_array = $temp_array['data'][0];
        $state_title = ucwords(str_replace("-", " ", $_GET['state_name']));
        if ($temp_array['meta_title']) {
            $meta_title = $temp_array['meta_title'];
        }else{
            $meta_title = 'Franchises for Sale in '.$state_title.$pageText.' | FranchiseForSale.com';
        }
        if ($temp_array['meta_description']) {
            $description = $temp_array['meta_description'];
        }else{
            $description = 'Search franchises for sale in '.$state_title.'. FranchiseForSale.com provides a fantastic selection of franchises and business opportunities for sale in '.$state_title.$pageText;
        }
        if ($temp_array['meta_keyword']) {
            $keywords = $temp_array['meta_keyword'];
        }else{
            $keywords = 'franchises for sale in '.$state_title.',state search,businesses for sale in '.$state_title;
        }
        $stateTitle = $temp_array['page_h1'];
        $stateDesc = html_entity_decode($temp_array['intro_text']);
        $top_html = html_entity_decode($temp_array['top_html']);
        $bottom_html = html_entity_decode($temp_array['bottom_html']);
        $stylesheet_includes = $temp_array['stylesheet_includes'];
        $js_includes = $temp_array['js_includes']; 

        if ($temp_array['capital'] || $temp_array['population'] || $temp_array['cities'] || $temp_array['tree'] || $temp_array['nickname']) {
            $search_header_results_array = $temp_array;
        }

        // $state_title = ucwords(str_replace("-", " ", $_GET['state_name']));
        // $state_abbr = $flippedstates[$_GET['state_name']];
        // $state_cities = explode(',',$array['cities']);
        // array_splice($state_cities, count($state_cities) - 2, 2);
        // $state_cities = implode(',',$state_cities);
        // $meta_title = 'Franchises for Sale in '.$state_title.$pageText.' | FranchiseForSale.com';
        // $description = 'Search franchises for sale in '.$state_title.'. Open your own franchise today and browse our opportunities in '.$state_cities.' and more -- new listings daily.'.$pageText;
        // $keywords = 'franchises for sale in '.$state_title.',state search,businesses for sale in '.$state_title;
    }elseif(is_array(@$array)){
        if(isset($_GET['investment'])){
            $adjective = 'amazing';
            $investment_adj = 'Under'; 
            // if($_GET['investment'] == 10000){
            //     $adjective = 'low cost';
            // }
            if($_GET['investment'] == 500000 && $_GET['range'] == 'over'){
                $investment_adj = 'Over';
            } 
            $meta_title = 'Franchises For Sale '.$investment_adj.' $'.number_format($_GET['investment']).' '.$pageText.' | FranchiseForSale.com';
            $description = 'Discover '.$adjective.' franchises for sale '.strtolower($investment_adj).' '.investment_k($_GET['investment']).'. We have a wide range of great franchises and businesses available for sale to fit your specific budget.'.$pageText;
            $keywords = 'franchises '.strtolower($investment_adj).' $'.number_format($_GET['investment']).',investment level search,business opportunities,business for sale,search by asking price';
        }else{
            $titleArray = explode('|',$array['seo_title_tag']);
            $meta_title = $titleArray[0].' '.$pageText.' | '.$titleArray[1];
            $description = $array['seo_meta_description'].$pageText;
            $keywords = $array['seo_meta_keywords'];

            if (isset($_GET['cat_name'])) {
                if (in_array($_GET['landing_url'],['great-franchises-a','great-franchises-b','great-franchises-c','great-franchises-d','great-franchises-q','great-franchises-r','great-franchises-demo'])) {
                    $meta_title = 'Search Franchises for Sale by Industry Category '.$pageText.' | FranchiseOpportunities.com';
                    $description = 'Search franchises and business opportunities for sale by industry category on FranchiseOpportunities.com. Great selection all price points - search now!'.$pageText;
                    $keywords = 'food franchise,category search,business opportunities,food business for sale,restaurant for sale,search by food industry';
                }
            }
        }
    }elseif(array_key_exists(@$_GET['section'],$secondary_array)){
        $meta_title = $secondary_array[$_GET['section']]['meta_title'];
        $description = $secondary_array[$_GET['section']]['description'];
        $keywords = $secondary_array[$_GET['section']]['keywords'];
    }else{
        //DO NOTHING
    } 

    if (@$_GET['state_name'] == 'international' || @$_GET['state_name'] == 'canada') {
        $meta_title = 'Franchises for Sale in '.ucfirst($_GET['state_name']).' () | FranchiseOpportunities.com';
        $description = 'Franchises for Sale in '.ucfirst($_GET['state_name']).'. FranchiseOpportunities.com provides a great selection of franchises for sale in '.ucfirst($_GET['state_name']).'. Search in your State for a franchise or business opportunity to buy. Search now!';
        $keywords = 'franchises for sale in '.ucfirst($_GET['state_name']).',state search,businesses for sale in '.ucfirst($_GET['state_name']).'';
    }

    if(isset($profile)){
        $meta_type = 'Franchise Opportunity';
        if($profile['type'] == 'opportunity'){
            $meta_type = 'Business Opportunity';
        }
        $meta_title = htmlspecialchars($profile['name']).' '.$meta_type.' | FranchiseForSale.com';
        $description = htmlspecialchars($profile['name']).' '.$profile['type'].' information - Looking to buy '.first_letter($profile['category_name']).str_replace('franchises','franchise',strtolower($profile['category_name'])).'? Learn more about this fantastic '.$profile['type'].' and how you can own one!';
    }

    if(isset($meta_title_override)){
        $meta_title = $meta_title_override;
    }
    if(isset($description_override)){
        $description = $description_override;
    }
    if(isset($keywords_override)){
        $keywords = $keywords_override;
    }

    // $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $actual_link = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $actual_link = strtok($actual_link, '?');
    $return .= '<title>'.$meta_title.'</title>'."\r\n";
    $return .= '<meta name="description" content="'.$description.'" >'."\r\n";
    $return .= '<meta name="keywords" content="'.$keywords.'" >'."\r\n";
    $return .= '<meta property="og:title" content="'.$meta_title.'" >'."\r\n";
    $return .= '<meta property="og:description" content="'.$description.'" >'."\r\n";
    $return .= '<meta property="og:type" content="website" >'."\r\n";
    $return .= '<meta property="og:url" content="'.$actual_link.'" >'."\r\n";
    $return .= '<meta property="og:image" content="https://franchise-ventures-general.s3.amazonaws.com/cdn_ffs/images/og-ffs-logo.png" >'."\r\n";
    $return .= '<meta property="twitter:title" content="'.$meta_title.'" >'."\r\n";
    $return .= '<meta property="twitter:description" content="'.$description.'" >'."\r\n";
    $return .= '<link rel="canonical" href="'.$actual_link.'" >'."\r\n";

    echo $return;
}

function investment_k($value){
    $temp_value = substr($value, 0, -3);
    return '$'.$temp_value.'K'; 
}

$secondary_array = [
    'featured-franchises' => [
        'meta_title' => 'Featured Franchises for Sale | FranchiseForSale.com',
        'description' => 'Check out these Fantastic Featured Franchises for Sale. Search on FranchiseForSale.com to find a wide selection of franchises and business opportunities -- find your dream business to buy today - search now!',
        'keywords' => 'featured franchises for sale',
        'banner' => 'featured'
    ],
    'low-cost-franchises' => [ 
        'meta_title' => 'Low Cost Franchise Opportunities | FranchiseForSale.com',
        'description' => 'Find a great selection of low cost franchise opportunities for as low as $5K. Browse affordable franchises for sale, and start your own business today.',
        'keywords' => 'low cost franchises,affordable,inexpensive,budget friendly,franchises for sale',
        'banner' => 'low-cost'
    ],
    'alphabetical-company-search' => [
        'meta_title' => 'Alphabetical and Company Search | FranchiseForSale.com',
        'description' => 'Search alphabetically for a particular franchise company or do a specific company search on FranchiseForSale.com. Search now and find your dream business to buy!',
        'keywords' => 'franchises for sale'
    ],
    'hot-and-trendy-franchises' => [
        'meta_title' => 'Hot & Trendy Franchises & Business Opportunities | FranchiseForSale.com',
        'description' => 'Discover Hot, New and Trendy Franchises. Search the newest, hottest, &amp; most popular franchise brands &ndash; new listings posted daily!',
        'keywords' => 'trendy franchises,trending,popular,hot franchises,trendy',
        'banner' => 'trending'
    ],
    'new-franchises' => [
        'meta_title' => 'New to Site - Recently Added Franchises | FranchiseForSale.com',
        'description' => 'Search the newest Franchises on FranchiseForSale.com. Check out these recently added franchises and business opportunities for sale. New listings posted daily! Search now to find your dream business to buy!',
        'keywords' => 'franchises,new to site,new postings,recently added franchises',
        'banner' => 'new-to-site'
    ],
    'top-franchises' => [
        'meta_title' => 'The 50 Top Franchises To Buy For '.date('Y').' | FranchiseForSale.com',
        'description' => 'Discover the top 50 franchises of '.date('Y').' on FranchiseForSale.com. We compiled a list of our most popular franchises using data from our internal inquiries.',
        'keywords' => 'popular franchises,top franchises',
        'banner' => 'top-franchises'
    ]
];

//PROFILE NAME
function profile_name($name,$start = true){
    $return = strip_tags($name);
    $return = str_replace("&39;","&apos;",$return);
    $return = str_replace("'","&apos;",$return);
    $return = str_replace("&2013266094;","&reg;",$return);
    if($start == true){
        $return = stripslashes($return);                   
    }else{
        $return = htmlspecialchars_decode($return);              
    }
    $return = strip_tags($return);
    $return = preg_replace( "/\r|\n/", "", $return );
    $replace = array('®' => '<sup>®</sup>');
    $return = strtr($return, $replace);
    return $return;
}

//TRIM Descriptions
function truncate($string,$length=260,$append="&hellip;") {
    $string = trim($string);
  
    if(strlen($string) > $length) {
      $string = wordwrap($string, $length);
      $string = explode("\n", $string, 2);
      $string = $string[0] . $append;
    }
  
    return $string;
}

//CREATE BLANK AND NULL VALUES
function return_default_values($array){
    // EXAMPLE ARRAY
    // ['variable'=>'name','value'=>null,'default'=>'GET']
    foreach($array as $key => $value){
        global ${$value['variable']};
        ${$value['variable']} = $value['value'];
        if(!is_null($value['default'])){
            if(isset($$value['default'])){
                ${$value['variable']} = $$value['default'];
            }
        }
    }
}

//RETURN $homeState VARIABLE
function return_home_state(){
    global $geo,$filterstate,$legacy_mapping,$flipStates;
    $return = null;
    if(isset($_GET['qstate'])){
        $_GET['state']=$_GET['qstate'];
        $_GET['state_name']=$filterstate[$_GET['state']];
        $return = $_GET['state_name'];
    }
    if(isset($geo['state'])){
        $temp = $geo['state'];
        if($geo['country'] == 'ca'){
            $temp = 'CAN';
        }
        $return = strtoupper($temp);
    }
    if(isset($_COOKIE['state_name'])){
        $return = $flipStates[$_COOKIE['state_name']];
    }
    if(isset($_GET['state_name'])){
        $return = $flipStates[$_GET['state_name']];
    }
    if(isset($_GET['state'])){
        $return = strtoupper($_GET['state']);
    }
    if(isset($_POST['zipcode'])){
        $zip_code = '/api/statelookup/zip/'.$_POST['zipcode'];
        $array = get_json($zip_code,true);
        $array = json_decode($array,true);
        $temp_return = $array['data'][0];
        $return = strtoupper($temp_return['state']);;
    }
    if(!array_key_exists($return,$filterstate)){
        $return = 'CA';
    }
    return htmlspecialchars($return);
}

//SEARCH RESULTS CONNECTION
function return_results_array($website_id,$paid,$filter = '',$json = false){
    global $prefix,$homeState,$email,$rList,$requested,$invest,$cat_id,$page,$api_url,$site_id,$master_category_mapping;
    $url = $prefix.'.com/'.$website_id.'/fv-searchresults.php?website_id='.$website_id.'&lead_smoothing_results=1&paid='.$paid.$rList;

    //Quiz Filtering
    $quiz_mapping = ['qmin'=>'min','qmax'=>'max','qstate'=>'state_code','qff'=>'ff','qcat_id'=>'cat_id'];
    foreach($quiz_mapping as $key => $value){
        if(isset($_GET[$key])){
            $url .= '&'.$value.'='.$_GET[$key];
        }
    }
    if(isset($_GET['quiz'])){
        $url .= '&quiz='.$_GET['quiz'];
    }
    if(isset($requested)){
        $url .= '&requestlist='.$requested;
    }
    if(isset($homeState) && (@$_GET['section'] != 'directory')){
        $url .= '&state_code='.$homeState;
    }
    if(isset($invest)){
        if($invest == 1000000){
            $url .= '&min=1000000&max=9999999';
        }else{
            $url .= '&min=0&max='.$invest;
        }
    }
    if(isset($cat_id)){
        $url .= '&cat_id='.master_mapping($cat_id);
    }
    if(isset($email)){
        $url .= '&email_address='.urlencode($email);
    }
    if ($filter) {
        $url .= $filter;
    }
    if(isset($page)){
        $url .= $page;
    }
    // echo $url;die;
    if($json && isset($_GET['showsynd'])){
        if($_GET['showsynd'] == 'yes'){
            load_css('demo');
            echo '<p class="view_demo_json"><a href="'.$url.'" target="_blank"><svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd"><path d="M10.409 0c4.857 0 3.335 8 3.335 8 3.009-.745 8.256-.419 8.256 3v11.515l-4.801-4.801c.507-.782.801-1.714.801-2.714 0-2.76-2.24-5-5-5s-5 2.24-5 5 2.24 5 5 5c1.037 0 2-.316 2.799-.858l4.858 4.858h-18.657v-24h8.409zm2.591 12c1.656 0 3 1.344 3 3s-1.344 3-3 3-3-1.344-3-3 1.344-3 3-3zm1.568-11.925c2.201 1.174 5.938 4.884 7.432 6.882-1.286-.9-4.044-1.657-6.091-1.18.222-1.468-.186-4.534-1.341-5.702z"/></svg> View Endpoint JSON</a></p>';
        }
    }else{
        $json = file_get_contents($url);
        return  json_decode($json, true); 
    }
}

function truncateString($str, $to_space, $replacement="...") {
    $chars = 500;
    if($chars > strlen($str)) return $str;
 
    $str = substr($str, 0, $chars);
    $space_pos = strrpos($str, " ");
    if($to_space && $space_pos >= 0) 
        $str = substr($str, 0, strrpos($str, " "));
 
    return($str . $replacement);
}

//Hide Bottom Bar
function hide_bottom_bar($section){
    $return = null;
    $array = ['advertise-with-us','contact-us'];
    if(in_array($section,$array)){
        $return = '<style>body div#bottom{display:none !important;}</style>';
    }
    return $return;
}

// Guidant checkbox check
function checkbox_tool(){
    global $site_id;
    $data=array();
    $checkbox_data=get_json('/api/get_data_checkbox?site_id='.$site_id);
    $checkbox_data=json_decode($checkbox_data, TRUE);
    // debug($checkbox_data);
    if($checkbox_data['status']=='success'){
        $data=$checkbox_data['data'];
    }
    // die;
    return $data;
}

//Default Search Value
function search_value(){
    global $filterstate,$prepend_url,$homeState;
    $prepend = $prepend_url;
    if($prepend != '' && !is_null($prepend)){
        $prepend = rtrim($prepend,'/');
        $first = substr($prepend, 0, 1);
        if($first != '/'){
            $prepend = '/'.$prepend;
        }
    }
    $return = $prepend.'/state/'.$filterstate[$homeState].'-franchises';
    if((isset($_REQUEST['cat_name']) || isset($_REQUEST['investment'])) && !isset($_GET['thankyou'])){
        $cat = $_REQUEST['cat_name'];
        if(is_null($_REQUEST['cat_name'])){
            $cat = 'all-industries';
        }
        $return = $prepend.'/industry/'.$cat;
        if(isset($_REQUEST['investment'])){
            $return .= '/state/'.$filterstate[$homeState].'/investment/'.$_REQUEST['investment'];
        }else{
            $flipped = array_flip($filterstate);
            // debug($flipped);die;
            $return .= '?state='.$homeState;
        }
    }
    return $return;
}

//CREATE FOOTER COLUMNS
function footer_columns($name,$column,$paid = false){
    $list = '';
    $class = 'col-xs-12 col-sm-6 col-md-3';
    if($paid){
        $class = 'col-sm-12 col-md-6';
    }
    $links = [
        0 => [
            ['url'=>'/industry','target'=>null,'text'=>'By Industry'],
            ['url'=>'/investment-level','target'=>null,'text'=>'By Investment Level'],
            ['url'=>'/state','target'=>null,'text'=>'By State'],
            ['url'=>'/alphabetical-company-search','target'=>null,'text'=>'Alphabetical Search'],
            ['url'=>'/site-map','target'=>null,'text'=>'Site Map']
        ],
        1 => [
            ['url'=>'/newsletter-sign-up','target'=>null,'text'=>'Newsletter Sign-Up'],
            ['url'=>'/resources/net-worth-calculator','target'=>null,'text'=>'Net Worth Calculator'],
            ['url'=>'/photography-credits','target'=>null,'text'=>'Photography Credits'],
            ['url'=>'/resources/frequently-asked-franchising-questions','target'=>null,'text'=>'F.A.Q.'],
            ['url'=>'http://www.smallbusinessstartup.com/','target'=>'_blank','text'=>'Small Business Startup']
        ],
        2 => [
            ['url'=>'/about-us','target'=>null,'text'=>'About Us'],
            ['url'=>'/advertise-with-us','target'=>null,'text'=>'Advertise With Us'],
            ['url'=>'http://franchiseinsights.com/','target'=>'_blank','text'=>'Franchising Insights'],
            ['url'=>'/contact-us','target'=>null,'text'=>'Contact Us'],
            ['url'=>'/privacy-website-usage-policy','target'=>null,'text'=>'Privacy Policy'],
            ['url'=>'/privacy-website-usage-policy?interest-ads','target'=>null,'text'=>'Interest-Based Ads']
        ],
        3 => [
            ['url'=>'/privacy-website-usage-policy','target'=>null,'text'=>'Privacy Policy'],
            ['url'=>'/about-us','target'=>'_blank','text'=>'About Us'],
            ['url'=>'/contact-us','target'=>'_blank','text'=>'Contact Us'],
            ['url'=>'/','target'=>'_blank','text'=>'View Full Site']
        ]
    ];
    foreach($links[$column] as $key => $value){
        $target = null;
        if($value['target']){
            $target = ' target="'.$value['target'].'"';
        }
        if(substr($value['url'], 0, 1) != '/'){
            $target .= ' rel="noreferrer"';
        }
        $list .= '<li><a'.$target.' href="'.$value['url'].'">'.$value['text'].'</a></li>';
    }
    return '<div class="'.$class.'"><p class="h5">'.$name.'</p><ul class="no-bullet" itemscope="" itemtype="http://www.schema.org/SiteNavigationElement">'.$list.'</ul></div>';
}

if ($callFboChecker) {
    $fboid_checker = get_json('/api/profiles_mapping/'.$site_id.'?pending=no&fboid=yes');
    $fboid_checker = json_decode($fboid_checker,true);
}

function checkIfFboIdExists($fboId,$concept_site_id,$url) {
    global $site_id,$fboid_checker;
    $url = strtolower($url);
    if ($concept_site_id == $site_id) {
        return $url;
    }elseif (array_key_exists($fboId, $fboid_checker['data'])){
        $url = $fboid_checker['data'][$fboId];
        return $url;
    }else{
        return '';
    }
}

function youtubeEmbedUrl($url) {
    if (strpos($url, 'youtube') !== false) {
        $url = str_replace("watch?v=", "embed/", $url);
    }
    return $url;
}