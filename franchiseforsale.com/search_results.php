<?php
$callFboChecker = true;
include_once('includes/global.php');
$top_prefix = '';
$population = '';
$get_section = '';
$searchSubcat = '';
$filter = '';
$banner = '';
$alphaLetters = '';
$displayFilter = true;
$investment_titles = [
    'low-cost' => ['title'=>'Businesses under $50k',
    'filter'=>'&min=0&max=50000',
    'description'=> 'Are you interested in finding out what low cost franchise you can buy? Getting into franchise ownership may cost you less than you think. The following low cost franchises and business opportunities are looking to expand and new need owners (like you?) to increase their footprint across the United States. If you are interested in taking the next step to learn more about any of the following companies simply select them by tapping “get free info” and completing your request for information by first tapping the “request now” button.'],
    'cost-50k-100k' => ['title'=>'Businesses between $50k and $100k',
    'filter'=>'&min=50000&max=100000',
    'description'=>'How much does a franchise cost? Good question. Here is a list of businesses and franchises that cost between $50,000 and $100,000. These companies are seeking new owners to help them expand their brand and open new locations. If you are interested in taking the next step to learn more about any of these companies you can select them by tapping “get free info” and completing your request for ownership information by first tapping the “request now” button.'],
    'cost-100k-200k' => ['title'=>'Businesses between $100k and $200k',
    'filter'=>'&min=100000&max=200000',
    'description'=>'If you are interested in getting into franchise ownership, and you know would like to know what an investment between $100,000 and $200,000 you here are some franchises you may want to considering getting more information on. It’s easy to take the next step to get franchise information on any of these companies. Simply tap “get free info” below any of the companies you would like to learn more about and then tap the “request more” button to open up the form to complete your request for ownership information.'],
    'cost-200k' => ['title'=>'Businesses over $200k',
    'filter'=>'&min=200000&max=9999999',
    'description'=>'Some of the most iconic brands are franchises that cost over $200,000. They require larger investments usually because there are more costs associated with opening your location (for instance large footprints, building and/or land costs). Ongoing support and training is another reason why a franchise is more expensive than a business opportunity. The following businesses cost over $200,000 and are looking for new owners to help them expand across the United States. If you are interested in taking the next step to learn more about any of the following companies simply select them by tapping “get free info” and completing your request for information by first tapping the “request now” button.']
];
$cat_array = [];
$subcat_array = [];
//echo make_categories($value['category_name']);die;

if($_GET['landing_url']){ 
    unset($breadcrumbs);
    $breadcrumbs = array();
    $breadcrumbs['1']['title'] = 'Home';
    $uRL = '';
    if ($_GET['landing_url'] == 'find-a-franchise') {
        $uRL = '/search';
    }
    $breadcrumbs['1']['url'] = $_GET['landing_url'].$uRL;
} 

if ($_GET['landing']) {
    $landingUrl = $prepend_url;
}else{
    $landingUrl = '';
}

