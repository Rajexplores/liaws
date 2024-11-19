<?php 
$affordable = true;
include_once('includes/global.php');
$top_prefix = '';
$get_section = '';
$_GET['landing_paid'] = 1;
setcookie('paid',1, time() + (1 * 24 * 60 * 60), "/", $matches[0] , $isSecure); //Expires in 1 day
//Submit Leads from Request Form
$add_page_type = 'landing';
//echo make_categories($value['category_name']);die;

$cat_id = isset($_GET['cat_name']) ? $seocat[$_GET['cat_name']] : '';

$filter = '&min=0&max=30000';
if (isset($_GET['state']) || isset($_GET['investment_filter']) || isset($_GET['cat_name'])) {
    $filter = '';
}
if(isset($_GET['cat_name'])){
    if($_GET['landing']){
        $breadcrumbs['3']['title'] = 'Search Results';
    } 
    $filter .= '&cat_id='.$cat_id; 
    // $top_prefix = ucwords(str_replace('-',' ',$_GET['cat_name'])).' ';
    $top_prefix = $filtercat[$cat_id].' ';
}

$investment_title = '';
if(isset($_POST['investment_filter'])){
    $investment_filter = '';
    if(isset($_POST['investment_filter'])){
        $investment_filter = '&min=0&max='.$_POST['investment_filter'];
    }
    if(isset($_POST['investment_filter']) && $_GET['investment_filter'] == 500001){
        $investment_filter = '&min=500000&max=9999999';
    }
    $filter .= $investment_filter;
    $breadcrumbs['2']['title'] = 'Investment Level';
    $breadcrumbs['2']['url'] = $prepend_url.'investment-level';
    $breadcrumbs['3']['title'] = ucwords($_GET['range'].' $'.number_format($_GET['investment']));
    $breadcrumbs['3']['url'] = '';
    
}
if(isset($_GET['state_name'])){
    foreach($filterstate as $key => $value){
        if($_GET['state_name'] == $value){ 
            $homeState = $key;
            $state_name = $value;
            $state_title = ucwords(str_replace("-", " ", $state_name));
            $breadcrumbs['2']['title'] = 'State';
            $breadcrumbs['2']['url'] = 'state';
            $breadcrumbs['3']['title'] = ucwords($value);
            $breadcrumbs['3']['url'] = '';
        }
    }
}
$state_name = $filterstate[$homeState];
$state_title = ucwords(str_replace("-", " ", $state_name));
$home_stateurl = $state_name;
$home_statename = $state_title;
$title = $top_prefix.'Franchises Opportunities in '.ucwords(str_replace("-", " ", $filterstate[$homeState]));
$title .= $investment_title;
if(strlen($tyTitle)){
    $title = $tyTitle;
}

$excludelist = array();
if(strlen($requested)){
    $excludelist = explode(',',$requested);
}

if(isset($_POST['category'])  && is_numeric($_POST['category'])){
    $searchCat = '&master=yes&category='.$_POST['category'];
    $title = $filtercat[$_POST['category']].' '.$title;
}

if($_GET['thankyou']){
    $breadcrumbs['2']['title'] = 'Thank You';
    // $breadcrumbs['2']['url'] = 'thankyou';
    $breadcrumbs['2']['url'] = '';
    if($_POST['request'] == 'accept'){
        $add_page_type = 'thank_you';
        $rList = '&rtype=1';
        include_once('includes/submission.php');
        include_once('includes/thankyou.php'); 
    }
 
}elseif($_GET['verify'] == true){
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
}elseif($_GET['section'] == 'landing'){
    $get_section = $_GET['section'];
    $top_prefix = 'Top ';
}else{
    // Do Nothing
}

$page = '';
if (isset($_GET['page'])) {
    $page = '&per_page=30&page='.$_GET['page'];
}
//Pulls back search results
$searchServicesObj = return_results_array($website_id,$paid,$filter);

// echo '<pre>';
// print_r($search_results);die;

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

