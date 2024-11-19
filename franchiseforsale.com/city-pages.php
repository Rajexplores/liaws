<?php
$callFboChecker = true;
include_once('includes/global.php');
$add_page_type = 'city_page';
$city_state_url = $_GET['city'].'-'.$_GET['state_name'];
$cs_city = ucwords(str_replace('-',' ',$_GET['city']));
$cs_state = ucwords(str_replace('-',' ',$_GET['state_name']));
if(isset($_GET['state_name'])){
    foreach($filterstate as $key => $value){
        if($_GET['state_name']  == $value){ 
            $homeState = $key;
            $state_name = $value;
            $state_title = ucwords(str_replace("-", " ", $state_name));
            $breadcrumbs['2']['title'] = 'Near Me';
            $breadcrumbs['2']['url'] = 'near-me';
            $breadcrumbs['3']['title'] = $cs_city.', '.$cs_state;
            $breadcrumbs['3']['url'] = '';
        }
    }
}


$temp_array = get_json('/api/citypage_cities/'.$city_state_url.'?site_id='.$website_id);
$temp_array = json_decode($temp_array,true);

$population = '';
$population_growth = '';
$household_income = '';
$median_age = '';
$residents_per_business = '';
$hosuehold_expenditure = '';
$intro_text = '';
$top_intro = '';
$bottom_text = '';
$read_more = '';

$banner_h1 = 'Franchise Opportunities in '.$cs_city.', '.$cs_state;
$h2 = $cs_city.', '.$cs_state.' Franchise Opportunities (and Business for Sale)';

function nice_number($n) {
    // first strip any formatting;
    $n = (0+str_replace(",", "", $n));

    // is this a number?
    if (!is_numeric($n)) return false;

    // now filter it;
    if ($n > 1000000000000) return round(($n/1000000000000), 2).' trillion';
    elseif ($n > 1000000000) return round(($n/1000000000), 2).' billion';
    elseif ($n > 1000000) return round(($n/1000000), 2).' million';
    elseif ($n > 1000) return round(($n/1000), 2).' thousand';

    return number_format($n);
}
$city_table_body = '';
if($temp_array['data']){
    $city_data = $temp_array['data'];
    // debug($city_data);die;

    // city state data
    $city_array = ['population'=>'Population','population_growth'=>'Proj. 5 Year Population Growth','household_income'=>'Median Household Income','median_age'=>'Median Age','residents_per_business'=>'Residents per Business','annual_expenditure'=>'Annual Household Expenditures'];
    $worldwide_array = ['population'=>'333.2 million','population_growth'=>'0.5%','household_income'=>'$74,580','median_age'=>'38.9','residents_per_business'=>'10 to 1','annual_expenditure'=>'$23.9 trillion'];

    foreach ($city_array as $key => $value) {
        if ($city_data[$key]) {
            $$key = $city_data[$key];
            $formated_val = $city_data[$key];
            if (in_array($key,['population','annual_expenditure'])) {
                $formated_val = nice_number($city_data[$key]);
            }elseif($key == 'household_income'){
                $formated_val = '$'.number_format($city_data[$key]);
            }elseif($key == 'population_growth'){
                $formated_val = $city_data[$key].'%';
            }
            if ($key == 'annual_expenditure') {
                $formated_val = '$'.$formated_val;
            }
            $city_table_body .= '<tr>
                <td>'.$value.'</td>
                <td>'.$formated_val.'</td>
                <td>'.$worldwide_array[$key].'</td>
            </tr>';
            $additionalProperty[] = '{
                "@type": "PropertyValue",
                "name": "'.$value.'",
                "value": "'.$formated_val.' in '.$cs_state.', '.$worldwide_array[$key].' Nationwide"
            }';
        }
    }

    // Seo data
    if ($city_data['seo']) {
        $city_seo = $city_data['seo'];
        $intro_text = $city_seo['intro_text'] ? $city_seo['intro_text'] : '';
        $top_intro = $city_seo['top_intro_text'] ? $city_seo['top_intro_text'] : '';
        $bottom_text = $city_seo['bottom_text'] ? $city_seo['bottom_text'] : '';
        $banner_h1 = $city_seo['h1'] ? $city_seo['h1'] : $banner_h1;
        $h2 = $city_seo['h2'] ? $city_seo['h2'] : $h2;
        $read_more = $city_seo['read_more'] ? $city_seo['read_more'] : '';
        if ($city_seo['meta_title']) {
            $meta_title_override = $city_seo['meta_title'];
        }
        if ($city_seo['meta_description']) {
            $description_override = $city_seo['meta_description'];
        }
    }
}