if(isset($_GET['sub_cat_name']) || isset($_GET['cat_name'])){
    $breadcrumbs['2']['title'] = 'Industries';
    $breadcrumbs['2']['url'] = $landingUrl.'franchise-categories-all';
    $cat_mapping = [];
    $subcat_mapping = [];
    $site_categories_url = $api_url.'/api/site_categories/site/'.$site_id.'/all';
    $site_categories = file_get_contents($site_categories_url);
    $site_categoriesObj = json_decode($site_categories, true);  
    
    foreach($site_categoriesObj['data'] as $key => $value){
        $temp_key = make_categories($value['category_name']); 
        $cat_mapping[$temp_key]['id'] = $value['category_id'];
        $cat_mapping[$temp_key]['data'] = $value;
        foreach($value['sub_cats'] as $key1 => $value1){
            $temp_key1 = make_categories($value1['sub_cat_name']);
            $subcat_mapping[$temp_key1]['id'] = $value1['sub_cat_id'];
            $subcat_mapping[$temp_key1]['data'] = $value1;
            $cat_mapping[$temp_key]['subcat'][$temp_key1] = $value1['title'];
        }
    }
    // debug($cat_mapping);die;
    if(isset($_GET['cat_name'])){
        $cat_array = $cat_mapping[$_GET['cat_name']];
        $_POST['category'] = $cat_array['id'];
        $breadcrumbs['2']['title'] = 'Industry';
        $breadcrumbs['2']['url'] = $landingUrl.'industry';
        // $breadcrumbs['3']['title'] = $cat_array['data']['title'];
        // $breadcrumbs['3']['title'] = ucwords(str_replace("-", " ",$_GET['cat_name']));
        $breadcrumbs['3']['title'] = $flipMappedCategories[$_GET['cat_name']];
        if($_GET['cat_name'] == 'recreation-sports-franchises'){
            $breadcrumbs['3']['title'] = 'Sports & Recreation Franchises';
        }
        $breadcrumbs['3']['url'] = '';
        $cat_id = $cat_array['id'];
        // debug($cat_id);die;
        if($_GET['landing']){
            $breadcrumbs['3']['title'] = 'Search Results';
        }
    }
    if(isset($_GET['sub_cat_name'])){
        $subcat_array = $subcat_mapping[$_GET['sub_cat_name']];
        $filter .= '&subcat='.$subcat_mapping[$_GET['sub_cat_name']]['id'];
        $breadcrumbs['2']['title'] = 'Industry';
        $breadcrumbs['2']['url'] = $landingUrl.'industry';
        $breadcrumbs['3']['title'] = $subcat_array['data']['title'];
        // $breadcrumbs['3']['url'] = $breadcrumbs['2']['url'].'/'.$_GET['sub_cat_name'];
        $breadcrumbs['3']['url'] = '';
    }
}
$showMarginTop = '';
$backToTop = '<a id="backToTop" href="javascript:void(0);" onclick="topFunction();" class="back-to-top dark-gray-border white-bg" style="display: inline;">TOP ▲</a>';
//var_dump($cat_mapping);die;
//Submit Leads from Request Form
if($_GET['thankyou'] == true){
    $breadcrumbs['2']['title'] = 'Thank You';
    // $breadcrumbs['2']['url'] = 'thankyou';
        $global_robots = 'noindex, follow';
    $breadcrumbs['2']['url'] = '';
    if($_POST['request'] == 'accept'){
        $add_page_type = 'thank_you';
        $rList = '&rtype=1';
        include_once('includes/submission.php');
        include_once('includes/thankyou.php'); 
        // if (session_status() === PHP_SESSION_NONE) {
        //     session_start();
        // }
        // if(isset($_SESSION['duplicate_checker'])){
        //     //Already submitted the cart
        // }else{
        //     $_SESSION['duplicate_checker'] = "processed"; 
        //     session_write_close();
        // }
    }
 
}else if($_GET['verify'] == true){
    $breadcrumbs['2']['title'] = 'Thank You';
    // $breadcrumbs['2']['url'] = 'verify';
    $breadcrumbs['2']['url'] = '';
    if (is_numeric($_GET['check'])) {
        $_REQUEST['dev-live'] = explode(".",$_SERVER['SERVER_NAME']);
        if ($_REQUEST['dev-live'][0] == 'dev' || $_REQUEST['dev-live'][1] == 'whitelabeldev' || strpos($_SERVER['SERVER_NAME'], "franchiseportals") !== false){
            $path = 'https://pet.franchiseportals.com/';
        }else{
            $path = 'https://franchiseinsights.franchiseportals.com/';
        }
        $path .= 'api/userconfirmation?confirmation=yes&phase='.$_GET['phase'].'&tracking_id='.$_GET['check'].'&is_email='.$_GET['type'];
        $verify = file_get_contents($path);
    }
}else if(isset($_GET['section'])){
    $get_section = $_GET['section'];
    // $breadcrumbs['2']['url'] = $get_section;
    $breadcrumbs['2']['url'] = '';
    if($_GET['section'] == 'featured-franchises'){
        $get_section = $_GET['section'];
        $breadcrumbs['2']['title'] = 'Featured';
        // $breadcrumbs['2']['url'] = $get_section;
        $filter .= '&filter=top25&limit=25';
    }
    if($_GET['section'] == 'top-franchises'){
        $breadcrumbs['2']['title'] = 'Top Franchises';
        // $breadcrumbs['2']['url'] = 'top-franchises';
        $filter .= '&filter=top25&limit=50'; 
        $displayFilter = false;
    }
    if($_GET['section'] == 'hot-and-trendy-franchises' || $_GET['section'] == 'hot-and-trendy'){
        $breadcrumbs['2']['title'] = 'Hot & Trendy Franchises';
        $filter .= '&filter=top25&limit=50';
    }
    if($_GET['section'] == 'new-franchises'){ 
        $breadcrumbs['2']['title'] = 'New Franchises For Sale';
        $filter .= '&filter=new&limit=20';
        $hideTitle = 'hide';
        $showMarginTop = 'mg-1';
    }
    if($_GET['section'] == 'high-investment-franchises'){
        $breadcrumbs['2']['title'] = 'Investment Level';
        $breadcrumbs['2']['url'] = $landingUrl.'investment-level';
        $breadcrumbs['3']['title'] = 'High Investment Franchises';
        $filter .= '&min=500000&max=9999999';
        if ($_GET['landing_url'] == 'find-a-franchise' && isset($_GET['invest'])) {
            $filter .= '&min=250000&max=9999999';
        }
        $meta_title_override = 'High Investment Franchises | FranchiseForSale.com';
        $description_override = 'Explore high-investment franchises on FranchiseForSale.com. These high-investment franchises also have the potential to return very positive cash flow -- search now!';
        $keywords_override = 'High Investment Franchises';
    }
    if($_GET['section'] == 'low-cost-franchises'){
        $filter .= '&min=0&max=50000';
        $breadcrumbs['2']['title'] = 'Investment Level';
        $breadcrumbs['2']['url'] = $landingUrl.'investment-level'; 
        $breadcrumbs['3']['title'] = 'Low Cost Franchises';
    }
    if($_GET['section'] == 'alphabetical-company-search'){
        $alphabet = $_GET['alphabet']; 
        $ucAlpha = ucwords($alphabet);
        $breadcrumbs['2']['title'] = 'Alphabetical Company Search';
        $breadcrumbs['2']['url'] = $landingUrl.'alphabetical-company-search';
        $breadcrumbs['3']['title'] = "Starts With \"$ucAlpha\"";
        $meta_title_override = 'Alphabetical Search - Franchises Starting With '.$ucAlpha.' | FranchiseForSale.com';
        $description_override = 'Search Franchises on FranchiseForSale.com starting with '.$ucAlpha.'. New listings posted daily!';
        if ($alphabet == 'non-alpha') { 
            $alphabet = 'misc';
            $breadcrumbs['3']['title'] = "Starts With \"Numbers Or Special Characters\"";
            $meta_title_override = 'Alphabetical Search - Franchises Starting With Numbers Or Special Characters | FranchiseForSale.com';
            $description_override = 'Search Franchises on FranchiseForSale.com starting with Numbers Or Special Characters. New listings posted daily!';
        }
        $filter .= '&letter='.$alphabet;
        
    }
    if($_GET['section'] == 'name-search') {
        $name = $_GET['name'];
        $breadcrumbs['2']['title'] = 'Alphabetical Company Search';
        $breadcrumbs['2']['url'] = $landingUrl.'alphabetical-company-search';
        // $breadcrumbs['3']['title'] = "Starts With \"$ucAlpha\"";
        $breadCrumbName = ucwords($name);
        $breadcrumbs['3']['title'] = "Has \"$breadCrumbName\" in the name";
        $filter .= '&keyword='.$name;
    }

    if($_GET['section'] == 'directory'){
        $breadcrumbs['2']['title'] = 'Franchise Directory in '.$filterstate[$homeState];
        $breadcrumbs['2']['url'] = $landingUrl.'directory';
        $filter .= '&filter=directory';
        $meta_title_override = 'Find Your Ideal Franchise - Listings Available on FranchiseForSale.com';
        $description_override = 'Uncover the perfect franchise for sale that matches your entrepreneurial spirit. Browse our full inventory of franchise listings to begin your ownership journey.';
    }
}else if($_GET['cat_name'] == 'low-cost-franchises'){
    $breadcrumbs['2']['title'] = 'Investment Level';
    $breadcrumbs['2']['url'] = $landingUrl.'investment-level'; 
    $breadcrumbs['3']['title'] = 'Low Cost Franchises';
    // $breadcrumbs['3']['url'] = 'low-cost-franchises';
    $breadcrumbs['3']['url'] = '';
    $filter .= '&min=0&max=50000';
}else if($_GET['cat_name'] == 'high-investment-franchises'){
    $breadcrumbs['2']['title'] = 'Investment Level';
    $breadcrumbs['2']['url'] = $landingUrl.'investment-level';
    $breadcrumbs['3']['title'] = 'High Investment Franchises';
    $filter .= '&min=500000&max=9999999';
}
//var_dump($_GET);die;
if(isset($_POST['investment_filter']) || isset($_GET['investment'])){
    $investment_filter = '';
    if(isset($_GET['investment'])){
        $_POST['investment_filter'] = $_GET['investment'];
    }
    if(in_array($_POST['investment_filter'],$investment_array)){
        $investment_filter = '&min=0&max='.$_POST['investment_filter'];
    }else if($_POST['investment_filter'] == 'over' || $_POST['investment_filter'] == 500001){ 
        $investment_filter = '&min=500000&max=9999999';
    }else if(isset($_GET['investment'])){
        $investment_filter = $investment_titles[$_GET['investment']]['filter'];
    }
    $filter .= $investment_filter;
    $breadcrumbs['2']['title'] = 'Investment Level'; 
    $breadcrumbs['2']['url'] = $landingUrl.'investment-level';
    $breadcrumbs['3']['title'] = ucwords($_GET['range'].' $'.number_format($_GET['investment']));
    // $breadcrumbs['3']['url'] = 'franchises-'.$_GET['range'].'-'.$_GET['investment'];
    $breadcrumbs['3']['url'] = '';
    
}
if(isset($_GET['state_name'])){
    foreach($filterstate as $key => $value){
        if($_GET['state_name']  == $value){ 
            $homeState = $key;
            $state_name = $value;
            $state_title = ucwords(str_replace("-", " ", $state_name));
            $breadcrumbs['2']['title'] = 'State';
            $breadcrumbs['2']['url'] = $landingUrl.'state';
            $breadcrumbs['3']['title'] = ucwords($value);
            // $breadcrumbs['3']['url'] = $_GET['state_name'];
            $breadcrumbs['3']['url'] = '';
        }
    }
}
$state_name = $filterstate[$homeState];
$state_title = ucwords(str_replace("-", " ", $state_name));
// if(isset($_GET['state_name'])){
//     $meta_title_override = 'Franchises for Sale in '.$state_title.$pageText.' | FranchiseForSale.com';
//     $description_override = 'Search franchises for sale in '.$state_title.'. FranchiseForSale.com provides a fantastic selection of franchises and business opportunities for sale in '.$state_title.$pageText;
//     $keywords_override = 'franchises for sale in '.$state_title.',state search,businesses for sale in '.$state_title;
// }
$home_stateurl = $state_name;
$home_statename = $state_title;
$title = $top_prefix.'Franchises Opportunities in '.ucwords(str_replace("-", " ", $filterstate[$homeState]));
if(isset($cat_id)  && is_numeric($cat_id)){
    $title = $filtercat[$cat_id].' '.$title;
}
$title .= $investment_title;
if(strlen($tyTitle)){
    $title = $tyTitle;
}

