<?php 
$is_index = true;
$callFboChecker = true;
include_once('includes/global.php');
$add_page_type = 'home';
$listing_array = [];
$listings = '';
$carousel = '';

// Push into Array of Already Used Listings to Exclude
function push_to_listings_array($array){
    global $listing_array;
    $temp = array_column($array['data']['data'],'fbo_id');
    $listing_array = array_merge($listing_array,$temp);
    return implode(',',$listing_array);
}

// Generate Home Page Listings
function generate_listings($searchServicesObj,$lazy = true){
    global $units,$website_id;
    $i = 1;
    $temp_listings = '';
    foreach($searchServicesObj['data']['data'] as $key => $value) {
        $data_invest = preg_replace('/[^0-9]/','',$value['investment']);
        if(empty($value['investment'])){
            $data_invest = 0;
        }
        $data_name = preg_replace('/[^0-9a-zA-Z ]/','',$value['name']);
        $fran_name = cleanfix($value['name'],false); 
        $description = cleanfix($value['ShortDesc'],false,true); 
        $dimensions = (strpos($value['image_url'], '250x150')) ? 'width="250" height="150"' : '';
        $button = '<button class="basket" onclick="checkCart('.$value['fbo_id'].',&#39;'.$data_name.'&#39;,'.$data_invest.',&#39;'.$units[$value['site_id']].'&#39;,true,'.$value['rate'].','.substr("0000{$i}", -4).');"><span>Request Free Info</span></button>';
        $noEvents = '';
        $checkValue = checkIfFboIdExists($value['fbo_id'],$value['site_id'],$value['brochure_url']);
        if ($checkValue) {
            $brochure_url = $checkValue;
            $brochure_url = 'href="'.$brochure_url.'"';
        }else{
            $brochure_url = 'data-name="'.$data_name.'"';
            $noEvents = 'no-events';
        }
        $date_now = date("Y-m-d");
        $start_date = date('Y-m-d',strtotime($value['start_date']));
        if (($value['status'] == 3 && $start_date > $date_now) || !in_array($value['status'],[3,7])) {
            $button = '<button class="noshow"></button>'; 
        }
        // $temp_listings .= '<li class="splide__slide">';
        $temp_listings .= '<div class="result-item listing fadeout" id="listing_'.$value['fbo_id'].'" data-rate="'.$value['rate'].'" data-invest="'.substr("00000000{$data_invest}", -8).'" data-name="'.$data_name.'" data-id="'.$value['site_id'].'" data-fbo="'.$value['fbo_id'].'" data-order="'.substr("0000{$i}", -4).'">';
        $temp_listings .= '<div class="item">';
        $lazyload = ($lazy || (!$lazy && $i > 1)) ? 'loading="lazy"' : '';
        $temp_listings .= '<a class="'.$noEvents.'" '.$brochure_url.'><div class="result-img"><img class="concept-logo" '.$lazyload.' '.$dimensions.' src="'.safe_logos($value['image_url']).'" alt="'.$fran_name.'" title="'.$fran_name.'"></div></a>';
        // $temp_listings .= '<div class="pd-15">';
        // if($value['site_id'] != $website_id){
        //     $temp_listings .= '<h3 class="">'.$fran_name.'</h3>';
        // }else{
            $temp_listings .= '<h3 class=""><a class="'.$noEvents.'" '.$brochure_url.'>'.$fran_name.'</a></h3>';
        // }
        $temp_listings .= '<p class="summary"><a class="'.$noEvents.'" '.$brochure_url.'>'.$description.'</a></p>';
        $temp_listings .= '<div class="cash-block">';
        $temp_listings .= '<p class="investment"><small><span>$</span></small> '.number_format($value['investment']).' Min.Cash Required</p>';
        $temp_listings .= '</div></div>';
        $temp_listings .= '<div class="result-checkbox">';
        $temp_listings .= '<input type="checkbox" class="temp-checkbox checkbox_'.$value['fbo_id'].'" value="'.$value['fbo_id'].'" id="checkbox_'.$value['fbo_id'].'">';
        $temp_listings .= $button;
        // $temp_listings .= '</div>';
        $temp_listings .= '</div></div>';
        // $temp_listings .= '</li>';
        $i++;
    }
    return $temp_listings;
}

// Featured Franchises
$searchServicesObj = return_results_array($website_id,$paid,'&filter=top100&per_page=3&randomize_count=20'); 
$listings = generate_listings($searchServicesObj,false);

// New to the Site
$searchServicesObj3 = return_results_array($website_id,$paid,'&filter=new&per_page=1&randomize_count=20&requestlist='.push_to_listings_array($searchServicesObj)); 
$newFranCarousel = generate_listings($searchServicesObj3);