//Create listing results
function write_listings($start,$stop){
    global $searchServicesObj,$excludelist,$units,$global_rate,$get_section, $homeState,$website_id,$spinner;
	if (is_array(@$searchServicesObj['data']['data']) && !empty($searchServicesObj['data']['data'])) {
        $max = count($searchServicesObj['data']['data']);
        $listings = '[[\'';
        $ending = '\']]';
        if($start == 0){ 
            $listings = '';
            $ending = '';
        }
        $i = 0;
        if($_GET['section'] == 'big'){
            $stop = 50;
        }

        $isProfile = true;

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
                $fran_name = cleanfix($value['name'],$starter);
                $description = cleanfix($value['ShortDesc'],$starter,true); 
                $moreInfo = '<div class="view_profile" data-fboid="'.$value['fbo_id'].'" onclick="view_profile(&#39;'.$value['brochure_url'].'&#39;,false,&#39;&#39;);">';
                $button = '<button class="basket" onclick="checkCart('.$value['fbo_id'].',&quot;'.$data_name.'&quot;,'.$data_invest.',&quot;'.$units[$value['site_id']].'&quot;,'.$isProfile.','.$value['rate'].','.substr("0000{$i}", -4).');"><span>Request FREE Info</span></button>';  
                if($i > $stop){
                    break;
                }else{

                    if($i >= $start || $i == $max){
                            
                            if($i % 6 == 0 && $i != 6 && $start != 0){
                                $listings .= '\'],[\'';		
                            }
                                         
                            $listings .= '<div class="result-item affordable listing '.$fadeOut.'" id="listing_'.$value['fbo_id'].'" data-rate="'.$value['rate'].'" data-invest="'.substr("00000000{$data_invest}", -8).'" data-name="'.$data_name.'" data-id="'.$value['site_id'].'" data-fbo="'.$value['fbo_id'].'" data-order="'.substr("0000{$i}", -4).'">';
                            $listings .= '<div class="item '.$tagName.'">';
                            $listings .= $moreInfo; 
                            $listings .= '<div class="result-img"><a class="'.$noEvents.'" href="javascript:void(0);"><img class="concept-logo lazyload" src="'.$spinner.'" data-src="'.safe_logos($value['image_url']).'" alt="'.$fran_name.'" title="'.$fran_name.'"></a></div>';
                            $listings .= '<h3 class="text-center">'.profile_name($value['name'],$starter).'</h3>';
                            $listings .= '<div class="cash-block">';
                            $listings .= '<p class="investment"><small><span>$</span></small> '.number_format($value['investment']).' Min.Cash Required</p>'; 
                            $listings .= '</div></div>';
                            $listings .= '<div class="result-checkbox">';
                            $listings .= '<input type="checkbox" class="temp-checkbox checkbox_'.$value['fbo_id'].'" value="'.$value['fbo_id'].'" id="checkbox_'.$value['fbo_id'].'">';
                            $listings .= $button;
                            $listings .= '</div></div></div>';      
                    } 
                    $i++;  
                }     
            }
        }
        $listings .= $ending;
    }else{
        if($start == 0){
            $listings = '<div class="nope"><p><strong>No results</strong> match this search criteria. Begin a new search with different filter options or browse by <a href="/franchise-categories-all">category</a>.</p></div>';             
        }else{
            $listings = '[""]';
        }
    }
    echo $listings;
}

if($_GET['thankyou'] == true){
    $title = 'Thank You';
    $paragraph = 'Your';
    if(strlen($firstname)){
        $paragraph = $firstname.',';
    }
    $paragraph .= ' request has been sent to the companies that you would like to be in touch with. Development representatives will be contacting you by phone and/or email shortly.
    ';
}

$stateURL = $baseURL.'franchises/'.$_GET['state'].'/all-categories/any-level/';
$catURL = $baseURL.'franchises/'.$_GET['state'].'/'.$_GET['category'].'/any-level/';
$investmentURL = $baseURL.'franchises/'.$_GET['state'].'/'.$_GET['category'].'/'.$_GET['investment_filter'].'/';   

//Trigger refresh of cart and variables
cart_submitted();

$noBreadcrumbs = 'hide';
$filterCls = '';
$customPage = '';
$landingDescription = 'Search for franchises, franchise information and business opportunities on FranchiseOpportunities.com. Great selection, search now!';
$landingKeywords = 'franchise for sale,franchise opportunity,business opportunity,franchises,business for sale,search,find,locate,discover';

if($_GET['thank-you']){
    $filterCls = 'hide';
}