$excludelist = array();
if(strlen($requested)){
    $excludelist = explode(',',$requested);
}
$page = '';
if (isset($_GET['page'])) {
    $page = '&per_page=30&page='.$_GET['page'];
    $pageText = ' - Page '.$_GET['page'];
}
//Pulls back search results
$searchServicesObj = return_results_array($website_id,$paid,$filter);

$total_count = $searchServicesObj['pagination']['current_page']['total_count'];

// Top Franchises
if($_GET['section'] == 'top-franchises'){
    $top50Franchises = $searchServicesObj;
}

// echo '<pre>';
// print_r($searchServicesObj);die;

// Pagination
$totalPages = $searchServicesObj['data']['total'];
$nextPageUrl = $searchServicesObj['data']['next_page_url'];
$currentPage = $searchServicesObj['data']['current_page'];

$path = strtok($_SERVER["REQUEST_URI"], '?');
$hidePagination = '';
if (!empty($totalPages) && $totalPages > 30) {
    $pagination = '<li><a class="gray-border radius white-bg" href="'.$path.'">1</a></li>';
    for ($i=2; $i <= ceil($totalPages/30); $i++) { 
        if ($currentPage == $i) {
            $pagination .= '<li><a class="gray-border radius white-bg active" href="?page='.$i.'">'.$i.'</a></li>';
        }else{
            $pagination .= '<li><a class="gray-border radius white-bg" href="?page='.$i.'">'.$i.'</a></li>';
        }
    }
}else{
    $hidePagination = 'hide';
}