// Low Cost Franchises
$searchServicesObj1 = return_results_array($website_id,$paid,'&min=0&max=50000&per_page=1&randomize_count=20&requestlist='.push_to_listings_array($searchServicesObj3)); 
$lowcostfran = generate_listings($searchServicesObj1);

// Hot & Trendy Franchises
$searchServicesObj2 = return_results_array($website_id,$paid,'&filter=top25&per_page=3&randomize_count=20&requestlist='.push_to_listings_array($searchServicesObj1)); 
$hottrendyfran = generate_listings($searchServicesObj2);

$meta_title_override = 'Franchises & Business Opportunities for Sale | FranchiseForSale.com';
$description_override = 'Search a wide selection of affordable franchises and business opportunities on FranchiseForSale.com. Find franchises for sale at all different price points and industries. Search now and find your dream business!';

$noBreadcrumbs = 'hide';
$noAddBasketBlock = 'no-add-basket';
$is_home = true;

//Popular Franchise List
function popular_franchises(){
    $list = '';
    $array = [
        'advertising'=>['url'=>'advertising-franchises','text'=>'Advertising'],
        'automotive'=>['url'=>'auto-franchises','text'=>'Auto'],
        'beauty'=>['url'=>'beauty-franchises','text'=>'Beauty'],
        'business-opportunities'=>['url'=>'business-opportunities','text'=>'Business Opportunities'],
        'business-services'=>['url'=>'business-services-franchises','text'=>'Business Services'],
        'child-related'=>['url'=>'child-related-franchises','text'=>'Child Related'],
        'cleaning'=>['url'=>'cleaning-franchises','text'=>'Cleaning'],
        'computer-internet'=>['url'=>'computer-internet-franchises','text'=>'Computer, Ecomm + Internet'],
        'education'=>['url'=>'education-training-franchises','text'=>'Education'],
        'entertainment'=>['url'=>'entertainment-franchises','text'=>'Entertainment'],
        'financial-services'=>['url'=>'financial-franchises','text'=>'Financial Services'],
        'food'=>['url'=>'food-franchises','text'=>'Food'],
        'health-fitness'=>['url'=>'health-fitness-franchises','text'=>'Health + Fitness'],
        'home-based'=>['url'=>'home-based-franchises','text'=>'Home Based'],
        'home-services'=>['url'=>'home-services-repair-franchises','text'=>'Home Services + Repair'],
        'low-cost'=>['url'=>'low-cost-franchises','text'=>'Low Cost'],
        'pet'=>['url'=>'pet-franchises','text'=>'Pet'],
        'print-copy'=>['url'=>'printing-copy-shipping-signs','text'=>'Printing, Copying, Shipping, Signs'],
        'retail'=>['url'=>'retail-franchises','text'=>'Retail'],
        'senior-care'=>['url'=>'senior-care-franchises','text'=>'Senior Care'],
        'sports'=>['url'=>'sports-recreation-franchises','text'=>'Sports + Recreation'],
        'travel'=>['url'=>'travel-franchises','text'=>'Travel'],
        'vending'=>['url'=>'vending-franchises','text'=>'Vending'],
        'veterans'=>['url'=>'veteran-franchises-for-sale','text'=>'Veterans']
    ];
    foreach($array as $key => $value){
        $list .= '<li class="'.$key.'"><a href="/industry/'.$value['url'].'"><span></span>'.$value['text'].'</a></li>';
    }
    return '<div class="popular-fran-list"><div class="fran-list-item"><br><h2 class="h2 text-center bold">Popular Franchises For Sale</h2><ul>'.$list.'</ul></div></div>';
}

//Phone Quicklinks
function phone_quicklinks(){
    $list = '';
    $array = [
        'food'=>['url'=>'/industry/food-franchises','text'=>'Food'],
        'new'=>['url'=>'/new-franchises','text'=>'Recently Added'],
        'low'=>['url'=>'/low-cost-franchises','text'=>'Low Cost'],
        'hot'=>['url'=>'/hot-and-trendy-franchises','text'=>'Hot &amp; Trendy']
    ];
    foreach($array as $key => $value){
        $list .= '<div class="small-6"><a href="'.$value['url'].'"><div class="tile '.$key.'"><span></span>'.$value['text'].'<br> Franchises</div></a></div>';
    }
    return '<div class="phone-quick-links hidden-xs">'.$list.'</div>';
}