function json_city_table(){
    global $additionalProperty,$homeState,$cs_city,$cs_state;
    $city_table = '<script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Dataset",
        "name": "Key Data Points for '.$cs_city.', '.$cs_state.'",
        "description": "This dataset provides key data points for '.$cs_city.', '.$cs_state.', compared to Nationwide statistics.",
        "url": "https://sitesusa.com/Data",
        "distribution": [
          {
            "@type": "DataDownload",
            "contentUrl": "https://sitesusa.com/Data",
            "name": "Population Data",
            "additionalProperty": ['.implode(",",$additionalProperty).']
          }
        ]
    }
    </script>';
    echo $city_table;
}

function json_bredcrumbs(){
    global $cs_city, $cs_state;
    $breadcrumb = '<script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "BreadcrumbList",
        "itemListElement":
        [
        {
        "@type": "ListItem",
        "position": 1,
        "item":
        {
            "@id": "https://'.$_SERVER['HTTP_HOST'].'/near-me",
            "name": "Near Me"
            }
        },
        {
        "@type": "ListItem",
        "position": 2,
        "item":
        {
            "@id": "",
            "name": "'.$cs_city.', '.$cs_state.'"
        }
        }
        ]
    }
    </script>';
    echo $breadcrumb;
}

$searchServicesObj = return_results_array($website_id,$paid,$filter);