//Create Big List
function make_biglist(){
    if($_GET['section'] == 'big'){
        global $searchServicesObj;
        $bigList = '';
        foreach($searchServicesObj['data']['data'] as $key => $value) {
            $fran_name = cleanfix($value['name'],$starter);
            $bigList .= '<li class="col-md-4 col-sm-6 col-12"><a href="#listing_'.$value['fbo_id'].'">'.$fran_name.'</a></li>';
        }
        $temp_bigList = '<div id="toc-slider" class="closed">
                            <div class="container"> 
                                <div class="row">
                                    <div id="toc-toggle" class="col-md-12">
                                        <span>Top Businesses for Sale</span><i id="toc-search-chevron" class="fa fa-chevron-down"></i>
                                    </div>
                                </div>
                                <ol id="toc-filters" class="row">';
                                $temp_bigList .= $bigList;
                                $temp_bigList .= '      
                                </ol>
                            </div>
                        </div>';
        echo $temp_bigList;
    }
}

$hideViewAll = 'hide';

//Create listing results
function write_listings($start,$stop){
    global $searchServicesObj,$excludelist,$units,$global_rate,$get_section, $homeState,$website_id,$site_id,$spinner;
	if (is_array(@$searchServicesObj['data']['data']) && !empty($searchServicesObj['data']['data'])) {
        $max = count($searchServicesObj['data']['data']);
        $listings = '[[\'';
        $ending = '\']]';
        if($start == 0){
            $listings = '';
            $ending = ''; 
        }
        $hideViewAll = '';
        $i = 0;
        $j = 0;
        if($_GET['section'] == 'big'){
            $stop = 50;
        }
        foreach($searchServicesObj['data']['data'] as $key => $value) {
            if(($get_section == 'landing' && $value['rate'] >= $global_rate) || $get_section != 'landing'){
                $data_invest = preg_replace('/[^0-9]/','',$value['investment']);
                if(empty($value['investment'])){
                    $data_invest = 0;
                }
                $data_name = preg_replace('/[^0-9a-zA-Z ]/','',$value['name']);
                $starter = false;
                if($start == 0){
                    $starter = true;               
                }

                $tagName = '';
                if($value['new_tag'] == 1){
                    $tagName = 'new-franchises';
                }

                if($value['banner_id'] == 135){
                    $tagName = 'hot-trendy-tag';
                }
                if($value['site_id'] != $site_id && $_GET['section'] == 'directory'){
                    continue;
                }

                $fran_name = cleanfix($value['name'],$starter);
                $description = cleanfix($value['ShortDesc'],$starter,true); 
                if (in_array($value['concept_id'], $excludelist)) {
                    $moreInfo = '<div class="view_profile sent" data-fboid="'.$value['fbo_id'].'" onclick="view_profile('.$value['concept_id'].',true,&#39;&#39;);">';
                    $button = '<div class="requested">Info Request Sent</div>';
                }else{
                    $moreInfo = '<div class="view_profile" data-fboid="'.$value['fbo_id'].'" onclick="view_profile('.$value['concept_id'].',false,&#39;&#39;);">';
                    $button = '<button class="basket" onclick="checkCart('.$value['fbo_id'].',&#39;'.$data_name.'&#39;,'.$data_invest.',&#39;'.$units[$value['site_id']].'&#39;,false,'.$value['rate'].','.substr("0000{$i}", -4).');"><span>Request Free Info</span></button>';                              
                    $date_now = date("Y-m-d");
                    $start_date = date('Y-m-d',strtotime($value['start_date']));
                    if (($value['status'] == 3 && $start_date > $date_now) || !in_array($value['status'],[3,7])) {
                        $button = '<button class="noshow"></button>'; 
                    } 
                }  
                if($i > $stop){
                    break;
                }else{

                    if($i >= $start || $i == $max){

                        if (!$_GET['landing'] && $_GET['section'] == 'top-franchises' && $i > 49) {
                            $listcount = $i-49;
                        }else{
                            $listcount = $i+1;
                        }
                             
                            if($i % 6 == 0 && $i != 6 && $start != 0){
                                $listings .= '\'],[\'';		
                            }
                            $listings .= '<div class="result-item listing fadeout" id="listing_'.$value['fbo_id'].'" data-rate="'.$value['rate'].'" data-invest="'.substr("00000000{$data_invest}", -8).'" data-name="'.$data_name.'" data-id="'.$value['site_id'].'" data-fbo="'.$value['fbo_id'].'" data-order="'.substr("0000{$i}", -4).'">';
                            $listings .= '<div class="item '.$tagName.'">';
                            if($_GET['section'] == 'top-franchises'){
                                $listings .= '<a class="anchor" name="'.($i+1).'"></a>';
                                $listings .= '<span class="positionNumber text-center bold radius">'.($i+1).'</span>';
                            }
                            $noEvents = '';
                            $landingSummary = '';
                            $hideViewAll = '';
                            // if($value['site_id'] != $website_id || isset($_GET['landing']) ){
                            //     $noEvents = 'no-events';
                            // } 
                            if(isset($_GET['landing'])) {
                                $hideViewAll = 'hide';
                                $landingSummary = 'landing-summary';
                            }  

                            $brochureUrl = $value['brochure_url'];
                            $checkValue = checkIfFboIdExists($value['fbo_id'],$value['site_id'],$value['brochure_url']);
                            if ($checkValue) {
                                $brochure_url = $checkValue;
                                $brochure_url = 'href="'.$brochure_url.'"';
                            }else{
                                $brochure_url = 'data-name="'.$data_name.'"';
                                $noEvents = 'no-events';
                            }
                            $listings .= '<a class="'.$noEvents.'" '.$brochure_url.'>';
                            $listings .= '<div class="result-img"><img class="concept-logo lazyload" src="'.$spinner.'" data-src="'.safe_logos($value['image_url']).'" alt="'.$fran_name.'" title="'.$fran_name.'"></div>';
                            $listings .= '<h3 class="text-center">'.profile_name($value['name'],$starter).'</h3>';
                            $listings .= '<p class="summary '.$landingSummary.'">'.$description.'</p></a>';
                            $listings .= '<div class="text-center read-more '.$hideViewAll.'"><small><a class="'.$noEvents.'" '.$brochure_url.'><b>Read More »</b></a></small></div>';
                            $listings .= '<div class="cash-block">';
                            $listings .= '<p class="investment"><small><span>$</span></small> '.number_format($value['investment']).' Min.Cash Required</p>';
                            $listings .= '</div>';
                            $listings .= '<div class="result-checkbox">';
                            $listings .= '<input type="checkbox" class="temp-checkbox" value="'.$value['fbo_id'].'" id="checkbox_'.$value['fbo_id'].'">';
                            $listings .= $button;
                            $listings .= '</div></div></div>';      
                            
                            if (!$_GET['landing'] && $_GET['section'] == 'top-franchises' && $i == 49) {
                                $listings .= '<div class="container"><div class="columns rankDivider dusk-gray-bg xy-padded gray-border"><h3 class="white-text bold text-center no-margin-bottom">Honorable mentions - the next 25 most popular</h3></div> </div>';
                            }
                    } 
                    $i++;  
                }     
            }
        }
        $listings .= $ending;
    }else{
        if($start == 0){
            $listings = '<div class="nope"><p>At this time, there are no franchises that match your search request. Please revise your search to view other opportunities.</p></div>';             
        }else{
            $listings = '[""]';
        }
    }
    echo $listings;
}

