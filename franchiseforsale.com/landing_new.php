<?php 
include_once('includes/global.php');
$top_prefix = '';
$get_section = '';
$_GET['landing_paid'] = 1; 
$add_page_type = 'landing';
setcookie('paid',1, time() + (1 * 24 * 60 * 60), "/", $matches[0] , $isSecure); //Expires in 1 day
$landing_page_data = get_json('/api/all_site_landingpage_settings/url/'.$_GET['url']);
$landing_page_data = json_decode($landing_page_data,true)['data'][0];
$top_prefix = '';
$get_section = '';
$clp_id = (isset($landing_page_data['id']))?$landing_page_data['id']:null;
$landing_sort = explode(',',$_GET['order']);
$landing_view_order = explode(',',$landing_page_data['details_and_order']);
$landingPageFilter = ($landing_page_data['show_filter_options']==1)?'':'hide';
$landingPageSorting = ($landing_page_data['show_sorting_options']==1)?'':'hide';
$pageUrl = $landing_page_data['url'] ? $landing_page_data['url'] : '';
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

if(isset($_GET['sub_cat_name']) || isset($_GET['cat_name'])){
    $breadcrumbs['2']['title'] = 'Industries';
    $breadcrumbs['2']['url'] = 'franchise-categories-all';
    $cat_mapping = [];
    $subcat_mapping = [];
    $site_categories_url = $api_url.'site_categories/site/'.$site_id.'/all';
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
    if(isset($_GET['cat_name'])){
        $cat_array = $cat_mapping[$_GET['cat_name']];
        $_POST['category'] = $cat_array['id'];
        $breadcrumbs['2']['title'] = 'Industry';
        $breadcrumbs['2']['url'] = $prepend_url.'industry';
        // $breadcrumbs['3']['title'] = $cat_array['data']['title'];
        $breadcrumbs['3']['title'] = $flipMappedCategories[$_GET['cat_name']];
        if($_GET['cat_name'] == 'recreation-sports-franchises'){
            $breadcrumbs['3']['title'] = 'Sports & Recreation Franchises';
        }
        $breadcrumbs['3']['url'] = '';
        $cat_id = $cat_array['id'];
        if($_GET['landing']){
            $breadcrumbs['3']['title'] = 'Search Results';
        }
    }
    if(isset($_GET['sub_cat_name'])){
        $subcat_array = $subcat_mapping[$_GET['sub_cat_name']];
        $filter .= '&subcat='.$subcat_mapping[$_GET['sub_cat_name']]['id'];
        $breadcrumbs['2']['title'] = 'Industry';
        $breadcrumbs['2']['url'] = $prepend_url.'industry';
        $breadcrumbs['3']['title'] = $subcat_array['data']['title'];
        // $breadcrumbs['3']['url'] = $breadcrumbs['2']['url'].'/'.$_GET['sub_cat_name'];
        $breadcrumbs['3']['url'] = '';
    }
}

$investment_title = '';
if(isset($_POST['investment_filter']) || isset($_GET['investment'])){
    $investment_filter = ''; 
    if(isset($_GET['investment'])){
        $_POST['investment_filter'] = $_GET['investment'];
    }
    if(in_array($_POST['investment_filter'],$investment_array) && $_POST['investment_filter'] != 500001){ 
        $investment_filter = '&min=0&max='.$_POST['investment_filter'];
    }else if($_POST['investment_filter'] == 'over' || $_POST['investment_filter'] == 500001){ 
        $investment_filter = '&min=500000&max=9999999';
    }else if(isset($_GET['investment'])){
        $investment_filter = $investment_titles[$_GET['investment']]['filter'];
    }
    $filter .= $investment_filter;
    $breadcrumbs['2']['title'] = 'Investment Level';
    $breadcrumbs['2']['url'] = $prepend_url.'investment-level';
    $breadcrumbs['3']['title'] = ucwords($_GET['range'].' $'.number_format($_GET['investment']));
    // $breadcrumbs['3']['url'] = 'franchises-'.$_GET['range'].'-'.$_GET['investment'];
    $breadcrumbs['3']['url'] = '';
    
}

