<?php 
$callFboChecker = true;
include_once('includes/global.php');
$is_pending = '?pending=no';
if(isset($_GET['concept_id'])){
    $is_pending = null;
}
$temp_checker = get_json('/api/profiles_mapping/'.$site_id.$is_pending);
$checker = json_decode($temp_checker,true);
// debug($checker);die;
$temp_name = '/'.$_GET['concept_type'].'/'.strtolower($_GET['name']);
$datapoints = [];
$media = [];
$carousel = '';
$discount = ''; 
$add_page_type = 'profile';
$financial = '';
$options = '';
$details = '';  
$availableStates = '';
$awards = '';
$pressrelease = '';
$conceptType = '';
$optionsLogo = '';

function franchiseDetails($franName, $franVal, $uText = false){
    $whatFran = $uText ? '<small><a class="underline text-info">What does '.$franName.' mean?</a></small>' : '';
    return '<tr>
        <th class="text-green" nowrap="">'.$franName.':</th>
        <td class="border-top-0">'.$franVal.'<br>'.$whatFran.'</td>
    </tr>';
}

if (array_key_exists($temp_name, $checker['data']) || $_GET['concept_id']) {
    $concept_id = $checker['data'][$temp_name];
    if(isset($_GET['concept_id'])){
        $concept_id = $_GET['concept_id'];
    }
    /* new profile logic */
    if($_GET['website_id']){
        $website_id = $_GET['website_id'];
        $site_id = $website_id;
    }
    $temp_profile = get_json('/api/get_profile/'.$site_id.'/'.$concept_id);
    $temp_profile = json_decode($temp_profile,true);
    $profile = $temp_profile['data']['0'];
    // echo '<pre>';
    // print_r($profile);
    // die;
    $brochure = $profile['brochure']['0'];
    if(is_null($temp_profile['data']) || strtolower($brochure['url']) != $temp_name && $_GET['custom_brochure'] != 'yes'){//custom_brochure is new profile logic
        http_response_code(404);
        $_GET['section'] = '404';
        include('page.php'); // provide your own HTML for the error page
        die();
    }

    // $brochureMeta = $profile['brochure_meta'][0];

    if($profile['concept_status'] == 0){
        header('Location : /');
        die();
    }
    if(in_array($_GET['landing_url'],['great-franchises-c','great-franchises-d','great-franchises-e','great-franchises-q','great-franchises-r'])){ 
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

    $breadcrumbs['2']['title'] = 'Industry';
    $breadcrumbs['2']['url'] = $landingUrl.'industry';

    $breadcrumbs['3']['title'] = $profile['category_name'];
    $breadcrumbs['3']['url'] = $landingUrl.'industry/'.$mapped_categories[$profile['category_name']];

    $breadcrumbs['4']['title'] = $profile['name'];
    $breadcrumbs['4']['url'] = '';
    
    foreach($profile['datapoints'] as $key => $value){
        $datapoints[$value['field_name']] = $value['value'];
    }

    // debug($brochure);
    // debug($profile);
    // debug($datapoints);
    // die;
    if($website_id==4){
        if($datapoints['min_net_worth']>0){
            $datapoints['DisplayNetWorthRequired']=1;
            $datapoints['NetWorthRequired']=$datapoints['min_net_worth'];
        }
        if($datapoints['fg_total_capital_min']>0){
            $datapoints['DisplayTotalInvestment']=1;
            $datapoints['TotalInvestmentMin']=$datapoints['fg_total_capital_min'];
            $datapoints['TotalInvestmentMax']=$datapoints['fg_total_capital_max'];
        }
        if($datapoints['fg_franchise_fee']>0){
            $datapoints['DisplayFranchiseFee']=1;
            $datapoints['FranchiseFee']=$datapoints['fg_franchise_fee'];
        }
        if($datapoints['fg_total_units']>0){
            $datapoints['DisplayExistingUnits']=1;
            $datapoints['ExistingUnits']=$datapoints['fg_total_units'];
        }
        if($datapoints['franchising_since']>0){
            $datapoints['DisplayFranchisingSince']=1;
            $datapoints['FranchisingSince']=$datapoints['franchising_since'];
        }
    }
    if($website_id==6){
        if($datapoints['franchising_since']>0){
            $datapoints['DisplayFranchisingSince']=1;
            $datapoints['FranchisingSince']=$datapoints['franchising_since'];
        }
        if($datapoints['total_invest']!=''){
            $datapoints['DisplayTotalInvestment']=1;
            $total_invest=$datapoints['total_invest'];
            $temp_liq_capital=explode('-',$total_invest);
            $temp_liq_capital[0] = str_replace('$','',$temp_liq_capital[0]); 
            $temp_liq_capital[0] = str_replace(',','',$temp_liq_capital[0]); 
            $temp_liq_capital[0] = trim($temp_liq_capital[0]); 
            $datapoints['TotalInvestmentMin']=$temp_liq_capital[0];
            $temp_liq_capital[1] = str_replace('$','',$temp_liq_capital[1]); 
            $temp_liq_capital[1] = str_replace(',','',$temp_liq_capital[1]); 
            $temp_liq_capital[1] = trim($temp_liq_capital[1]); 
            $datapoints['TotalInvestmentMax']=$temp_liq_capital[1];
        }
    }

    // Image Carousel
    foreach($brochure['images'] as $key => $value){
        // New Profile Logic: Removed Below code
        // if($site_id != $value['display_site_id']){ 
        //     continue;
        // }
        if($value['type'] == 'carousel'){
            $carouselMedia[$value['type']] = $value['image_url'];
            $media[$value['type']][$key] = $value;
        }else{
            $media[$value['type']] = $value;
        }
    }
    $media['carousel'] = array_values(array_filter($media['carousel']));
    
    foreach($media['carousel'] as $key => $value) {
        $carousel .= '<li class="splide__slide"><img src="'.$value['image_url'].'"></li>';
    }

    // Description
    // $conceptName = $profile['name'].' - '.$profile['category_name'];
    if (!in_array($profile['type'],['license','other','consultant_advertiser'])) {
        $conceptName = $profile['name'].' - '.ucwords($profile['type']);
    }else{
        $conceptName = $profile['name'];
    }
    $opt_name = 'Franchise';
    if($datapoints['BusinessTypeName'] == 'Business Opportunity'){
        $conceptName = $profile['name'].' - '.$datapoints['BusinessTypeName'];
        $opt_name = 'Business';
    }
    if($datapoints['BusinessTypeName'] == 'License'){
        $opt_name = 'License';
    }
    $shortDescription = $brochure['short_description']; 
    $longDescription = htmlspecialchars_decode($brochure['long_description']);

    foreach ($brochure['images'] as $key => $value) {
        if($value['type'] == 'logo' && $value['sort_order'] == 2){
            $logo = $value['image_url'];
            break;
        }
        if($logo=='' && $value['type'] == 'logo'){
            $logo = $value['image_url'];
        }
    }

    // $militaryPromotion = explode(',', $brochure['military_veteran_details']);
    // $militaryVeteranPromotion = $militaryPromotion[0];
    $militaryVeteranPromotion = html_entity_decode($brochure['military_veteran_details']);

    /* Available States */
    foreach ($profile['locations'][0]['states'] as $key => $value) {
        if($filterstate[$value]){
            $availableStates .= '<span class="available-states-span">'.$value.'</span>';
        }
    }

    $testimonial = '';
    if(count($profile['testimonials']) > 0){
        foreach ($profile['testimonials'] as $key => $value) {
            $testimonial .= '<blockquote>'.$value['testimony'].'<cite>'.$value['first_name'].' '.$value['last_name'].'</cite></blockquote>';
        }
    }

    $videos = '';
    $video_json = '';
    if(count($brochure['videos']) > 0){
        $video_index = 0;
        foreach ($brochure['videos'] as $key => $value) {
            if (@getimagesize($value['image_url'])) {
                $vImg = $value['image_url'];
                $vUrl = $value['video_url'];
                $vAlt = $value['alt_tag'];
                $vDesc = $value['title_text'];
                $vUploadDate = date('c', strtotime($value['uploaded_date']));
                if (@$profile['category_name'] && $profile['category_name']) {
                    $descName = preg_replace('/[^0-9a-zA-Z ]/','',$profile['name']).' in '.preg_replace('/[^0-9a-zA-Z ]/','',$profile['category_name']);
                }else{
                    $descName = preg_replace('/[^0-9a-zA-Z ]/','',$profile['name']);
                }

                $defaultDesc = $shortDescription ? $shortDescription : 'Watch and see what '.$descName.' has to offer. Discover what makes them unique. Visit FranchiseForSale.com for more info.';
                $video_desc = $value['title_text'] ? $value['title_text'] : $defaultDesc.' Video '.$video_index;
                $videos .= '<div class="profile-video-item">
                <div class="item">
                    <a href="javascript:void(0);" onclick="setProfileVideo(\''.$vUrl.'\',\''.$vAlt.'\');">
                        <div class="row thumb">
                            <span></span>
                            <img class="changeVideo" src="'.$vImg.'" alt="">
                            <p class="text-center bold">'.$video_desc.'</p>
                        </div>
                    </a>
                </div>
                </div>';

                if ($value['uploaded_date']) {
                    $iso8601_date = date("Y-m-d\TH:i:s\Z",strtotime($value['uploaded_date']));
                }else{
                    $dateTime = new DateTime();
                    $iso8601_date = $dateTime->format('Y-m-d\TH:i:s\Z');
                }

                $video_json .= '<script type="application/ld+json">
                {
                    "@context": "http://schema.org",
                    "@type": "VideoObject",
                    "name": "'.preg_replace('/[^0-9a-zA-Z ]/','',$profile['name']).' '.ucwords($profile['type']).' Video '.$video_index.'",
                    "description": "'.preg_replace('/[^0-9a-zA-Z. ]/','',$video_desc).'",
                    "thumbnailUrl": [
                        "'.$value['image_url'].'"
                    ],
                    "uploadDate": "'.$iso8601_date.'",
                    "duration": "",
                    "contentUrl": "'.$value['video_url'].'",
                    "embedUrl": "'.youtubeEmbedUrl($value['video_url']).'",
                    "interactionStatistic": {
                        "@type": "InteractionCounter",
                        "interactionType": {
                            "@type": "http://schema.org/WatchAction"
                        },
                        "userInteractionCount": ""
                    },
                    "regionsAllowed": "US"
                }
                </script>';
                $video_index++;
            }else{
                unset($brochure['videos'][$key]);
            }
        }
        $brochure['videos'] = array_values($brochure['videos']);
        if(count($brochure['videos']) > 0){
            $carouselVideoUrl = $brochure['videos'][0]['video_url'];
        }
    }

    

    $pressRelease = '';
    if (count($profile['press_releases']) > 0 && $profile['site_id'] == 5) { 
        foreach ($profile['press_releases'] as $key => $value) {
            $postDateFull = explode(' ', $value['release_date']);
            $postDate = date("j M, Y", strtotime($value['release_date']));
            $pressLink = $value['external_link_url'];
            $pressRelease .= '<div class="eachPressRelease">
                <strong>'.$postDate.' - '.$value['title'].'</strong>
                <blockquote><a href="'.$pressLink.'">Details</a></blockquote>
            </div>';
        }
    } 
    
    $data_invest = preg_replace('/[^0-9]/','',$profile['investment']);
    if(empty($profile['investment'])){
        $data_invest = 0;
    }
    $data_name = preg_replace('/[^0-9a-zA-Z ]/','',$profile['name']);
    $isProfile = true;
    $button = '<button class="basket" onclick="checkCart('.$profile['fbo_id'].',\''.$data_name.'\','.$data_invest.',\''.$units[$profile['site_id']].'\','.$isProfile.','.$profile['rate'].');"><span>Request Free Info</span></button>';
    if (in_array($profile['contract_status'],[3,7]) || is_null($is_pending)) {
        $checkbox = '<div class="result-checkbox"><input type="checkbox" class="temp-checkbox checkbox_'.$profile['fbo_id'].'" value="'.$profile['fbo_id'].'" />'.$button.'</div>';
    }

}else{
    http_response_code(404);
    $_GET['section'] = 'error';
    include('page.php'); // provide your own HTML for the error page
    die();
}
// debug($profile);die;
if(isset($datapoints['FFS_SEO_TitleTag'])){
    $meta_title_override = $datapoints['FFS_SEO_TitleTag'];
}
if(isset($datapoints['FFS_SEO_MetaDescription'])){
    $description_override = $datapoints['FFS_SEO_MetaDescription'];
}
$keywords_override = $profile['brochure'][0]['meta_keywords']; 
 
$title = $brand_array['home_page_title'];

$splide = true;

function usAvailability(){
    global $profile,$api_url,$website_id;
    $temp_available = $profile['locations'][0]['states'];
    $temp_states = $api_url.'/api/location?site_id='.$website_id;
    $temp_states = file_get_contents($temp_states);
    $usa_array = json_decode($temp_states, true); 
    $all_states = array();
    foreach($usa_array['data']['states'] as $key => $value){
        if(!in_array($value['state_code'],$temp_available)){
            array_push($all_states,$value['state_code']);
        }
    }
    $return = '<h2 class="h4 SourceSansPro text-center medium-black-bg white-text">Available In These States:</h2>';
    if(empty($all_states)){
        $return .= '<div class="availContainer">
                        <span class="availability text-center plain-sans">We are currently accepting inquiries in <u>ALL</u> States</span>
                    </div>';
    }else{
        $good = '';
        $bad = '';
        foreach($temp_available as $key => $value){
            $good .= '<li>'.$value.'</li>';
        }
        foreach($all_states as $key => $value){
            $bad .= '<li>'.$value.'</li>';
        }
        $return .= '<div class="availContainer">
                        <span class="availability text-center plain-sans">We are currently accepting inquiries in these states:</span>
                        <ul>'.$good.'</ul>
                    </div>';
        // $return .= '<p class="h4 SourceSansPro text-center medium-black-bg white-text">Not Available In These States:</p>';
        // $return .= '<div class="availContainer">
        //                 <span class="availability text-center plain-sans">We are currently not accepting inquiries in these states:</span>
        //                 <ul>'.$bad.'</ul>
        //             </div>';
        if(count($profile['locations']) > 0){
            $return .= '<h2 class="h4 SourceSansPro text-center medium-black-bg white-text">International Availability:</h2>';
            $return .= '<div class="availContainer">
                        <span class="availability text-center plain-sans">Accepting In These Countries:</span>
                        <ul>
                            <li>United States and Canada</li>
                        </ul>
                    </div>';
        }
    }
    echo $return;
}

if(empty($militaryVeteranPromotion) && empty($testimonial) && empty($videos) && empty($pressRelease)){
    $tabClass .= ' single-tab';
}

if (!in_array($profile['contract_status'],[3,7])) {
    $hideContent = 'hide';
    $large6 = 'pd-15';
    $inactiveText = '<p class="profile-inactive-p">*Please contact "'.$profile['name'].'" to confirm the accuracy of any information provided on this page.</p>';
    // New Profile Logic
    if(isset($_GET['concept_id'])){
        $hideContent = null;
        $global_robots = 'noindex, nofollow';
    }
}

$infoCount = 0;

//Getting Category Id
$filter = '';
if ($profile['master_categories_id']) {
    $filter = '&cat_id='.$profile['master_categories_id'];
}

if(!in_array($profile['contract_status'],[3,7])){
    $searchServicesObj = return_results_array($website_id,0,'&per_page=12&random=1');
    
    $max = 0;
    if(is_array(@$searchServicesObj['data']['data']) && !empty($searchServicesObj['data']['data'])){
        $max = count($searchServicesObj['data']['data']);
    }
}

//Create listing results
function write_listings(){
    global $searchServicesObj,$excludelist,$units,$global_rate,$get_section, $homeState,$website_id;
	if (is_array(@$searchServicesObj['data']['data']) && !empty($searchServicesObj['data']['data'])) {
        $listings = '';
        $hideViewAll = '';
        $i = 0;
        $j = 0;
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

                $fran_name = cleanfix($value['name'],$starter);
                $description = cleanfix($value['ShortDesc'],$starter,true); 
                if (in_array($value['concept_id'], $excludelist)) {
                    $moreInfo = '<div class="view_profile sent" data-fboid="'.$value['fbo_id'].'" onclick="view_profile('.$value['concept_id'].',true,&#39;&#39;);">';
                    $button = '<div class="requested">Info Request Sent</div>';
                }else{
                    $moreInfo = '<div class="view_profile" data-fboid="'.$value['fbo_id'].'" onclick="view_profile('.$value['concept_id'].',false,&#39;&#39;);">';
                    $button = '<button class="basket" onclick="checkCart('.$value['fbo_id'].',&#39;'.$data_name.'&#39;,'.$data_invest.',&#39;'.$units[$value['site_id']].'&#39;,false,'.$value['rate'].');"><span>Request Free Info</span></button>';                              
                    $date_now = date("Y-m-d");
                    $start_date = date('Y-m-d',strtotime($value['start_date']));
                    if (($value['status'] == 3 && $start_date > $date_now) || !in_array($value['status'],[3,7])) {
                        $button = '<button class="noshow"></button>'; 
                    }
                }  
                
                if (!$_GET['landing'] && $_GET['section'] == 'top-franchises' && $i > 49) {
                    $listcount = $i-49;
                }else{
                    $listcount = $i+1;
                }

                if($i % 6 == 0 && $i != 6 && $start != 0){
                    $listings .= '\'],[\'';		
                }
                
                $listings .= '<div class="result-item listing" id="listing_'.$value['fbo_id'].'" data-rate="'.$value['rate'].'" data-invest="'.substr("00000000{$data_invest}", -8).'" data-name="'.$data_name.'" data-id="'.$value['site_id'].'" data-fbo="'.$value['fbo_id'].'" data-order="'.substr("0000{$i}", -4).'">';
                $listings .= '<a class="anchor" name="'.$listcount.'"></a>';
                $listings .= '<div class="item '.$tagName.'">';
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

                $i++;    
            }
        }
    }else{
        $listings = '';             
    }

    echo $listings;
}