function featuredFranchise(){
    global $excludelist,$units,$global_rate,$get_section, $homeState,$site_id,$paid,$website_id,$api_url,$filter;
    $searchServicesObj1 = return_results_array($website_id,$paid,$filter);
	if (is_array(@$searchServicesObj1['data']['data']) && !empty($searchServicesObj1['data']['data'])) {
        $listings = '';

        foreach($searchServicesObj1['data']['data'] as $key => $value) {

            $data_invest = preg_replace('/[^0-9]/','',$value['investment']);
            if(empty($value['investment'])){
                $data_invest = 0;
            }
            $data_name = preg_replace('/[^0-9a-zA-Z ]/','',$value['name']);
            $starter = false;
            $fran_name = cleanfix($value['name'],$starter);
            $description = cleanfix($value['ShortDesc'],$starter,true); 
            if (in_array($value['concept_id'], $excludelist)) {
                $moreInfo = '<div class="view_profile sent" data-fboid="'.$value['fbo_id'].'" onclick="view_profile('.$value['concept_id'].',true,&#39;&#39;);">';
                $button = '<div class="requested">Info Request Sent</div>';
            }else{
                $moreInfo = '<div class="view_profile" data-fboid="'.$value['fbo_id'].'" onclick="view_profile('.$value['concept_id'].',false,&#39;&#39;);">';
                $button = '<button class="basket" onclick="checkCart('.$value['fbo_id'].',&#39;'.$data_name.'&#39;,'.$data_invest.',&#39;'.$units[$value['site_id']].'&#39;,false,'.$value['rate'].','.substr("0000{$i}", -4).');"><span>Request Free Info</span></button>';                              
            }

            $noEvents = '';
            $checkValue = checkIfFboIdExists($value['fbo_id'],$value['site_id'],$value['brochure_url']);
            if ($checkValue) {
                $brochure_url = $checkValue;
            }else{
                $brochure_url = '';
                $noEvents = 'no-events';
            }
            
            $listings .= '<div class="featured-fran-results container">
                <div class="featured listing" id="listing_'.$value['fbo_id'].'" data-rate="'.$value['rate'].'" data-invest="'.substr("00000000{$data_invest}", -8).'" data-name="'.$data_name.'" data-id="'.$value['site_id'].'" data-fbo="'.$value['fbo_id'].'" data-order="'.substr("0000{$i}", -4).'">
                    <a class="'.$noEvents.'" href="'.$brochure_url.'">
                        <p class="medium-black-bg white-text featured-bar bold"><span class="star-icon"></span> Featured Franchise</p>
                        <div class="featured-fran-row">
                            <div class="large-4 pd-15">
                                <div class="concept-image-wrapper">
                                    <img class="lazyload" data-src="'.safe_logos($value['image_url']).'" alt="'.$fran_name.'" title="'.$fran_name.'">
                                </div>
                            </div>
                            <div class="large-8 pd-15">
                                <h3 class="text-center">'.$fran_name.'</h3>
                                <p class="summaryabbrev">'.$description.'</p>
                            </div>
                        </div>
                    </a>
                    <div class="featured-fran-row">
                        <div class="large-4">
                            <div class="read-more text-center"><small><b>Read More »</b></small></div>
                        </div>
                    </div>
                    <div class="featuredCBRow">
                        <div class="large-8">
                            <p class="investment bold text-center"><small><span>$</span> '.number_format($value['investment']).' Min.Cash Required</small></p>
                        </div>';
                        $listings .= '<div class="result-checkbox large-4">';
                        $listings .= '<input type="checkbox" class="temp-checkbox" value="'.$value['fbo_id'].'" id="checkbox_'.$value['fbo_id'].'">';
                        $listings .= $button;
                        $listings .= '</div></div>
                </div>
            </div>';
        }
    }else{
        $listings = '';             
    }
    echo $listings;
}