if($_GET['section'] == 'high-investment-franchises'){
    $breadcrumbs['2']['title'] = 'Investment Level';
    $breadcrumbs['2']['url'] = $prepend_url.'investment-level';
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
    $breadcrumbs['2']['url'] = $prepend_url.'investment-level'; 
    $breadcrumbs['3']['title'] = 'Low Cost Franchises';
}

if (!isset($_GET['display']) && $landing_page_data) {
    $filter = '&min='.$landing_page_data['minimum_cash_required'].'&max='.$landing_page_data['maximum_cash_required'];
    $_POST['investment_filter']=$landing_page_data['maximum_cash_required'];
    $invest = $landing_page_data['maximum_cash_required'];
    $invest_filter = $landing_page_data['maximum_cash_required'];
    if(isset($landing_page_data['primary_category']) && $landing_page_data['primary_category']){
        // $_GET['cat_name'] = $_POST['industry'];
        if ($landing_page_data['site_id'] == 0) {
            $cat_id = $landing_page_data['primary_category'];
        }else{
            $cat_id = master_mapping($landing_page_data['primary_category']);
        }
        $filter.='&cat_id='.$cat_id;
    }
    if ($landing_page_data['fbo_ids']) {
        $filter .= '&filter=all&idlist='.$landing_page_data['fbo_ids'];
    }
}

if(isset($_GET['state_name'])){
    foreach($filterstate as $key => $value){
        if($_GET['state_name']  == $value){ 
            $homeState = $key;
            $state_name = $value;
            $state_title = ucwords(str_replace("-", " ", $state_name));
            $breadcrumbs['2']['title'] = 'State';
            $breadcrumbs['2']['url'] = 'state';
            $breadcrumbs['3']['title'] = ucwords($value);
            // $breadcrumbs['3']['url'] = $_GET['state_name'];
            $breadcrumbs['3']['url'] = '';
        }
    }
}
$state_name = $filterstate[$homeState];
$state_title = ucwords(str_replace("-", " ", $state_name));
$home_stateurl = $state_name;
$home_statename = $state_title;
$title = $top_prefix.'Franchises in '.ucwords(str_replace("-", " ", $filterstate[$homeState]));
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

if($_GET['thankyou'] == true){
    $breadcrumbs['2']['title'] = 'Thank You';
    // $breadcrumbs['2']['url'] = 'thankyou';
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
}else if($_GET['section'] == 'landing'){
    
    $get_section = $_GET['section'];
    $top_prefix = 'Top ';
}else if($_GET['quiz'] == true){
    $searchCat = '&isQuiz=true&master=yes&category='.$_POST['rcategory'];   
    $tyTitle = 'Quiz Results';
}else{
    // Do Nothing
}

if($_GET['section'] == 'hot-and-trendy-franchises' || $_GET['section'] == 'hot-and-trendy'){
    $breadcrumbs['2']['title'] = 'Hot & Trendy Franchises';
    $filter .= '&filter=top25&limit=50';
}

if ($_GET['quiz'] == true) {
    if (empty($searchServicesObj['results'])) {
        $search_results = $prefix.'.com/'.$site_id.'/searchservices_new.php?udid='.$udid.'&platform_type=web&webfilter=yes&name=searchresults2&long_suppress=1&statecode='.$homeState.$searchCat;
        $searchServices = file_get_contents($search_results);
        $searchServicesObj = json_decode($searchServices, true); 
        $_POST['investment_filter'] = '';
        if (empty($searchServicesObj['results'])) {
            $search_results = $prefix.'.com/'.$site_id.'/searchservices_new.php?udid='.$udid.'&platform_type=web&webfilter=yes&name=searchresults2&long_suppress=1&statecode='.$homeState;
            $searchServices = file_get_contents($search_results);
            $searchServicesObj = json_decode($searchServices, true); 
            $_POST['category'] = '';
        }
    }
}