$investmentReq = '';
if (!empty($datapoints['TotalInvestmentMin']) && !empty($datapoints['TotalInvestmentMax'])) {
    $investmentReq = number_format($datapoints['TotalInvestmentMin']).' - $'.number_format($datapoints['TotalInvestmentMax']);
}elseif($datapoints['TotalInvestmentMin']){
    $investmentReq = number_format($datapoints['TotalInvestmentMin']);
}

$franchiseFee = '';
if (!empty($datapoints['FranchiseFee']) && !empty($datapoints['FranchiseFeeMax'])) {
    $franchiseFee = number_format($datapoints['FranchiseFee']).' - $'.number_format($datapoints['FranchiseFeeMax']);
}elseif($datapoints['FranchiseFee']){
    $franchiseFee = number_format($datapoints['FranchiseFee']);
}

if (isset($_GET['custom_brochure'])) {
    $global_robots = 'noindex,nofollow';
}

//GA4 Profile View
function ga4_profile_view(){
    global $profile;
    $units = [4 =>'FG',5 =>'FON',6 =>'FCN',7 =>'FCN'];
    $fran_name = cleanfix($profile['name'],$starter);
    echo "ga4_single('view_item',".$profile['fbo_id'].",'".$fran_name."','".$units[$profile['site_id']]."',".$profile['rate'].");\n\r";
}