//Create listing results
function write_listings($start,$stop){
    global $searchServicesObj,$units, $homeState,$website_id,$spinner;
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
                    $button = '<button class="basket" onclick="checkCart('.$value['fbo_id'].',&#39;'.$data_name.'&#39;,'.$data_invest.',&#39;'.$units[$value['site_id']].'&#39;,false,'.$value['rate'].','.substr("0000{$i}", -4).');"><span>Request Free Info</span></button>';                               
                }  
                if($i > $stop){
                    break;
                }else{

                    if($i >= $start || $i == $max){

                            if($i % 6 == 0 && $i != 6 && $start != 0){
                                $listings .= '\'],[\'';		
                            }
                            
                            $listings .= '<div class="result-item listing" id="listing_'.$value['fbo_id'].'" data-rate="'.$value['rate'].'" data-invest="'.substr("00000000{$data_invest}", -8).'" data-name="'.$data_name.'" data-id="'.$value['site_id'].'" data-fbo="'.$value['fbo_id'].'" data-order="'.substr("0000{$i}", -4).'">';
                            $listings .= '<div class="item '.$tagName.'">';
                            // $noEvents = '';
                            $landingSummary = '';
                            $hideViewAll = '';
                            $noEvents = 'no-events';
                            $checkValue = checkIfFboIdExists($value['fbo_id'],$value['site_id'],$value['brochure_url']);
                            if ($checkValue) {
                                $brochure_url = $checkValue;
                                $brochure_url = 'href="'.$brochure_url.'"';
                            }else{
                                $brochure_url = 'data-name="'.$data_name.'"';
                            }
                            // $brochureUrl = profileChangeUrl($value['brochure_url'],$value['concept_type']);
                            $brochureUrl = $value['brochure_url'];
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

if ($intro_text) {
    $banner_intro = '<div class="paragraphWrapper white-text">
        '.$intro_text.'
    </div>';
}

$banner = '<section id="banner" class="landing-dir-banner banner-bg mx-height">
        <div class="container">
            <div class="introBack banner-intro-bg w-100 ">
                <div class="white-text h1">'.$banner_h1.'</div>
                '.$banner_intro.'
            </div>
        </div>
    </section>';

?>

<!doctype html>
<html lang="en-US" translate="no">
<?php include_once('includes/head.php'); ?>
    <body>
        <?php include_once('includes/header.php'); ?>
        <main id="main" class="pages-main city-main">
            <?php echo $banner; ?>
            <div class="container">
                <div id="city_pages">
                    <div id="dirStates" class="advertise">
                        <div class="states-block white-bg">
                            <div class="white-bg xy-padded mb-1">   
                                <p>The table below offers a collection of data aimed at giving potential franchisees a glimpse into their market landscape. Click Read More to delve deeper, uncovering additional information to assist you in making a well-informed decision. These insights are tailored to highlight the opportunities in your city as you consider a franchise purchase.</p>                             
                                <table>
                                    <tr><td colspan="3">Key Data Points for Acworth, Georgia</td></tr>
                                    <tr>
                                        <th>Data</th>
                                        <th>New York</th>
                                        <th>Worldwide</th>
                                    </tr>
                                    <?php echo $city_table_body; ?>
                                    <tr><td colspan="3">Table estimations are based on a 5 mile radius from the geographical city center. Data retrieved from https://sitesusa.com/Data</td></tr>
                                </table>
                                <br>
                                <?php 
                                    echo $top_intro;
                                    if ($read_more) {
                                        echo '<a href="javascript:void(0);" onclick="toggleRM();">Read More</a><div id="city_read_more" class="hide">'.$read_more.'</div>';
                                    }
                                ?>
                                <p>Explore the franchise listings below and take the first step towards a rewarding business venture.</p>
                            </div>
                            <hr>
                            <div class="white-bg xy-padded mb-1">
                                <h2><?php echo $h2 ?></h2>
                            </div>
                            <div id="results" class="white-bg mb-1"><?php write_listings(0,5); ?></div> 
                            <div id="refresh" class="container hide">
                                <div class="columns">
                                    <span class="bold text-center" style="display:block">Pages:</span>
                                    <ul class="searchPaging no-bullet text-center">
                                        <?php echo $pagination; ?>
                                    </ul>
                                </div>
                            </div>  
                            <div id="seeMoreRes" class="white-bg xy-padded mb-1">
                                <p class="text-center"><a href="javascript:void(0);" onclick="togglePagination();">See More Opportunities</a></p>
                            </div>
                            <hr>
                            <div class="white-bg xy-padded mb-1">
                                <p>At Franchise Ventures, we prioritize transparency and objectivity to empower your franchise decisions. Unlike brokers or franchisors, our motivations are unclouded by financial incentives tied to your choices. We don&#039;t earn based on the franchise you opt for, instead, our mission is to present you with impartial insights. Navigate through a realm of franchising opportunities with information you can trust.</p>
                                <?php echo $bottom_text ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <?php include_once('includes/footer.php'); ?>

        <script>
            <?php 
                function getListings(){
                    return write_listings(6,999);
                }
            ?>

            var child_listings = <?php getListings(); ?>,
                loaded = 0,
                child_length = child_listings.length;
            submitted(<?php echo $submitted_count; ?>);

            var isLoad = false;

            function togglePagination() {
                isLoad = true;
                document.getElementById('seeMoreRes').classList.add('hide');
                document.getElementById('refresh').classList.remove('hide');

                document.getElementById("results").innerHTML += child_listings[loaded]; 
                lazyload(); 
                loaded++;
            }

            var findMe = document.querySelector('#refresh'); 
            window.addEventListener('scroll', function (event) {
                if (isLoad && isInViewport(findMe) && loaded != child_length) {
                    document.getElementById("results").innerHTML += child_listings[loaded]; 
                    lazyload(); 
                    loaded++;
                }
            }, false);

            function toggleRM() {
                document.getElementById('city_read_more').classList.toggle('hide');
            }
        </script>
        <?php json_city_table(); ?>
        <?php json_bredcrumbs(); ?>
    </body>
</html>