$page = '';
if (isset($_GET['page'])) {
    $page = '&per_page=30&page='.$_GET['page'];
}
if($_GET['thankyou']){
    //"D" Version
    if(in_array($uri_parts[1],['great-franchises-d'])){
        $filter .= '&filter=search_rank_cap';
    }

    //"R" Version
    if(in_array($uri_parts[1],['great-franchises-r','great-franchises-demo'])){
        $filter .= '&filter=search_rank_revenue';
    }
}

//Pulls back search results
$searchServicesObj = return_results_array($website_id,$paid,$filter);
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
$see_more=false;

//Create listing results
function write_listings($start,$stop,$json=false,$listings_view_order=[1,2,3,4,5,6]){
    global $searchServicesObj,$excludelist,$units,$global_rate,$get_section, $homeState,$website_id,$spinner,$see_more,$landing_page_data;
	if (is_array(@$searchServicesObj['data']['data']) && !empty($searchServicesObj['data']['data'])) {
        $max = count($searchServicesObj['data']['data']);
        $warning_message = null;
        $listings = '[[\'';
        $ending = '\']]';
        if($json == false){
            $listings = '';
            $ending = '';
        }
        $i = 0;
        if($_GET['section'] == 'big'){
            $stop = 50;
        }
        if($searchServicesObj['warning_message'] && $start == 0 && $json == false){
            $warning_message = '<script>var div = document.getElementById("backup-results");div.innerHTML += "<div><p>'.$searchServicesObj['warning_message'].'</p></div>";</script>';        
        }
        if($data['data']['quiz_count'] > 0){$see_more = true;}
        foreach($searchServicesObj['data']['data'] as $key => $value) {
            if(in_array($_GET['landing_url'],['great-franchises-d']) && $value['not_on_paid'] == 1){
                continue;
            } 
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
                $fran_name = cleanfix($value['name'],$starter);
                $description = cleanfix($value['ShortDesc'],$starter,true); 
                if (in_array($value['concept_id'], $excludelist)) {
                    $moreInfo = '<div class="view_profile sent" data-fboid="'.$value['fbo_id'].'" onclick="view_profile('.$value['concept_id'].',true,&#39;&#39;);">';
                    $button = '<div class="requested">Info Request Sent</div>';
                }else{
                    $moreInfo = '<div class="view_profile" data-fboid="'.$value['fbo_id'].'" onclick="view_profile('.$value['concept_id'].',false,&#39;&#39;);">';
                    $button = '<button class="basket" onclick="checkCart('.$value['fbo_id'].',&#39;'.$data_name.'&#39;,'.$data_invest.',&#39;'.$units[$value['site_id']].'&#39;,false,'.$value['rate'].','.substr("0000{$i}", -4).');"><span>Request Free Info</span></button>';                              
                }  
                if($i > $stop){
                    break;
                }else{

                    if($i >= $start || $i == $max){
                            
                            if($i % 8 == 0 && $i != 8 && $json == true){
                                // $listings .= '\'],[\'';		
                            }
                            
                            $noEvents = ''; 
                            $landingSummary = '';
                            $hideViewAll = '';
                            $fadeOut = '';
                            $description_truncated = $description;

                            if($value['site_id'] != $website_id){
                                $hideViewAll = 'lower-opacity';
                                $noEvents = 'no-events';
                            }
                                
                            $noEvents = 'no-events';
                            $hideViewAll = 'hide';
                            $landingSummary = 'landing-summary';
                            $description_truncated = truncate_landing($description,200,$key); 
                            $listings .= '<div class="result-item listing '.$fadeOut.'" id="listing_'.$value['fbo_id'].'" data-rate="'.$value['rate'].'" data-invest="'.substr("00000000{$data_invest}", -8).'" data-name="'.$data_name.'" data-id="'.$value['site_id'].'" data-fbo="'.$value['fbo_id'].'" data-order="'.substr("0000{$i}", -4).'">';
                            if ($listcount) {
                                $listings .= '<a class="anchor" name="'.$listcount.'"></a>';
                            }
                            $listings .= '<div class="flexxed-item item '.$tagName.'"><div class="flexxed-top">';
                            $counter=1;
                            foreach($listings_view_order as $view_order){
                                if($view_order==1 && $landing_page_data['logo']==1){
                                    $listings .= '<div class="result-img"><img class="concept-logo lazyload" src="'.$spinner.'" data-src="'.safe_logos($value['image_url']).'" alt="'.$fran_name.'" title="'.$fran_name.'"></div>';
                                }
                                if($view_order==2 && $landing_page_data['name']==1){
                                    $listings .= '<h3 class="text-center">'.profile_name($value['name'],$starter).'</h3>';
                                }
                                if($view_order==3 && $landing_page_data['description']==1){
                                        // $listings .= '<div class="description '.$moreDetails.'">';
                                        $listings .= '<p class="summary '.$landingSummary.'">'.$description_truncated.'</p>';
                                        // if($landing_page_data['show_details_modal']==1){
                                        //     $listings .= '<span class="more_info" onclick="view_profile('.$value['site_id'].','.$value['concept_id'].',false,&apos;Get Free Info&apos;);"></span>';
                                        // }
                                        // $listings .= '</div>';
                                }
                                if($view_order==5 && $landing_page_data['min_cash_required']==1){
                                    $listings .= '<div class="cash-block">';
                                    $listings .= '<p class="investment"><small><span>$</span></small> '.number_format($value['investment']).' Min.Cash Required</p>'; 
                                    $listings .= '</div>';
                                }
                                if($view_order==6){
                                    $listings .= '<div class="result-checkbox">';
                                    $listings .= '<input type="checkbox" class="temp-checkbox" value="'.$value['fbo_id'].'" id="checkbox_'.$value['fbo_id'].'">';
                                    $listings .= $button;
                                    $listings .= '</div>';  
                                }
                                if($counter==3){
                                    $listings .= '</div><div class="flexxed-bottom">';
                                }
                                $counter++;
                            }
                            $listings .= '</div></div></div>';   
                            
                            // $listings .= '<div class="result-item listing '.$fadeOut.'" id="listing_'.$value['fbo_id'].'" data-rate="'.$value['rate'].'" data-invest="'.substr("00000000{$data_invest}", -8).'" data-name="'.$data_name.'" data-id="'.$value['site_id'].'" data-fbo="'.$value['fbo_id'].'" data-order="'.substr("0000{$i}", -4).'">';
                            // if ($listcount) {
                            //     $listings .= '<a class="anchor" name="'.$listcount.'"></a>';
                            // }
                            // $listings .= '<div class="flexxed-item item '.$tagName.'"><div class="flexxed-top">';
                            // $listings .= '<div class="result-img"><img class="concept-logo lazyload" src="'.$spinner.'" data-src="'.safe_logos($value['image_url']).'" alt="'.$fran_name.'" title="'.$fran_name.'"></div>';
                            // $listings .= '<h3 class="text-center">'.profile_name($value['name'],$starter).'</h3>';
                            // $listings .= '<p class="summary '.$landingSummary.'">'.$description_truncated.'</p>';
                            // $listings .= '</div><div class="flexxed-bottom">';
                            // $listings .= '<div class="cash-block">';
                            // $listings .= '<p class="investment"><small><span>$</span></small> '.number_format($value['investment']).' Min.Cash Required</p>'; 
                            // $listings .= '</div>';
                            // $listings .= '<div class="result-checkbox">';
                            // $listings .= '<input type="checkbox" class="temp-checkbox" value="'.$value['fbo_id'].'" id="checkbox_'.$value['fbo_id'].'">';
                            // $listings .= $button;
                            // $listings .= '</div></div></div></div>';   
                    } 
                    $i++;  
                }     
            }
        }
        $listings .= $ending;
    }else{
        if($start == 0){
            $listings = '<div class="nope"><p><strong>No results</strong> match this search criteria. Begin a new search with different filter options or browse by <a href="/'.$_GET['landing_url'].'/industry">Industry</a>.</p></div>';
        }else{
            $listings = '[""]';
        }
    }
    echo $warning_message.$listings;
}