function buildTableRow($value){
    $finacialInfo .= '<tr>
        <td><span class="'.$value['class'].'"></span></td>
        <td>'.$value['title'].'</td>
        <td><span class="display_labels labels_bottom labels_right" data-labels="'.$value['name'].'">'.$value['val'].'</span></td>
    </tr>';
    return $finacialInfo;
}

function createFinancialInfo(){
    global $profile,$datapoints,$hideContent,$investmentReq,$franchiseFee,$militaryVeteranPromotion,$infoCount;
    $finacialInfo = '';
    $temp_arr = ['investment'=>['name'=>'Basic Information &rsaquo; Investment', 'title'=>'Minimum Cash Required','class'=>'cash-required', 'val'=>'$'.number_format($profile['investment'])],
                    'DisplayNetWorthRequired'=>['name'=>'Detail &rsaquo; Min Net Worth', 'title'=>'Net Worth Required','class'=>'net-worth', 'val'=>'$'.number_format($datapoints['NetWorthRequired'])],
                    'DisplayTotalInvestment'=>['name'=>'Detail &rsaquo; Total Investment Min', 'title'=>'Total Investment', 'class'=>'total-investment', 'val'=>'$'.$investmentReq],
                    'DisplayFranchiseFee'=>['name'=>'Detail &rsaquo; Franchise Fee', 'title'=>'Franchise Fee', 'class'=>'franchise-fee', 'val'=>'$'.$franchiseFee],
                    'DisplayFinanceAvailable'=>['name'=>'Detail &rsaquo; Financing Assistance', 'title'=>'Financing Assistance', 'class'=>'finance-assist', 'val'=>$datapoints['FinanceAvailableType']],
                    'DisplayYearFounded'=>['name'=>'Detail &rsaquo; Year Founded', 'title'=>'Year Founded', 'class'=>'year-founded', 'val'=>$datapoints['YearFounded']],
                    'DisplayExistingUnits'=>['name'=>'Detail &rsaquo; Existing Units', 'title'=>'# of Existing Units', 'class'=>'number-units', 'val'=>$datapoints['ExistingUnits']],
                    'DisplayFranchisingSince'=>['name'=>'Detail &rsaquo; Franchising Since', 'title'=>'Franchising Since', 'class'=>'franchise-since', 'val'=>$datapoints['FranchisingSince']],
                    'DisplayTrainingAndSupport'=>['name'=>'Detail &rsaquo; Training and Support Details', 'title'=>'Training & Support', 'class'=>'training-info', 'val'=>($datapoints['TrainingAndSupport'] == 1 ? 'Yes' : 'No')],
                    'DisplayHomeOfficeLocation'=>['name'=>'Detail &rsaquo; Home Office Location', 'title'=>'Home Office Location', 'class'=>'home-office', 'val'=>$datapoints['HomeOfficeLocation']],             
                    'military'=>['name'=>'Brochure &rsaquo; Military/Veteran Promotion Details', 'title'=>'Military/Veteran Promotion', 'class'=>'military-promo', 'val'=>'Yes']                
                ];

    foreach ($temp_arr as $key => $value) {
        if (in_array($key,['investment','DisplayNetWorthRequired','DisplayTotalInvestment','DisplayFranchiseFee','DisplayFinanceAvailable','DisplayFranchisingSince'])) {
            if ($key == 'investment' && $profile[$key]) {
                $infoCount += 1;
                $finacialInfo .= buildTableRow($value);
            }elseif (!$hideContent && $datapoints[$key]) {
                $infoCount += 1;
                $finacialInfo .= buildTableRow($value);
            }
        }else{
            if ($key == 'military') {
                if($militaryVeteranPromotion){
                    $infoCount += 1;
                    $finacialInfo .= buildTableRow($value);
                }
            }elseif ($datapoints[$key]) {
                $infoCount += 1;
                $finacialInfo .= buildTableRow($value);
            }
        }
    }

    echo $finacialInfo;

}
?>
 