if($_GET['landing']){
    $filterCls = 'landing-res-filter hide';
}

$customPage = 'custom-page';

$search_hide = '';
$hideTitle = 'hide';
if (isset($_GET['state']) || isset($_GET['cat_name']) || isset($_GET['investment_filter'])) {
    $affordable_search = 'affordable-search';
    $search_hide = 'hide';
    $hideTitle = '';
    $filterCls = 'landing-res-filter affordable-search';
}

?>

<!doctype html>
<html lang="en-US" translate="no">
    
    <?php include_once('includes/head.php'); ?>
    <body class="landing-body"> 
        <?php include_once('includes/header.php'); ?>
        <?php echo $storage; ?>
        <main id="main" class="home-main home-page <?php echo $customPage; ?>">  
            <?php 
                if($_GET['thankyou']) {
                    include_once('includes/thank-you.php');
                }else{ ?>
                    <div id="banner" class="affordable-banner results-banner-all <?php echo $search_hide; ?>">
                        <div class="container">
                            <div class="introBack banner-intro-bg w-100">
                                <h1>Welcome, and thanks for checking out franchise and business opportunities that you can start for $30,000 or less!</h1>
                                <p class="gold-text" onclick="toggleFilterExpand();">Expand Search <span id="filter_expand"><i class="fa fa-chevron-down" aria-hidden="true"></i></span></p>
                            </div>
                        </div>
                    </div>
            <?php }
            ?>
            <div id="dirFilter" class="results-filter <?php echo $filterCls; ?>">
                <div class="container">
                    <div id="filter"> 
                        <form id="filter_form" method="GET" class="search-form" name="search-form">
                            <div class="intro-item2-row"> 
                                <div class="form-group"> 
                                    <select name="industry" id="category" class="form-control" onchange="filter_action_affordable('<?php echo $prepend_url; ?>')">
                                        <?php create_select($filtercat,'Industry',$cat_id); ?>
                                    </select> 
                                </div> 
                                <div class="form-group"> 
                                    <select name="state" id="state" class="form-control" onchange="filter_action_affordable('<?php echo $prepend_url; ?>')"><?php create_select($filterstate,'State',$homeState);?></select> 
                                </div> 
                                <div class="form-group"> 
                                    <select name="investment" id="investment_filter" class="form-control" onchange="filter_action_affordable('<?php echo $prepend_url; ?>')"><?php investment_select($_GET['investment_filter'],$_GET['range']); ?></select>
                                </div> 
                                <div class="form-group search-button"> 
                                    <button id="search-btn" class="btn btn-lg btn-submit" onClick="filter_go(); return false;">Search in <span id="current_state"><?php echo $home_statename; ?></span></button>
                                </div> 
                            </div> 
                        </form>  
                    </div>
                </div>
            </div>
            <section id="heading" class="container <?php echo $hideTitle; ?>">
                <h2 class="page-title bold"><?php echo $title;?></h2>
            </section>
            <div id="results"  class="container"><?php write_listings(0,5); ?></div>  
            <div id="refresh" class="container <?php echo $hidePagination; ?>"></div>  
        </main>
        <div id="profile_view" class="modal">
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
                <?php 
                    function getListings(){
                        return write_listings(6,999);
                    }
                ?>

                var child_listings = <?php getListings(); ?>,
                    loaded = 0,
                    child_length = child_listings.length;
                submitted(<?php echo $submitted_count; ?>);
                window.addEventListener('DOMContentLoaded', () => {
                    // console.log('entry');
                    document.getElementById("results").innerHTML += child_listings[loaded]; 
                    lazyload(); 
                    loaded++;
                }); 

                var findMe = document.querySelector('#refresh'); 
                window.addEventListener('scroll', function (event) {
                    if (isInViewport(findMe) && loaded != child_length) {
                        document.getElementById("results").innerHTML += child_listings[loaded]; 
                        // console.log(loaded);
                        // window.history.pushState("state", "title", "?page="+loaded);
                        lazyload(); 
                        loaded++;
                    }

                    // if(loaded == child_length){
                    //     findMe.style.display = 'none';
                    // }
                }, false); 

                document.getElementById('bottom').classList.add('show');

        </script>
    </body>
</html>