$stateURL = $baseURL.'franchises/'.$_GET['state'].'/all-categories/any-level/';
$catURL = $baseURL.'franchises/'.$_GET['state'].'/'.$_GET['category'].'/any-level/';
$investmentURL = $baseURL.'franchises/'.$_GET['state'].'/'.$_GET['category'].'/'.$_GET['investment_filter'].'/';   
    
//Trigger refresh of cart and variables
cart_submitted();

$noBreadcrumbs = 'hide';
$landingDescription = 'Search for franchises, franchise information and business opportunities on Franchiseforsale.com. Great selection, search now!';
$landingKeywords = 'franchise for sale,franchise opportunity,business opportunity,franchises,business for sale,search,find,locate,discover';
$noFilter = '';
$customPage = '';
if(is_array($_GET['landing_url'], ['great-franchises-a','great-franchises-b','great-franchises-c','great-franchises-d','great-franchises-e','great-franchises-r','great-franchises-demo'])){ 
    $title = 'Franchises and Businesses for Sale | Franchise Opportunities';
    $customPage = 'custom-page';
    $noBreadcrumbs = 'hide';
    // $noFilter = 'hide-for-small';
    if ($_GET['section'] == 'industry' || $_GET['section'] == 'investment-level' || $_GET['section'] == 'state' || $_GET['section'] == 'thank-you') {
        $noBreadcrumbs = '';
        $customPage = 'having-bread-crumbs';
    }
    if($_GET['section'] == 'thank-you'){
        $noFilter = 'hide';
        $noBreadcrumbs = '';
        unset($breadcrumbs);
        $breadcrumbs = array();
        $breadcrumbs['0']['title'] = 'Home';
        $breadcrumbs['0']['url'] = $_GET['landing_url'];
        $breadcrumbs['1']['title'] = 'Thank You';
    }
} 