//Splide Function
function create_splide($value,$trendy = false){
    return '<div class="splide__track">
                <ul class="splide__list">'.$value.'</ul>
            </div>';
    // return '<div class="splide__arrows">
    //             <button class="splide__arrow splide__arrow--prev">
    //                 <span class="arrow-btn carousel-control-prev-icon" aria-hidden="true"></span> <span class="sr-only">Previous</span>
    //             </button>
    //             <button class="splide__arrow splide__arrow--next">
    //                 <span class="arrow-btn carousel-control-next-icon" aria-hidden="true"></span> <span class="sr-only">Next</span>
    //             </button>
    //         </div>
    //         <div class="splide__track">
    //             <ul class="splide__list">'.$value.'</ul>
    //         </div>';
}
?>
<!doctype html>
<html lang="en-US" translate="no">
<?php include_once('includes/head.php'); ?>
    <body>
        <?php include_once('includes/header.php');  ?>
            <main id="main" class="home-main home-page">
                
                <section id="home">
                    <div id="homeBanner" class="banner">
                        <div class="container">
                            <div class="introBack">
                                <h1>Find Great <br> Franchises For Sale</h1>
                                <br>
                            </div>
                            <div class="home-filter"> 
                                <?php include_once('includes/filter.php'); ?>
                            </div>
                        </div>
                        <br>
                    </div>
                    
                    <div class="popular-franchises hidden-xs">
                        <div class="container">
                            <div class="popular-fran-tabs">
                                <a class="tab-item">
                                    <b>What's Popular</b> 
                                    <span class="chevron"></span>
                                </a>
                            </div>
                            <?php echo popular_franchises(); ?>
                        </div>
                    </div>
                    <?php echo phone_quicklinks(); ?>
                    <div class="featured-franchises container">
                        <br>
                        <p class="h2 text-center">Featured Franchises</p>
                        <div id="featured-franchises-slider" class="result_container"><?php echo $listings; ?></div>
                        <div class="view-all-franchise-btn text-center">
                            <a href="/featured-franchises" class="viewAll button">View All Featured Franchises »</a>
                        </div>
                    </div>
                    <div class="latest-blogs container">
                        <div class="new-fran-item large-6">
                            <div id="newFranchises" class="fran-block-item featured-franchises">
                                <p class="h4 text-center">New To The Site</p>
                                <p class="text-center show-for-medium">Discover new franchises in your area</p>
                                <div id="newFran-slider" class="result_container"><?php echo $newFranCarousel; ?></div>
                                <div class="view-btn text-center"><a href="/new-franchises" class="viewAll button">View All New To The Site »</a></div>
                            </div>
                        </div>
                        <div class="new-fran-item large-6">
                            <div id="lowcostfranchises" class="fran-block-item featured-franchises">
                                <p class="h4 text-center">Low Cost Franchises</p>
                                <p class="text-center show-for-medium">Discover affordable franchises in your area</p>
                                <div id="lowcost-slider" class="result_container"><?php echo $lowcostfran; ?></div>
                                <div class="view-btn text-center"><a href="/low-cost-franchises" class="viewAll button">View All Low Cost Franchises</a></div>
                            </div>
                        </div>
                    </div>
                    <div class="latest-blogs container">
                        <div class="new-fran-item large-6 w-100">
                            <div id="hotfranchises" class="fran-block-item featured-franchises">
                                <p class="h4 text-center">Hot &amp; Trendy Franchises</p>
                                <p class="text-center show-for-medium">Discover trendy franchises in your area</p>
                                <div id="hotfran-slider" class="result_container"><?php echo $hottrendyfran ?></div>
                                <div class="view-btn text-center"><a href="/hot-and-trendy-franchises" class="viewAll button">View All Hot Franchises</a></div>
                            </div>
                        </div>
                    </div>
                </section>
            </main>
        <?php include_once('includes/footer.php'); ?>
        <?php load_js('index'); ?>
        <script type="application/ld+json">
            {
                "@context": "http://schema.org",
                "@type": "Organization",
                "name": "FranchiseForSale.com",
                "url": "https://www.franchiseforsale.com/",
                "logo": "https://franchise-ventures-general.s3.amazonaws.com/cdn_ffs/images/og-ffs-logo.png",
                "address": {
                    "@type": "PostalAddress",
                    "streetAddress": "150 Granby Street",
                    "addressLocality": "Norfolk",
                    "addressRegion": "VA",
                    "postalCode": "23510",
                    "addressCountry":"US"
                },
                "contactPoint": {
                    "@type": "ContactPoint",
                    "telephone": "888-363-3390",
                    "contactType": "customer service"
                },
                "description": "FranchiseForSale.com offers a selection of franchise opportunities catered to those seeking new business ventures. It serves as a resourceful hub, guiding prospective franchisees to make well-informed investment decisions that align with their financial capabilities and entrepreneurial aspirations."
            }
        </script>
    </body>
</html>