if(isset($_GET['city_name'])){ 
    $temp_city = str_replace("-", " ", $_GET['city_name']);
    $temp_city = ucwords($temp_city);
    $breadcrumbs['1']['title'] = 'Business For Sale | '.$temp_city.', '.$filterstate[$homeState];
    // $breadcrumbs['1']['url'] = 'city/'.strtolower($homeState).'/'.$_GET['city_name'];
    $breadcrumbs['1']['url'] = '';
}
// if(isset($_GET['investment'])){
//     $breadcrumbs['1']['title'] = $investment_titles[$_GET['investment']]['title'];
//     $breadcrumbs['1']['url'] = $_GET['investment'];
// }

    // 
    if($_GET['thankyou'] == true){
        $title = 'Thank You';
        $paragraph = 'Your';
        if(strlen($firstname)){
            $paragraph = $firstname.',';
        }
        $paragraph .= ' request has been sent to the companies that you would like to be in touch with. Development representatives will be contacting you by phone and/or email shortly.
        ';
    }

    if(isset($_GET['section'])){
        if($_GET['section'] == 'directory'){
            $title = 'Franchise Directory';
        }
        if($_GET['section'] == 'top25'){
            $title = 'Top 25 Franchises';
        }
        if($_GET['section'] == 'big'){
            $title = 'Big Brand - Businesses for Sale';
        }
        if($_GET['section'] == 'new'){
            $title = 'New Franchises For Sale';
        }
        if($_GET['section'] == 'keyword'){
            $title = 'Search Results';
        }
        if($_GET['section'] == 'low-cost-franchises'){
            $title = 'Low Cost Franchise Opportunties';
        }
        if($_GET['section'] == 'high-investment-franchises'){
            $title = 'High Investment Franchise Opportunities';
        }
    }

    if(isset($_GET['investment'])){
        $title = $investment_titles[$_GET['investment']]['title'];
        $show_more_title = $title;
    }

    if ($_GET['section'] == 'low-cost-franchises' || $_GET['cat_name'] == 'low-cost-franchises') {
        $meta_title_override = 'Low Cost Franchises | FranchiseForSale.com';
        $description_override = 'Find franchises for sale at budget-friendly prices! Search fantastic franchises and business opportunities available for sale at prices to fit every type of budget. Search now and find a low cost franchise that is perfect for you!';
    }
    
    if(isset($_GET['cat_name'])){
        if(isset($_GET['sub_cat_name'])){
            $title = $subcat_array['data']['title'];
            $show_more_title = ucwords(str_replace("-", " ", $_GET['sub_cat_name']));
        }else{
            $title = $cat_array['data']['title'];
            $show_more_title = ucwords(str_replace("-", " ", $_GET['cat_name'])); 
        }
    }
    if(isset($_GET['state_name']) && !isset($_GET['city_name'])){
        $title = 'Franchises for Sale in '.$state_title;
        $show_more_title = $title;
    }
    if(isset($_GET['city_name'])){
        $nicknames = ["alabama" => "The Yellowhammer State","alaska" => "The Last Frontier","arizona" => "The Grand Canyon State","arkansas" => "The Natural State","california" => "The Golden State","colorado" => "The Centennial State","connecticut" => "The Constitution State","delaware" => "The First State","district-of-columbia" => "The Nation's Capital","florida" => "The Sunshine State","georgia" => "The Peach State","hawaii" => "The Aloha State","idaho" => "The Gem State","illinois" => "The Prairie State","indiana" => "The Hoosier State","iowa" => "The Hawkeye State","kansas" => "The Sunflower State","kentucky" => "The Bluegrass State","louisiana" => "The Pelican State","maine" => "The Pine Tree State","maryland" => "The Old Line State","massachusetts" => "The Bay State","michigan" => "The Great Lakes State","minnesota" => "The North Star State","mississippi" => "The Magnolia State","missouri" => "The Show-Me State","montana" => "The Treasure State","nebraska" => "The Cornhusker State","nevada" => "The Silver State","new-hampshire" => "The Granite State","new-jersey" => "The Garden State","new-mexico" => "The Land of Enchantment","new-york" => "The Empire State","north-carolina" => "The Tar Heel State","north-dakota" => "The Peace Garden State","ohio" => "The Buckeye State","oklahoma" => "The Sooner State","oregon" => "The Beaver State","pennsylvania" => "The Keystone State","rhode-island" => "The Ocean State","south-carolina" => "The Palmetto State","south-dakota" => "The Mount Rushmore State","tennessee" => "The Volunteer State","texas" => "The Lone Star State","utah" => "The Beehive State","vermont" => "The Green Mountain State","virginia" => "The Old Dominion","washington" => "The Evergreen State","west-virginia" => "The Mountain State","wisconsin" => "The Badger State","wyoming" => "The Equality State"];

        /* GET CITY DATA */
        $city_name = str_replace("-", " ", $_GET['city_name']);
        $city_name = str_replace("\\'", "'", $city_name);
        $city_name = ucwords($city_name);
        $city_sdk = preg_replace("/\s+/", "%20", $city_name);
        $city_sdk = str_replace("'", "%27", $city_sdk);

        $json_string = 'https://www.franchiseportals.com/'.$site_id.'/city_sdk.php?city='.stripslashes($city_sdk).'&state_code='.$homeState.'&random='.rand(100000,999999);
        $jsondata = file_get_contents($json_string);
        $cityArray = json_decode($jsondata,true);

        $cityName = $cityArray['city']['city_name'];
        $stateName = $cityArray['city']['state_name'];
        $population = $cityArray['city']['population'];
        $stateNickname = $nicknames[$filterstate[$homeState]];

        $title = 'Business for Sale | '.ucwords(str_replace("-", " ", $_GET['city_name'])).', '.$stateName;
        $paragraph1 = 'Discover business opportunities, in or around '.$cityName.', with well-established brands, who are looking for people like you to help them grow.';
        $paragraph2 = 'As of the 2010 United States Census, '.$cityName.', located in '.$stateNickname.', had a total population of '.$population.' people.';
    }

    if($_GET['state_name'] && $_GET['cat_name'] && $_GET['investment']){
        unset($breadcrumbs);
        $breadcrumbs = array();
        $breadcrumbs['0']['title'] = 'Home';
        $breadcrumbs['0']['url'] = '/';
        $breadcrumbs['1']['title'] = 'Search Results';
    }
    
    //Trigger refresh of cart and variables
    cart_submitted();

    if($_GET['thankyou']){
        $filterCls = 'hide';
    }

    //Category Subcategories
    function subcategories($array){
            if(!empty($array)){
                global $cat_array;
                $temp_links = '';
                $return = '<section id="subcat"><div class="container"><div class="row"><div class="col-md-12">';
                $return .= '<span>Also in '.$cat_array['data']['category_name'].':</span>';
                foreach(array_slice($array,1) as $key => $value){
                    if(isset($_GET['sub_cat_name'])){
                        if($_GET['sub_cat_name'] == $key){
                            continue;
                        }
                    }
                    $temp_links .= '<a href="/'.$_GET['cat_name'].'/'.$key.'">'.ucwords(str_replace("-", " ", $key)).'</a>';
                }
                $return .= $temp_links;
                $return .= '</div></div></div></section>';
                echo $return;
            }
    }

    //Category Subcategories Description
    function subcat_description($value){
        if(!isset($_GET['sub_cat_name'])){
            if(!empty($value)){
                $return = '<section id="subcat_description"><div class="container"><div class="row"><div class="col-md-12">';
                $return .= $value;
                $return .= '</div></div></div></section>';
                echo $return;
            }
        } 
    }

    if($_GET['section'] == 'alphabetical-company-search'){

        $alphaLetters = '<div class="alphabetical-block container">
            <p class="h4 bold">Search By Letter Or Number:</p>
            <ul class="alphaList"><li><a href="/alphabetical-company-search/a">A</a></li><li><a href="/alphabetical-company-search/b">B</a></li><li><a href="/alphabetical-company-search/c">C</a></li><li><a href="/alphabetical-company-search/d">D</a></li><li><a href="/alphabetical-company-search/e">E</a></li><li><a href="/alphabetical-company-search/f">F</a></li><li><a href="/alphabetical-company-search/g">G</a></li><li><a href="/alphabetical-company-search/h">H</a></li><li><a href="/alphabetical-company-search/i">I</a></li><li><a href="/alphabetical-company-search/j">J</a></li><li><a href="/alphabetical-company-search/k">K</a></li><li><a href="/alphabetical-company-search/l">L</a></li><li><a href="/alphabetical-company-search/m">M</a></li><li><a href="/alphabetical-company-search/n">N</a></li><li><a href="/alphabetical-company-search/o">O</a></li><li><a href="/alphabetical-company-search/p">P</a></li><li><a href="/alphabetical-company-search/q">Q</a></li><li><a href="/alphabetical-company-search/r">R</a></li><li><a href="/alphabetical-company-search/s">S</a></li><li><a href="/alphabetical-company-search/t">T</a></li><li><a href="/alphabetical-company-search/u">U</a></li><li><a href="/alphabetical-company-search/v">V</a></li><li><a href="/alphabetical-company-search/w">W</a></li><li><a href="/alphabetical-company-search/x">X</a></li><li><a href="/alphabetical-company-search/y">Y</a></li><li><a href="/alphabetical-company-search/z">Z</a></li><li><a href="/alphabetical-company-search/non-alpha">#</a></li>
            </ul>
        </div>';
    }

    if($_GET['section'] == 'top-franchises'){
        $whatBestFran = '<div class="container">
            <div class="what-best-fran">
                <h2>What’s the Best Franchise for You?</h2>
                <p>It’s one thing to browse a list of top franchises, but how do you decide on the best franchise for you? Some businesses that may be suitable for other individuals might not be the best fit for you. Here are some key factors to consider as you look for the right opportunity:</p>
                <ul>
                    <li><strong>Niche.</strong> What industry is the franchise in? Is it a small niche targeting a very specific type of customer or a broader niche with a larger audience? While it’s not necessary to have an interest in the company to run a successful business, being passionate about 
                    the niche can help to improve your enjoyment as a business owner. Additionally, it can be helpful to ensure your skillset aligns with the requirements of the niche you choose. For instance, if you want to be hands-on and have a deep knowledge of automotive mechanics, a car repair franchise might be a great fit for you.</li>
                    <li><strong>Budget.</strong> When determining your budget, it’s important to consider startup costs and fees. Some of the top franchises can also be some of the most expensive. Be sure that you understand the startup and ongoing costs required from you to open and manage your franchise. The good news is that there are 
                    <a class="linked bold" href="https://www.franchiseopportunities.com/finance-center">financing options available</a> that can help you get started.</li>
                    <li><strong>Location.</strong> Does the franchise operate in your region? Do you have any control over where you can open a franchise? Are there already strong competitors in your area? These are all important considerations when looking for the best franchise opportunities for you.</li>
                    <li><strong>Support.</strong> Almost all franchisors will offer some level of support to help you start and manage your new business. But some companies offer more support than others. When comparing opportunities, be sure to ask what initial training and resources are provided as well as what types of ongoing support will be available.</li>
                    <li><strong>Time.</strong> As you compare franchises, you’ll want to consider both your day-to-day time commitment and your long-term time commitment. Will you need to be hands-on daily managing the business, or will you be able to hire staff and delegate tasks? Is there a franchise term in place that requires you to manage the business for a certain number of years? And, if you do decide you want to change directions later, how easy will it be for you to sell the franchise?</li>
                    <li><strong>Growth Opportunities.</strong> What opportunities are there to grow your business? If you choose a larger brand, there may be less room to grow—but if you choose a smaller brand, there may be many challenges to overcome as you expand. Is there a wider market that hasn’t yet been tapped into, or has the brand reached the limit of 
                    its market cap? How easy would it be for you to open a second location if things were going well with your first location? While you might not be thinking too heavily about expansion before you even get started, considering the growth potential is an important factor when deciding on the best franchises to own.</li>
                </ul>
            </div>
            <div class="what-best-fran">
                <br>
                <p id="footnote"><b>** Footnote:</b> This list was compiled based on the most requested franchises and business opportunities on Franchise Opportunities Network of websites in calendar year '.date('Y').'. The four sites include: FranchiseOpportunities.com, FranchiseForSale.com, FoodFranchise.com, and BusinessBroker.net. 
                The number of inquiries a franchise receives is influenced by their advertising budget and the number of days active on the websites. This is not an endorsement of any particular franchise or business opportunity nor is this a scientific ranking.</p>
            </div>
        </div>';
    }

    if($_GET['landing']){
        $filterCls = 'landing-res-filter';

        if (($_GET['landing_url'] == 'great-franchises-a') || ($_GET['landing_url'] == 'great-franchises-b')){
            $noBreadcrumbs = 'hide';
            $customSearch = 'custom-search';
        }
    }

    // $title = $total_count.' '.$title;

    $searchResults = true;

    if($_GET['cat_state']){
        $breadcrumbs['2']['title'] = 'Industry';
        $breadcrumbs['2']['url'] = $landingUrl.'industry';
        $breadcrumbs['3']['title'] = $flipMappedCategories[$_GET['cat_name']];
        if($_GET['cat_name'] == 'recreation-sports-franchises'){
            $breadcrumbs['3']['title'] = 'Sports & Recreation Franchises';
        }
        $clean_industry = str_replace(' Franchises','',$breadcrumbs['3']['title']);
        $breadcrumbs['3']['title'] .= ' in '.$state_title;
        // $breadcrumbs['3']['url'] = $breadcrumbs['2']['url'].'/'.$_GET['sub_cat_name'];
        $breadcrumbs['3']['url'] = '';
        $meta_title_override = $breadcrumbs['3']['title'].' | FranchiseForSale.com';
        $description_override = $clean_industry.' Franchise Opportunities on FranchiseForSale.com. Find local franchises near you in '.$state_title.' - new listings daily';
        $title = $clean_industry.' '.$title;
    }