if($_GET['state_name'] && $_GET['cat_name'] && $_GET['investment']){
    unset($breadcrumbs);
    $breadcrumbs = array();
    $breadcrumbs['0']['title'] = 'Home';
    $breadcrumbs['0']['url'] = '/';
    $breadcrumbs['1']['title'] = 'Search Results';
}

if($_GET['thankyou']){
    $filterCls = 'hide';
}

if (!isset($_GET['home']) && $_GET['landing_url'] == 'find-a-franchise') {
    // if ($_GET['section'] == 'industry' || $_GET['section'] == 'investment-level' || $_GET['section'] == 'state' || $_GET['section'] == 'thank-you') {
        $noBreadcrumbs = '';
        $customPage = 'having-bread-crumbs';
    // }
}

if($_GET['landing']){
    $filterCls = 'landing-res-filter';

    if (($_GET['landing_url'] == 'great-franchises-a') || ($_GET['landing_url'] == 'great-franchises-b')){
        $noBreadcrumbs = 'hide';
        $customSearch = 'custom-search';
    }
}

//Truncated landing short description
function truncate_landing($string,$length=500,$target) {
    $string = trim($string);
    $string = str_replace(array("\r", "\n"), '', $string);
    $return = $string;
    if(strlen($string) > $length) {
        $array = explode( "\n", wordwrap( $string, $length));
        $return =  $array[0].' <span id="read_more'.$target.'" class="read_more" onclick="toggleMore('.$target.');">show more...</span><span id="more_text'.$target.'" class="more_text"> '.$array[1].'</span>';
    }
    return $return;
}

?>