<!doctype html>
<html lang="en-US" translate="no">
<?php include_once('includes/head.php'); ?>
    <body>
        <?php include_once('includes/header.php');  ?>
            <main id="main">
                <section id="profile">
                    <div id="profileHeader"> 
                        <div id="profileInfo" class="profile-header-row container"> 
                            <div class="item1 listing">
                                <div class="logo-block" style="background-image:url('<?php echo $logo; ?>');"></div>
                                <?php echo $checkbox; ?>
                            </div>
                            <div class="item2">
                                <div id="image-slider" class="splide">
                                    <div class="splide__arrows">
                                        <button class="splide__arrow splide__arrow--prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true">◀︎</span><span class="sr-only">Previous</span>
                                        </button>
                                        <button class="splide__arrow splide__arrow--next">
                                            <span class="carousel-control-next-icon" aria-hidden="true">▶︎</span><span class="sr-only">Next</span>
                                        </button>
                                    </div>
                                    <div class="splide__track">
                                        <ul class="splide__list">
                                            <?php echo $carousel; ?>
                                        </ul>
                                    </div>
                                </div>
                                <?php if($videos != ''){ ?>
                                    <div class="slide-link" onclick="setProfileVideo('<?php echo $carouselVideoUrl; ?>','<?php echo $data_name; ?>');carouselVideoClick('video','profile-details-all');"><u class="bold">Watch <?php echo $profile['name']; ?> Video(s) </u><span>&nbsp;</span></div>
                                <?php } ?>
                            </div>
                        </div> 
                    </div>
                    <div class="franchise-oview container white-bg">
                        <div class="large-6 summary">
                            <h1 class="SourceSansPro"><?php echo $conceptName; ?></h1>
                            <br>
                            <p class="h4 SourceSansPro">Quick Summary</p>
                            <p class="text-left"><?php echo $shortDescription; ?></p>
                            <div class="listing">
                                <?php echo $checkbox; ?>
                            </div>
                            <?php echo $inactiveText; ?>
                        </div>
                        <div class="large-6 quickFacts">
                            <h2 class="h4 SourceSansPro text-center medium-black-bg white-text"><?php echo $profile['name']; ?> <br>Business Cost, Fees &amp; Facts for <?php echo date('Y'); ?></h2>
                            <div class="qFWrapper">
                                <table>
                                    <tbody>
                                        <?php createFinancialInfo(); ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php if($infoCount > 3){ ?>
                                <div id="viewQuickFacts" class="button hollow tiny qFBtn" onclick="toggleProfileViewFacts();">View More Quick Facts</div>
                            <?php } ?>
                            <div class="usAvailability"><?php usAvailability(); ?></div>
                        </div>
                    </div>
                    <div class="profile-details-all container white-bg">
                        <ul class="tabs <?php echo $tabClass ?>" id="profile-tabs">
                            <li class="tabs-title is-active" id="tabOverview" onclick="netWorthTab(event,'overview','profile-details-all');">Overview</li>
                            <?php if($militaryVeteranPromotion != ''){ ?>
                                <li class="tabs-title" id="militaryVeteranPromotion" onclick="netWorthTab(event,'veteran','profile-details-all');">Military & Veteran</li>
                            <?php } 
                            if($testimonial != ''){ ?>
                                <li class="tabs-title" id="tabTestimonial" onclick="netWorthTab(event,'testimonials','profile-details-all');">Testimonials</li>
                            <?php }
                            if($videos != ''){ ?>
                                <li class="tabs-title" id="tabVideo" onclick="netWorthTab(event,'video','profile-details-all');">Video</li>
                            <?php } 
                            if($pressRelease != ''){ ?>
                                <li class="tabs-title" id="tabPress" onclick="netWorthTab(event,'press','profile-details-all');">Press Releases</li>
                            <?php } ?>   
                        </ul>
                        <div class="tabs-content">
                            <div class="tabs-panel show" id="overview">
                                <!-- <h3 class="h3">Overview</h3> -->
                                <h2><?php echo $conceptName; ?></h2>
                                <div class="textContent">
                                    <?php echo $longDescription; ?>
                                </div>
                            </div>
                            <?php if($militaryVeteranPromotion != ''){ ?>
                            <div class="tabs-panel" id="veteran">
                                <!-- <h3 class="h3">Military &amp; Veteran Promotions</h3> -->
                                <p><?php echo $militaryVeteranPromotion; ?></p>
                            </div>
                            <?php }
                            if($testimonial != ''){ ?>
                            <div class="tabs-panel" id="testimonials">
                                <!-- <h3 class="h3 bold">Testimonials</h3> -->
                                <div class="textContent">
                                    <div class="fromCustomer">
                                        <?php echo $testimonial; ?>
                                    </div>
                                </div> 
                            </div>
                            <?php }
                             if($videos != ''){ ?>
                            <div class="tabs-panel" id="video">
                                <div>
                                    <!-- <h3 class="h3"><?php echo $profile['name']; ?> Video</h3> -->
                                    <div class="large-8 video-display-block">
                                        <div id="videoElement" style="max-height:410px">
                                            
                                        </div>
                                        <div id="videoException" class="show-for-medium"><br>
                                            <small><em>Video not loading? Click <a id="videoDisplayLink" href="">here</a> to download it.</em></small>
                                        </div>
                                    </div>
                                    <div class="profile-video-row">
                                        <?php echo $videos; ?>
                                    </div>
                                </div>
                            </div>
                            <?php } 
                            if($pressRelease != ''){?>
                            <div class="tabs-panel" id="press">
                                <!-- <h3 class="h3">Press Releases & Awards</h3> -->
                                <div class="press-rel">
                                    <?php echo $pressRelease; ?>
                                </div>
                            </div>
                            <?php } ?>
                            <br class="<?php echo $hideContent; ?>">
                            <div class="text-center <?php echo $hideContent; ?>">
                                <div class="listing bottom-btn text-center">
                                    <?php echo $checkbox; ?>
                                </div>
                            </div>
                        </div>
                        <?php if(!in_array($profile['contract_status'],[3,7]) && $max > 0){ ?>
                            <section id="profile-listings">
                                <section id="results" class="container">
                                    <h2>Thank You for Your Interest in this <?php echo $opt_name; ?> Opportunity!</h2>
                                    <p class="xy-padded">Great news! <strong><?php echo $profile['name']; ?></strong> has reached its current goals thanks to high demand. More franchise and business opportunities are coming soon,
                                        <strong><a href="https://www.franchiseforsale.com/newsletter-sign-up">sign up here</a></strong> for updates and be the first to know! Below are a few other suggestions to start with.</p>
                                    <?php write_listings() ?>
                                </section>
                            </section>
                        <?php } ?>
                    </div> 
                </section>

            </main>
            <?php include_once('includes/footer.php'); ?>
            <script>
                <?php ga4_profile_view(); ?> 
                document.addEventListener( 'DOMContentLoaded', function () {
                    new Splide( '#image-slider', {
                        type  : 'fade',
                        perPage : 1,
                        autoplay: true,
                    } ).mount();
                });
            </script>
            <?php echo $video_json; ?>
    </body>
</html>