?>
<!doctype html>
<html lang="en-US" translate="no">
<?php include_once('includes/head.php'); ?>
    <body>
        <?php include_once('includes/header.php'); ?>
        <?php echo $storage; ?>
        <main id="main" class="home-main main-results <?php echo $customSearch; ?>">

            <!-- Banner -->
            <?php 
                if($_GET['thankyou']) {
                    include_once('includes/thank-you.php');
                }else{
                    searchBanner();
                }
            ?>
            
            <!-- Filter -->
            <?php if($displayFilter){ ?>
                <div class="results-filter <?php echo $filterCls; ?>">
                    <div class="container">
                        <!-- Filter -->
                        <?php include_once('includes/filter.php'); ?>
                    </div>
                </div>
            <?php } ?>

            <!-- Alphabetical Block -->
            <?php echo $alphaLetters; ?>

            <!-- Featured Franchise -->
            <?php //(($_GET['cat_name'] == 'featured-franchises') && !$_GET['landing']) ? featuredFranchise() : ''; ?>

            <!-- Title -->
            <?php if(!$_GET['landing'] && $displayFilter){ ?>
                <div id="heading" class="container <?php echo $hideTitle; ?>">
                    <h2 class="page-title bold"><?php echo $title;?></h2>
                </div>
            <?php }elseif(!$displayFilter){ ?>
                <div class="top-25-fran"><br></div>
            <?php } ?>
            <!-- top html -->
            <!-- <div class="container custom-html pd-30">
            <?php if($show_more_title){ ?>
                <?php echo $top_html ? '<p class="show_more_link"><a href="#results">Show me the '.$show_more_title.'</a></p>'.$top_html : ''; ?>
            <?php } ?>
            </div> -->
            <?php return_results_array($website_id,$paid,$filter,true); ?>
            <!-- Results --> 
            <div id="results" class="container <?php echo $showMarginTop; ?>"><?php write_listings(0,5); ?></div>  
            <div id="refresh" class="container <?php echo $hidePagination; ?>">
                <div class="columns">
                    <span class="bold text-center" style="display:block">Pages:</span>
                    <ul class="searchPaging no-bullet text-center">
                        <?php echo $pagination; ?>
                    </ul>
                </div>
            </div>
            <!-- What best franchise -->
            <?php echo $whatBestFran; ?>
            <?php echo $backToTop; ?>
            <!-- bottom html -->
            <div class="container custom-html"><?php echo $bottom_html ? $bottom_html : ''; ?></div>
        </main>
        <div id="profile" class="modal">
            <div class="guts">
                <div class="content">
                    <div class="close_button">&times;</div>
                    <div id="profile_content"></div>
                </div>
            </div>
        </div>
        <?php include_once('includes/footer.php'); ?>
        <?php if($_GET['section'] == 'landing'){echo '<style>.truste,#footer li:not(.landing_link){display: none !important;}</style>';} ?>
        <script> 
                <?php if($_GET['section'] == 'landing'){echo 'accept_cookies();';} ?>
                var child_listings = <?php write_listings(6,999); ?>,
                    loaded = 0,
                    child_length = child_listings.length;
                submitted(<?php echo $submitted_count; ?>);
                window.addEventListener('DOMContentLoaded', () => {
                    document.getElementById("results").innerHTML += child_listings[loaded]; 
                    lazyload(); 
                    loaded++;
                });
                var isInViewport = function (elem) {
                var distance = elem.getBoundingClientRect();
                    return (
                        distance.top >= 0 &&
                        distance.left >= 0 &&
                        distance.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                        distance.right <= (window.innerWidth || document.documentElement.clientWidth)
                    );
                };

                var findMe = document.querySelector('#refresh');
                window.addEventListener('scroll', function (event) {
                    if (isInViewport(findMe) && loaded != child_length) {
                        document.getElementById("results").innerHTML += child_listings[loaded]; 
                        lazyload(); 
                        loaded++;
                    }

                    // if(loaded == child_length){
                    //     findMe.style.display = 'none';
                    // }
                }, false);
        </script>
    </body>
</html>