<!doctype html>
<html lang="en-US" translate="no">
    <?php include_once('includes/head.php'); ?>
    <body class="landing-body">
        <?php include_once('includes/header.php'); ?>
        <?php echo $storage; ?>
        <style type="text/css">
        .listing input:checked + button span::after{
            font-size: 0.9rem;
            content: '<?php echo $landing_page_data['remove_concept_text'];?>';
        }
        .listing button {
            background-color: <?php echo $landing_page_data['add_concept_color'];?> !important;
        }
        .listing input:checked + button{
            background-color: <?php echo $landing_page_data['remove_concept_color'];?> !important; 
            cursor: pointer;
        }
        .modal button{
            background-color: <?php echo $landing_page_data['add_concept_color'];?> !important;
        }
        .modal .content{
            padding:1rem;
            text-align:center;
        }
        .modal .content .close_button{
            padding:1rem;
            text-align:left;
        }
        </style>
        <main id="main" class="home-main home-page <?php echo $customPage; ?>">
            
                <!-- Banner -->
                <?php 
                    if($_GET['thankyou']) {
                        include_once('includes/thank-you.php');
                    }
                    $toggle_filter = $toggle_filter_form = null;
                    if($landing_interiors){
                        $toggle_filter = 'open';
                        $toggle_filter_form = 'mobile_close';
                    }
                ?>
                
                <!-- Filter -->
                <div id="dirFilter" class="results-filter hide">
                    <div id="toggle_filter" class="<?php echo landing_interiors; ?>" onClick="toggle_filter();">
                        <span>Filter Results</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 7.33l2.829-2.83 9.175 9.339 9.167-9.339 2.829 2.83-11.996 12.17z"/></svg>
                    </div>
                    <div id="toggle_target" class="container <?php echo $toggle_filter_form; ?>">
                    <div id="filter"> 
    <form id="filter_form" method="GET" class="search-form" name="search-form">
        <div class="intro-item2-row"> 
            <div class="form-group"> 
                <select name="industry" id="category" class="form-control" onchange="landing_filter_action('category','<?php echo $prepend_url; ?>')">
                <?php create_select($filtercat,'Industry',$cat_id,$skippedCategories); ?>
                </select> 
            </div> 
            <div class="form-group"> 
                <select name="state" id="state" class="form-control" onchange="landing_filter_action('state','<?php echo $prepend_url; ?>')">
                    <optgroup label="Global">
                        <option value="CAN" <?php echo $homeState == 'CAN' ? 'selected' : '' ?>>Canada</option>
                        <!-- <option value="INT" <?php //echo $homeState == 'INT' ? 'selected' : '' ?>>International</option> -->
                    </optgroup> 
                    <optgroup label="USA">
                        <?php create_select($filterstate,'State',$homeState); ?>
                    </optgroup>
                    
                </select> 
            </div> 
            <div class="form-group"> 
                <select name="investment" id="investment_filter" class="form-control" onchange="landing_filter_action('investment','<?php echo $prepend_url; ?>')"><?php investment_select($_GET['investment']); ?></select>
            </div> 
            <div class="form-group search-button"> 
                <button id="search-btn" class="btn btn-lg btn-submit" onClick="filter_go(); return false;">Search in <span id="current_state"><?php echo $home_statename; ?></span></button>
            </div> 
        </div> 
    </form>  
