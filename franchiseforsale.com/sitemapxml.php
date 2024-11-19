<?php
include_once('includes/global.php');
$redirected_urls = redirections($site_id,false);
$pages = '';
$protocol = 'https://';
$domain_url = $protocol.$_SERVER['SERVER_NAME'];
$categories = [
    'advertising-franchises',
    'auto-franchises',
    'beauty-franchises',
    'business-opportunities',
    'business-services-franchises',
    'child-related-franchises',
    'cleaning-franchises',
    'computer-internet-franchises',
    'education-training-franchises',
    'entertainment-franchises',
    'financial-franchises',
    'food-franchises',
    'green-eco-friendly-franchises',
    'health-fitness-franchises',
    'home-based-franchises',
    'home-services-repair-franchises',
    'low-cost-franchises',
    'manufacturing',
    'mobile-franchises',
    'pet-franchises',
    'photography-video',
    'printing-copy-shipping-signs',
    'restoration-disaster-recovery-franchises',
    'retail-franchises',
    'seasonal-franchises',
    'security-alarm-franchises',
    'senior-care-franchises',
    'recreation-sports-franchises',
    'travel-franchises',
    'vending-franchises',
    'veteran-franchises-for-sale',
    'wholesale-distribution-franchises'
];

function priorities($priority = null,$change = 'daily'){
    $priorities = "<lastmod>".date('Y-m-d')."</lastmod>\r\n\t";
    if(!is_null($priority)){
        $priorities .= "<changefreq>".$change."</changefreq>\r\n\t<priority>".$priority."</priority>\r\n\t";
    }
    return $priorities;
}
if($_GET['sitemap'] == 'concepts'){ //PROFILES
    $profiles = get_json('/api/profiles_mapping/'.$site_id.'?pending=no');
    $profiles = json_decode($profiles,true);
    foreach ($profiles['data'] as $key => $value) {
        if(array_key_exists($key,$redirected_urls)){
            continue;
        }
        $pages .= "\t<url>\r\n\t\t<loc>".$domain_url.$key."</loc>\r\n\t".priorities(null,null)."</url>\r\n";
    }
}else if($_GET['sitemap'] == 'blog'){ //WORDPRESS
    $url = 'https://www.franchiseopportunities.com/blog/sitemap-json'; 
    if ($subdomain_check[0] != 'www'){
        $url = 'https://www2.franchiseopportunities.com/blog/sitemap-json';   
    }
    $response = (file_get_contents($url));
    $n = strpos($response, "[");
    $response = str_replace('"', "", $response);
    $response = substr_replace($response,"",0,$n+1);
    $response = substr_replace($response, "" , -1,1);
    $array = explode(',',$response);
    foreach ($array as $key => $value) {
        $pages .= "\t<url>\r\n\t\t<loc>".stripslashes($value)."</loc>\r\n\t".priorities('0.8','daily')."</url>\r\n";
    }
}else if($_GET['sitemap'] == 'info'){ //active custom sitepages in info/
    $sitepages = get_sitepages($website_id); 
    if(count($sitepages)) {
        foreach ($sitepages as $sitepage) {
            $pages .= "\t<url>\r\n\t\t<loc>".$domain_url."/info/".$sitepage['url']."</loc>\r\n\t".priorities(null,null)."</url>\r\n";
        }    
    }
}else if($_GET['sitemap'] == 'categories'){ //active custom sitepages in info/
    foreach ($categories as $key => $value) {
        $pages .= "\t<url>\r\n\t\t<loc>".$domain_url."/industry/".$value."</loc>\r\n\t".priorities(1,'daily')."</url>\r\n";
    }
}else if($_GET['sitemap'] == 'categories-by-state'){ //active custom sitepages in info/
    foreach ($filterstate as $subkey => $subvalue) {
        if(in_array($subkey,['CAN','INT'])){
            continue;
        }
        foreach ($categories as $key => $value) {
            $pages .= "\t<url>\r\n\t\t<loc>".$domain_url."/".$value."_in_".$subvalue."</loc>\r\n\t".priorities(1,'daily')."</url>\r\n";
        }
    }
}else if($_GET['sitemap'] == 'main'){ //active custom sitepages in info/
    $sitemap_urls = [
        '',
        'featured-franchises',
        'new-franchises',
        'hot-and-trendy-franchises',
        'low-cost-franchises',
        'high-investment-franchises',
        'top-franchises',
        'finance-center',
        'site-map',
        'photography-credits',
        'about-us',
        'advertise-with-us',
        'contact-us',
        'privacy-website-usage-policy',
        'industry',
        'investment-level',
        'state',
        'alphabetical-company-search'
    ];
    foreach ($sitemap_urls as $key) {
        $temp = "/".$key;
        if($key == ''){
            $temp = '';
        }
        $pages .= "\t<url>\r\n\t\t<loc>".$domain_url.$temp."</loc>\r\n\t".priorities(1,'daily')."</url>\r\n";
        if($key == 'investment-level'){
            foreach ($investment_array as $subvalue) {
                $over_under = 'under';
                if($subvalue == 500001){
                    $over_under = 'over';
                    $subvalue = 500000;
                }
                $pages .= "\t<url>\r\n\t\t<loc>".$domain_url."/investment-level/franchises-".$over_under."-".$subvalue."</loc>\r\n\t".priorities(1,'daily')."</url>\r\n";
            }
        }
        if($key == 'state'){
            foreach ($filterstate as $subkey => $subvalue) {
                if(in_array($subkey,['CAN','INT'])){
                    continue;
                }
                $pages .= "\t<url>\r\n\t\t<loc>".$domain_url."/state/".$subvalue."-franchises</loc>\r\n\t".priorities(1,'daily')."</url>\r\n";
            }
        }
        if($key == 'alphabetical-company-search'){
            $pages .= "\t<url>\r\n\t\t<loc>".$domain_url."/alphabetical-company-search/non-alpha</loc>\r\n\t".priorities(1,'daily')."</url>\r\n";
            foreach (range('a', 'z') as $subvalue) {
                $pages .= "\t<url>\r\n\t\t<loc>".$domain_url."/alphabetical-company-search/".$subvalue."</loc>\r\n\t".priorities(1,'daily')."</url>\r\n";
            }
        }
    }
    $pages .= "\t<url>\r\n\t\t<loc>".$domain_url."/resources/net-worth-calculator</loc>\r\n\t".priorities(1,'daily')."</url>\r\n";
} else{ //DEFAULT
    $default_pages = ['/sitemap-main.xml','/sitemap-categories.xml', '/sitemap-concepts.xml'];
    foreach ($default_pages as $key => $value) {
        $pages .= "\t<url>\r\n\t\t<loc>".$domain_url.$value."</loc>\r\n\t".priorities(null,null)."</url>\r\n";
    }
}
// Send the headers
header("Content-type: text/xml");
echo "<?xml version='1.0' encoding='UTF-8'?>\r\n";
?>
<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php echo $pages; ?>
</urlset>