</div>
                    </div>
                </div>
                
                <?php return_results_array($website_id,$paid,$filter,true); ?>
                <div id="backup-results" class="container"></div>
                <div class="container"><?php echo quiz_message(); ?></div>
                <div id="listings_above_content" class="container text-center">
                        <div class="wysiwyg_content <?php echo (strlen($landing_page_data['above_listings_content'])>0?'':'hide'); ?>">
                            <?php echo $landing_page_data['above_listings_content']?>
                        </div>
                </div>
                <div id="sorting-results" class="container <?php echo $landingPageSorting; ?>">
                <div id="sort-results" class="clearfix">
                    <span id="sort_order" class="dir-tab checked" onclick="sortTab('order');">Recommended</span>
                    <span id="sort_name" class="dir-tab" onclick="sortTab('name');">A-Z<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 0l-8 10h16l-8-10zm3.839 16l-3.839 4.798-3.839-4.798h7.678zm4.161-2h-16l8 10 8-10z"/></svg></span>
                    <span id="sort_invest" class="dir-tab" onclick="sortTab('invest');">$ Low to High<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 0l-8 10h16l-8-10zm3.839 16l-3.839 4.798-3.839-4.798h7.678zm4.161-2h-16l8 10 8-10z"/></svg></span>
                </div>
            </div>
                <div id="results"  class="container"><?php write_listings(0,5,false,$landing_view_order); ?></div> 
                <?php if($landing_page_data && $landing_page_data['fbo_ids']){ ?>  
                    <div class="container text-center"><a href="/landing/<?php echo $pageUrl ?>?display=all" style="font-size:1rem;">See More Opportunities</a></div>
                <?php } ?>
                <?php if($see_more){?><div id="see-more-btn"><a class="btn" href="great-franchises-r">See more matches</a></div><?php } ?> 
                <div id="refresh" class="container <?php echo $hidePagination; ?>">
                    <div class="columns">
                        <span class="bold text-center" style="display:block">Pages:</span>
                        <ul class="searchPaging no-bullet text-center">
                            <?php echo $pagination; ?>
                        </ul>
                    </div>
                </div>  
                <div id="listings_below_content" class="container text-center">
                        <div class="wysiwyg_content <?php echo (strlen($landing_page_data['below_listings_content'])>0?'':'hide'); ?>">
                            <?php echo $landing_page_data['below_listings_content']?>
                        </div>
                    </div>
                <a id="backToTop" href="javascript:void(0);" onclick="topFunction();" class="back-to-top dark-gray-border white-bg" style="display: inline;">TOP ▲</a>
            
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
        <?php if($_GET['section'] == 'landing'){echo '<script>accept_cookies();</script>';} ?>
        <?php if($_GET['landing'] && ($_GET['landing_url'] == 'find-a-franchise') && $_GET['home']){
            // Do Nothing
        }else{ ?>
            <script>
                var child_listings = <?php write_listings(6,999,true,$landing_view_order); ?>,
                    loaded = 0,
                    child_length = child_listings.length;
                submitted(<?php echo $submitted_count; ?>);
                if (document.getElementById('results')){
                    window.addEventListener('DOMContentLoaded', () => {
                        document.getElementById("results").innerHTML += child_listings[loaded]; 
                        lazyload(); 
                        loaded++;
                    }); 

                    var findMe = document.querySelector('#refresh'); 
                    window.addEventListener('scroll', function (event) {
                        if (isInViewport(findMe) && loaded != child_length) {
                            document.getElementById("results").innerHTML += child_listings[loaded]; 
                            lazyload(); 
                            loaded++;
                        }
                    }, false); 
                }
            </script>
        <?php } ?>
        <script>
function landing_filter_action(event = null,prepend_url=''){
    if(event != null){
        ga4_datalayer('fv_'+event+'_select');
    }
    if(prepend_url != ''){
        if(!prepend_url.startsWith('/')){
            prepend_url = '/'+prepend_url;
        }
        if(prepend_url.endsWith('/')){
            prepend_url = prepend_url.slice(0, -1);
        }
    }
    if (document.getElementById("filter")) {
        var subdirectory = location.pathname.split('/'),
            state_id = document.getElementById('state'),
            //Change from category to category and added .value BB 11/17/2022
            category = document.getElementById('category').value,
            current_state = document.getElementById('current_state'),
            investment_id = document.getElementById('investment_filter'),
            state = state_id.options[state_id.selectedIndex].text.replace(/\s+/g, '-').toLowerCase(),
            // category = category_id.options[category_id.selectedIndex].text.replace(/[&\',']/g, '').replace(/\s+/g, '-').toLowerCase(),
            investment = 'all-investment-amounts',
            path = '?';
            if (document.getElementById('investment_filter')){
                investment = investment_id.value;
            }
            //Commented out to fix issue BB 11/17/2022
            console.log(category);
            // if(category == 'green-franchises'){
            //     category = 'green-eco-friendly-franchises';
            // }else if(category == 'education-franchises'){
            //     category = 'education-training-franchises';
            // }else if(category == 'sports-recreation-franchises'){
            //     category = 'recreation-sports-franchises';
            // }else if(category == 'manufacturing-franchises'){
            //     category = 'manufacturing';
            // }else if(category == 'All Industries'){
            //     category = 'all-industries';
            // }
        current_state.innerHTML = state_id.options[state_id.selectedIndex].text;
        if(category != 'all-industries' || category != '----------------'){
            path += '&cat_name='+category;
        }
        if(state_id!='' && state_id!=null){
            path += '&state='+state_id.value;
        } 
        if(investment != 'all-investment-amounts'){
            path += '&investment='+investment;
        }
        path = path.replace("?&", "?");
        document.getElementById('filter_form').action = path;
    }
}

function sortTab(data_type){
    var temp_listings = <?php write_listings(0,7,true,$landing_view_order); ?>;
    var sort_listings = temp_listings[0]+child_listings[0];
    findMe.style.display = 'none';
    loaded = '';
    child_length = '';
    document.getElementById("results").innerHTML = sort_listings;
    sortTabActive(data_type);
    refresh_handler();
    }
                    //Resort Sales Index
                    function sortTabActive(data_type){
    var btnContainer = document.getElementById("sort-results");
    var btns = btnContainer.getElementsByClassName("dir-tab");
    var container = document.getElementById("results");
    var classname = document.getElementsByClassName('result-item');
    var sortHigh = document.getElementById("sort_"+data_type).classList.contains('high');
    var preCheck = false;
    var divs = [];
    if (document.getElementById("sort_"+data_type).classList.contains('checked')) {
        preCheck = true;
    }else{
        for (var i = 0; i < btns.length; i++) {
            btns[i].classList.remove("checked");
        }
        document.getElementById("sort_"+data_type).classList.add("checked");
    }

    for (var i = 0; i < classname.length; ++i) {
        divs.push(classname[i]);
    }

    if(data_type != 'order' && (sortHigh && !preCheck || !sortHigh && preCheck)){
        if(data_type == 'name'){
            document.getElementById("sort_"+data_type).innerHTML = 'Z-A';
        }
        if(data_type == 'invest'){
            document.getElementById("sort_"+data_type).innerHTML = '$ High to Low';
        }
        divs.sort(function(a, b) {
            return b.getAttribute("data-"+data_type).localeCompare(a.getAttribute("data-"+data_type));
        });
        // divs.sort().reverse();
    }else{
        if(data_type == 'name'){
            document.getElementById("sort_"+data_type).innerHTML = 'A-Z';
        }
        if(data_type == 'invest'){
            document.getElementById("sort_"+data_type).innerHTML = '$ Low to High';
        }
        divs.sort(function(a, b) {
            return a.getAttribute("data-"+data_type).localeCompare(b.getAttribute("data-"+data_type));
        });
    }
    if(data_type != 'order' && (!sortHigh && preCheck)){
        document.getElementById("sort_"+data_type).classList.add("high");
    }else if(data_type != 'order' && (sortHigh && preCheck)){
        document.getElementById("sort_"+data_type).classList.remove("high");
    }else{
        //DO NOTHING
    }
    
    var br = '';

    divs.forEach(function(el) {
        container.appendChild(el);
    });
  }
  refresh_handler = function(e) {
    var elements = document.querySelectorAll("*[data-src]");
    for (var i = 0; i < elements.length; i++) {
        var boundingClientRect = elements[i].getBoundingClientRect();
        if (elements[i].hasAttribute("data-src") && boundingClientRect.top <= window.innerHeight) {
            elements[i].setAttribute("src", elements[i].getAttribute("data-src"));
            elements[i].removeAttribute("data-src");
        }
    }
};
        </script>
    </body>
</html>