<?php
/*--------------------------------------------------*/
/*------------------DO NOT CHANGE-------------------*/
// If you need to make changes to any of these files, please verify with others first.
// These functions are intended to be in sync between the following websites:
// BBN, FF, FCOM, FFS, FG, FO, FS, SBS
// This will help when migrating all websites onto a singular tool.
/*--------------------------------------------------*/



if(@$_GET['showerrors'] == 'yes'){
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

//DEBUG
function debug($var){
	ob_start();
	echo '<pre>';
	print_r($var);
	echo '</pre>';
	ob_end_flush();
}

//Get Visitor's IP Address
if(isset($_SERVER['HTTP_CF_CONNECTING_IP'])){
    $ipAddress = $_SERVER['HTTP_CF_CONNECTING_IP'];
}else if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
    $ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
}else{
    $ipAddress = $_SERVER['REMOTE_ADDR'];
}
$temp_ipAddress_array = explode(',',$ipAddress);
$ipAddress = $temp_ipAddress_array[0];
if(substr_count($ipAddress, ':') == 1){
    $temp_ipAddress_array = explode(':',$ipAddress);
    $ipAddress = $temp_ipAddress_array[0];
}

//REFERRER DATA COLLECTION
function first_time(){
    global $website_id, $ipAddress, $api_url, $intersect, $utm_source, $isSecure, $matches, $landing_id, $udid, $gclid_mlclkid;

    //CHECK REFERRING URL
    if(empty($intersect)){
        $referring_url = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
        $referring_url = strtolower($referring_url);
        $referring_url = filter_var($referring_url, FILTER_SANITIZE_URL);
        $domains = ['google','bing','facebook','instagram','twitter','udimi','duckduckgo','linkedin','yahoo','ask.com','reddit','youtube','tiktok','pinterest'];
        foreach ($domains as $url) {
            if (strpos($referring_url, $url) !== FALSE) {
                if(!isset($utm_source) && !isset($_COOKIE['fv_campaign'])){
                    $temp['utm_source'] =  $url;
                    $utm_source = $url;
                    unset($_COOKIE['fv_campaign']); 
                    setcookie('fv_campaign',json_encode($temp), time() + (1 * 24 * 60 * 60), "/", $matches[0] , $isSecure, true); //Expires in 1 day
                }
                break;
            }
        }
    }

    //SETTING DATA
    $temp_landing = array();
    $temp_landing['session_id'] = $udid;
    $temp_landing['site_id'] = $website_id;
    $temp_landing['query_string'] = $_SERVER['QUERY_STRING'];
    $temp_landing['browser_agent'] = $_SERVER['HTTP_USER_AGENT'];
    $temp_landing['full_url'] = strtok('https://'.$_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"],"?");
    $temp_landing['referer_url'] = $_SERVER["HTTP_REFERER"];
    $temp_landing['ip_address'] = $ipAddress;
    $temp_landing['gclid_mlclkid'] = $gclid_mlclkid;

    $array['data'] = $temp_landing;

    $url = $api_url.'/api/insert_landing_page_info';
    $ch = curl_init();  
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_HEADER, false); 
    curl_setopt($ch, CURLOPT_POST, count($array));
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($array));   
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
 
    $output=curl_exec($ch);
 
    curl_close($ch);
    $response = json_decode($output,TRUE);//decode JSON object
    if(isset($response['data'])){
        $landing_id = $response['data'];
        setcookie('landing_id',$response['data'], time() + (1 * 24 * 60 * 60), "/", $matches[0] , $isSecure, true); //Expires in 1 day
    }
    return json_decode($output,true);
}

//OPTIN MONSTER
function optin_monster(){	
    //This account is not currently active. It is either expired, paused, archived, cancelled or temporarily suspended. (as of 3/10/2023)
    return null;
    echo "<!-- This site is converting visitors into subscribers and customers with OptinMonster - https://optinmonster.com --> <script>(function(d,u,ac){var s=d.createElement('script');s.type='text/javascript';s.src='https://a.omappapi.com/app/js/api.min.js';s.async=true;s.dataset.user=u;s.dataset.account=ac;d.getElementsByTagName('head')[0].appendChild(s);})(document,164494,178508);</script> <!-- / https://optinmonster.com -->";	
} 

//IN PAGE CSS
function load_css($array,$dark = false){
    $css = '';
    $root = $_SERVER['DOCUMENT_ROOT'].'/css/'; //directory where the css lives
    $files = explode(',',$array);
    if(sizeof($files)){
        foreach($files as $file)
        {
            $css.= (is_file($root.$file.'.css') ? file_get_contents($root.$file.'.css') : '');
        }
    }
    $return = str_replace('; ',';',str_replace(' }','}',str_replace('{ ','{',str_replace(array("\r\n","\r","\n","\t",'  ','    ','    '),"",preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!','',$css)))));
    echo '<style>'.$return.'</style>';
}

//IN PAGE JS
function load_js($js){
    global $not_dev,$website_id;
    $js = str_replace('.min','',$js);
    if($not_dev){
        $js .= '.min';
    }
    $url = $_SERVER['DOCUMENT_ROOT'].'/js/'.$js.'.js';
    $return = file_get_contents($url);
    echo '<script>'.$return.'</script>';
}

//DETECT IF MOBILE
function is_mobile(){
    $useragent=$_SERVER['HTTP_USER_AGENT'];
    $return = 'desktop';
    if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))){
        $return = 'mobile';
    }
    return $return;
}

//RETURN ZERO
function return_zero($value){
    $return = $value;
    if($value == '' || is_null($value)){
        $return = 0;
    }
    return $return;
}

//FUNCTION STATE AND COUNTRY FORMATTER
function geo_formatter($value,$type){
    if(is_null($value)){
        //DO NOTHING
    }else{
        if($type == 'state'){
            return strtolower($value);
        }else if($type == 'country'){
            if(in_array($value,['CAN','Canada','ca'])) {
                return 'ca';
            }else if(in_array($value,['203','us','US','USA'])) {
                return 'us';
            }else{
                return strtolower($value);
            }
        }

    }
}

//ANALYTICS RETURN FRANCHISES
function success_franchises($array,$affiliation,$gtag,$label){
    global $temp_post,$not_dev,$website_id,$site_id,$landing_id;
    $transaction_id = $temp_post['session_id'];
    $item_category2 = submission_url($temp_post['submission_url']);
    $item_category3 = submission_url($temp_post['form_url']);
    if(is_null($website_id)){
        $website_id = $site_id;
    }
    if($not_dev){
        fb_conversions_details($website_id);
    }
    $return = '';
    if(is_array($array)){
        if(!empty($array)){
            $items = $G4_items = array();
            $x = 0;
            $sum = 0;
            do {
                $value = $array[$x];
                $temp_status = strtoupper($value['status']);
                $price = 0;
                if(in_array($temp_status,['SUCCESS','LRQ_PENDING'])){
                    $price = $value['rate'];
                }
                $sum = $sum + return_zero($price);
                $items[] .= '{"id":"'.$value['lead_id'].'","name":"'.cleanfix($value['franname'],false).'","sku":"'.$value['fbo_id'].'","category":"'.$category.'","price":'.number_format(return_zero($price), 2, '.', '').',"quantity":1,"variant":"'.strtolower($temp_status).'"}';
                $G4_items[] .= '{item_id: "'.$value['fbo_id'].'",item_name: "'.cleanfix($value['franname'],false).'",affiliation: "'.$value['site_id'].'",item_list_name: "'.$temp_status.'",item_list_id: "'.$value['lead_id'].'",item_category: "'.$landing_id.'",item_category2: "'.$item_category2.'",item_category3: "'.$item_category3.'",price: '.number_format(return_zero($price), 2, '.', '').',quantity: 1}';
                $x++;
            } while ($x < count($array));
            if(!empty($items)){
                $return .= '
                    <script nonce="'.$transaction_id.'">
                        dataLayer.push({ ecommerce: null });  // Clear the previous ecommerce object.
                        dataLayer.push({
                        event: "purchase",
                        ecommerce: {
                            transaction_id: "'.$transaction_id.'",
                            value: '.number_format(return_zero($sum), 2, '.', '').',
                            currency: "USD",
                            items: ['.implode(',',$G4_items).']
                        }
                        });
                        let place2IsReady = false;
                        let gtagReady = false;
                        setReadyListener();

                        setTimeout(() => place2IsReady = true, 1000);

                        function setReadyListener() {
                        const readyListener = () => {
                            if (place2IsReady) {
                                if(gtagReady == false){
                                    gtag("event","purchase",
                                        {
                                            "transaction_id":"'.$transaction_id.'",
                                            "affiliation":"'.$affiliation.'",
                                            "value":'.number_format(return_zero($sum), 2, '.', '').',
                                            "currency":"USD",
                                            "shipping":0,
                                            "tax":0,
                                            "items":['.implode(',',$items).']
                                        }
                                    );
                                    gtagReady = true;
                                }
                            }
                            return setTimeout(readyListener, 250);
                        };
                        readyListener();
                        }
                        if(typeof fbq === "function"){
                            fbq("track", "Purchase", {value: 0, currency: "USD"},{eventID: "sb_'.$transaction_id.'"});
                        }
                    </script>
                    ';
            }
            $return .= '<script nonce="'.$transaction_id.'">
                            window.dataLayer = window.dataLayer || [];
                            window.dataLayer.push({
                                "event" : "fv_lead_conversion",
                                "session_id": "'.$transaction_id.'",
                                "transaction_id": "'.$transaction_id.'",
                                "value": "'.number_format(return_zero($sum), 2, '.', '').'"
                            });
                        </script>';
        }
    }else if($array){
        $return .= '<script nonce="'.$transaction_id.'">
            dataLayer.push({ ecommerce: null });  // Clear the previous ecommerce object.
            dataLayer.push({
            event: "purchase",
            ecommerce: {
                transaction_id: "'.$transaction_id.'",
                value: 0.00,
                currency: "USD",
                items: [
                        {
                            item_id: 99999,
                            item_name: "Missing ID",
                            price: 0.00,
                            quantity: 1
                        }
                    ]
                }
            });
        </script>';
    }
    return $return;
}

//Google Tag Manager
function gtm_manager($target = 'head'){
    global $gtm,$tracking,$response;
    $return = "<script>
                    (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
                    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
                    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
                    })(window,document,'script','dataLayer','".$gtm."');
                </script>";
    
    if($target == 'footer'){
        $return = '<!-- Google Tag Manager (noscript) --><noscript><iframe src="https://www.googletagmanager.com/ns.html?id='.$gtm.'" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript><!-- End Google Tag Manager (noscript) -->';
    }
    echo $return;
}

//Submission URL
function submission_url($url){
    $domain = 'https://'.$_SERVER['SERVER_NAME'];
    $return = $_SERVER['HTTP_REFERER'];
    if(isset($url)){
        $return = str_replace($domain,"",$url);
    }
    $return = strtok($return, '?');
    $return = trim($return);
    if(in_array($return,['','undefined']) || is_null($return)){
        $return = '/';
    }
    return $return;
}

//Replace special characters on contact forms
function replaceSpecialChars($string){
    return preg_replace('/[^ \w]+/', '', strip_tags($string));
}

//Contact Form Section
function contact_form($type = 'contact_us'){
    //INCLUDE GLOBAL VARIABLES
    global $website_id,$ipAddress,$geo,$relative_path,$api_url;
    $unit = [
        '4'=>'Franchise Gator',
        '5'=>'Franchise Opportunities',
        '6'=>'Franchise Solutions',
        '7'=>'Franchise.com',
        '17'=>'BusinessBroker.net',
        '40'=>'Franchisecost.com',
        '47'=>'Small Business StartUP',
        '53'=>'Franchise Ventures',
        '55'=>'Franchise for Sale',
        '57'=>'FoodFranchise.com'
    ];

    //DECLARE DEFAULT VARIABLES
    $return = null;
    $statusCode = false;
    $url = $api_url.'/api/create_inquiry';
    $fields['url'] = $relative_path;
    $fields['inquiry_type'] = $type;
    $fields['site_id'] = $website_id;
    $fields['ip_address'] = $ipAddress;
    $field_checks = ['company','token'=>'recaptcha_token','city','address','country','site_name'=>'site_name'];

    //GEO FIELDS
    foreach($geo as $key => $value){
        if($key == 'continent'){
            continue;
        }
        if($key == 'city'){
            $value = ucwords($value);
        }else{
            $value = strtoupper($value);
        }
        if($key == 'country'){
            $key = 'geo_located_country';
        }
        if($key == 'country3'){
            $fields['country'] = strip_tags($value);
        }else{
            $fields[$key] = strip_tags($value);
        }
    }

    //GET AND CLEANUP PHONE
    if (isset($_REQUEST['visitor_phone'])) {
        $fields['phone'] = preg_replace("/[^0-9]/", "", $_REQUEST['visitor_phone'] );
    }

    //GET AND CLEANUP EMAIL ADDRESS
    if (isset($_REQUEST['visitor_email'])) {
        $fields['email'] = str_replace(array("\r", "\n", "%0a", "%0d"), '', $_REQUEST['visitor_email']);
        $fields['email'] = filter_var($fields['email'], FILTER_SANITIZE_EMAIL);
    }

    //GET AND CLEANUP NAME
    if (isset($_REQUEST['visitor_firstname']) || isset($_REQUEST['visitor_name'])) {
        if(isset($_REQUEST['visitor_name'])){
            $fields['name'] = $_REQUEST['visitor_name'];
        }else{
            $fields['name'] = $_REQUEST['visitor_firstname'];
        }
        $fields['name'] = filter_var($fields['name'], FILTER_SANITIZE_STRING);
        if(isset($_REQUEST['visitor_lastname'])){
            $fields['name'] .= ' '.filter_var($_REQUEST['visitor_lastname'], FILTER_SANITIZE_STRING);
        }
        $fields['name'] = preg_replace('/\d+/u', '', $fields['name']);
        $fields['name'] = replaceSpecialChars($fields['name']);
    }

    //GET AND CLEANUP EMAIL COMMENTS
    if (isset($_REQUEST['visitor_message'])) {
        $fields['message'] = strip_tags($_REQUEST['visitor_message']);
        if (isset($_REQUEST['visitor_reason'])) {
            $fields['message'] = 'Purpose For Email : '.$_REQUEST['visitor_reason'].' | '.$fields['message'];
        }
    }

    //LOOP THROUGH REMAINING VARIABLES
    foreach($field_checks as $key => $value){
        $temp_value = $value;
        if(is_numeric($key)){
            $key = $value;
            $temp_value = 'visitor_'.$value;
        }
        if(isset($_REQUEST[$temp_value])){
            $fields[$key] = strip_tags($_REQUEST[$temp_value]);
        }
    }

    //RETURN COPY TEXT AND SUBMIT INQUIRY
    if (preg_match("/.ru/i", $fields['email'])) { //IF RUSSIAN
        $return = 'Thank you for contacting us. You will get a reply within 24 hours.';
    }else if (filter_var($fields['email'], FILTER_VALIDATE_EMAIL)) {
        //RUN CURL
        $fields_string = http_build_query($fields);

        //open connection
        $ch = curl_init();
            
        //set the url, number of POST vars, POST data
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            
        //execute post
        $response = curl_exec($ch);
        $error = curl_error($ch);

        //close connection
        curl_close($ch);
        
        $status = json_decode($response,true);
        if ($status['status'] == 'success') { //IF SUCCESS
            if($type == 'advertise_with_us'){
                $return = 'Thank you for requesting more information about advertising with '.$unit[$website_id].'. Your information has been sent to a member of our Client Consultant Team and you can expect to be contacted shortly.';
            }else{
                $temp_name = '.';
                if(!is_null($fields['name'])){
                    $temp_name = ', '.$fields['name'].'.';
                }
                $return = 'Thank you for contacting us'.$temp_name.' You will get a reply within 24 hours.';
            }
            $statusCode = true;
        }else if($status['status'] == 'error'){
            if($status['message'] == 'timeout-or-duplicate'){ //IF DUPLICATE
                $return = 'A message with this email address has recently been submitted. Please try again at a later time.';
            }else{ //FALLBACK ERROR MESSAGE
                $return = 'There was a problem sending this message. Please try again later.';
            }
        }else{ //FALLBACK STATUS
            $return = 'There was a problem sending this message. Please try again later.';
        }
    }else{ //IF EMAIL ADDRESS IS BAD
        $return = 'There was a problem with your email address. Please check your details and try again.';
    }
    if ($statusCode) {
        return ['message'=>$return,'status'=>'success'];
    }else{
        return ['message'=>$return,'status'=>'error'];
    }
}

//GET CUSTOM SITE PAGES
function get_sitepages($site_id, $url = "", $page_id = 0, $skip_active = 0){
    global $api_url;
    $request_URL = $api_url . '/api/sitepage/list';
    $fields = array();
    $fields['site_id'] = $site_id;
    if($page_id) {
        $fields['page_id'] = $page_id;
    }
    if($skip_active && $page_id) {
        //don't look for active, but ONLY if $page_id is sent in!
    } else {
        $fields['active'] = 1;
    }
    //$fields['type'] = 144; //only check actual pages (type id 144), not page sections (type_id 145)
    if($url) {
        $fields['url'] = trim(str_replace("/info/", "", strtok($url, '?')), "/ "); //strip out leading /info/ and querystring, and /
    }
    $fields_json = json_encode($fields);
    $header = array('Content-Type: application/json');

    $ch = curl_init();  
    curl_setopt($ch,CURLOPT_URL, $request_URL);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_json);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

    //So that curl_exec returns the contents of the cURL; rather than echoing it
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
    
    $output=curl_exec($ch);
    
    curl_close($ch);
    $output = json_decode($output,true);
    if($output['status'] == "success" && count($output['data']) > 0) {
        return $output['data'];
    }
}

//GET CUSTOM SITE PAGE SECTION
function get_sitepage_section($site_id, $title, $page_id = 0, $skip_active = 0){
    global $api_url;
    $request_URL = $api_url . '/api/sitepage/list';
    $fields = array();
    $fields['site_id'] = $site_id;
    if($page_id) {
        $fields['page_id'] = $page_id;
    }
    if($skip_active && $page_id) {
        //don't look for active, but ONLY if $page_id is sent in!
    } else {
        $fields['active'] = 1;
    }
    $fields['type'] = 145; //only check actual page sections (type_id 145), not site pages (type_id=144)
    $fields['title'] = $title;
    $fields_json = json_encode($fields);
    $header = array('Content-Type: application/json');

    $ch = curl_init();  
    curl_setopt($ch,CURLOPT_URL, $request_URL);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_json);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

    //So that curl_exec returns the contents of the cURL; rather than echoing it
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
    
    $output=curl_exec($ch);
    
    curl_close($ch);
    $output = json_decode($output,true);
    if($output['status'] == "success" && count($output['data']) > 0) {
        return $output['data'][0];
    }
}

//FACEBOOK INTERNAL ENDPOINT
function fb_conversions_details($site_id){
    global $ipAddress,$geo,$api_url,$response;

    $url = $api_url.'/api/fb_conversions_details';

    $fields = [
        "site_id" => $site_id,
        "url" => isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:null,
        "action_source" => "website",
        "client_ip_address" => $ipAddress,
        "client_user_agent" => $_SERVER['HTTP_USER_AGENT'],
        "st" => hash("sha256", geo_formatter($geo['state'],'state')),
        "country" => hash("sha256", geo_formatter($geo['country'],'country')),
        "fbp" => $_COOKIE['_fbp'],
        "track" => ["PageView"=>["eventID"=>"pv_".return_page_id()]]
    ];

    $checks = ['first_name'=>'fn','last_name'=>'ln','email'=>'em','phone'=>'ph','zipcode'=>'zp'];
    foreach($checks as $key => $value){
        if(isset($_COOKIE[$key])){
            $temp = hash("sha256", $_COOKIE[$key]);
            $fields[$value] = $temp;
        }
    }
    if(isset($_COOKIE['fullname'])){
        $temp_array = explode(' ',$_COOKIE['fullname']);
        $fields['fn'] = hash("sha256", $temp_array[0]);
        unset($temp_array[0]);
        $fields['ln'] = hash("sha256", implode(' ',$temp_array));

    }
    if($response['status'] == 'success'){
        $fields['track']['Purchase'] = array(
            ["value"=> 0, "currency"=> "USD"],
            ["eventID"=> "sb_".$response['session_id']]
        );
    }
    $fields_json = json_encode($fields,true);

    //open connection
    $ch = curl_init();

    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_POST, 1);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_json);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,3);
    curl_setopt($ch,CURLOPT_TIMEOUT, 20);
 
    $output=curl_exec($ch);
 
    curl_close($ch);
    // debug(json_decode($output,true));die;
}

//FUNCTION TO GET UNIQUE PAGE NUMBER
function return_page_id($temp_page_path = null){
    if(is_null($temp_page_path)){
        $temp_page_path = strtok($_SERVER['REQUEST_URI'], '?');
    }
    $trimmed = substr($temp_page_path, -12);
    $hash = bin2hex($trimmed);
    $str = preg_replace('/[^0-9.]+/', '', $hash);
    return substr($str, 0, 12);
}

//GET REDIRECTIONS
function redirections($site_id,$single = true){
    global $api_url;
    $request_URL = 'https://franchiseinsights.franchiseportals.com/api/redirect/'.$site_id;
    if($single){
        $old_url = urlencode($_SERVER['REQUEST_URI']);
        $request_URL .= '?old_url='.$old_url;
    }
    $ch = curl_init();  
 
    curl_setopt($ch,CURLOPT_URL,$request_URL);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_HEADER, false);  
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
 
    $output=curl_exec($ch);
 
    curl_close($ch);
    $temp_array = json_decode($output,true);
    $array = $temp_array['data'];
    $return = array();
    if(!is_null($array)){
        $i = 0;  
        do {
            $return[$array[$i]['old_url']] = $array[$i]['new_url'];
          $i++;
        } while ($i < count($array));
    }
    return $return;
}

//Safe Variable Echo
function is_safe($value = null){
    if(!is_null($value)){
        $value = trim($value);
        echo htmlspecialchars($value);
    }
}

//FUNCTION SINGLE/MULTI CONCEPT CART SURVEY
function quiz_typ_modal_survey(){
    global $response, $website_id, $paid;
    $leads = 0;
    if(is_array(@$response['franchises'])){
        $leads = count($response['franchises']);
    }
    $temp_url = '/api/quiz_typ_modal/'.$website_id;
    $array = get_json($temp_url,true);
    $temp = json_decode($array,true);
    foreach($temp['data'] as $key => $value){
        if($website_id == $value['site_id']){
            $data = $value;
            break;
        }
    }
    if ((is_array(@$response['franchises']) && $_POST['email_blast'] != 1) || isset($_GET['showmodal'])) {
        if((isset($_COOKIE['quiz_'.$data['quiz_id']]) && !isset($_GET['showmodal'])) || $data['active'] != 1 || !is_numeric($data['quiz_id']) || !is_numeric($data['master_type']) || ($paid != 1 && $data['paid_only'] == 1)) {
            //Do Nothing
        }else if(
            (($leads == 1 || $leads == 2 && isset($_POST['guidant'])) && $data['master_type'] == 251) || 
            (($leads >= 2 || $leads >= 3 && isset($_POST['guidant'])) && $data['master_type'] == 252) || 
            ($data['master_type'] == 272) || 
            isset($_GET['showmodal'])
        ){
            if($data['percent'] != 100 && !isset($_GET['showmodal'])){
                $random = rand(1,100);
                if($random > $data['percent']){
                    return;
                }
            }
            $return = '';
            $url = 'https://admin.franchiseventures.com/quiz-preview/';
            $subdomain_check = explode('.',$_SERVER['HTTP_HOST']);
            if ($subdomain_check[0] != 'www'){
                $url = 'https://'.$subdomain_check[0].'.admin.franchiseventures.com/quiz-preview/'; 
            }
            $url .= $data['quiz_id'];
            $url .= '?site_id='.$website_id.'&modal_include=yes';
            if($_GET['preview']){
                $url .= '&preview=yes';
            }
            $return = file_get_contents($url);
            if($return){
                $quiz = '<script>document.body.classList.add(\'survey\');</script>
                <div id="survey" class="modal mini" style="display: block;">
                    <div class="backdrop"></div>
                    <div class="quiz_guts">
                        <div id="survey-modal" class="modal-content">'.$return.'</div>
                    </div>
                </div>';
            }
            echo $quiz;
        }
    }
}

//Returns Quiz Messaging
function quiz_message(){
    global $website_id,$filterstate,$master_category_mapping;

    //Setting Default Values
    $qCopy = array();

    if(isset($_GET['qmin']) || isset($_GET['qmax']) || isset($_GET['qstate']) || isset($_GET['qff']) || isset($_GET['qcat_id'])){ 
    
        //Setting "Quiz" Franchise Features
        if(isset($_GET['qff'])){
            //Temp Values
            $temp_text = 'offering ';
            $temp_array = array();
    
            //Getting Quiz Mappings
            $qff_array = get_json('/api/master_type?type_category=quiz_mapping');
            $qff_array = json_decode($qff_array,true);
    
            //Push Display Names into temp array
            $qff = explode(',',$_GET['qff']);
            foreach($qff_array['data'] as $key => $value){
                if(!in_array($value['id'],$qff)){
                    continue;
                }
                array_push($temp_array,'<strong>'.$value['display_name'].'</strong>');
            }
            $temp_text .= strrev(implode(strrev(' &amp; '), explode(strrev(', '), strrev(implode(', ',$temp_array)), 2)));
            array_push($qCopy,$temp_text);
        }
    
        //Setting "Quiz" Investments (requires min & max)
        if(isset($_GET['qmin']) && isset($_GET['qmax'])){
            if(is_numeric($_GET['qmin']) && is_numeric($_GET['qmax'])){
                $temp_text = 'in the $<strong>'.round($_GET['qmin']/1000).'-'.round($_GET['qmax']/1000).'</strong>K investment range';
                array_push($qCopy,$temp_text);
            }
        }
    
        //Setting "Quiz" Categories
        if(isset($_GET['qcat_id'])){
            //Temp Values
            $temp_text = 'in the ';
            $temp_array = array();
            $temp_id = null;
            //Getting Quiz Mappings
            $qff_array = get_json('/api/site_categories/site/'.$website_id.'/all');
            $qff_array = json_decode($qff_array,true);
    
            //Push Display Names into temp array
            $qcat_id = explode(',',$_GET['qcat_id']);
            foreach($qff_array['data'] as $key => $value){
                if(!isset($value['category_id'])){
                    $temp_id = $value['id'];
                }else{
                    $temp_id = $master_category_mapping[$value['category_id']];
                }
                if(!in_array($temp_id,$qcat_id)){
                    continue;
                }
                array_push($temp_array,'<strong>'.$value['category_name'].'</strong>');
            }
            if (count($temp_array)>0) {
                $temp_text .= strrev(implode(strrev(' &amp; '), explode(strrev(', '), strrev(implode(', ',$temp_array)), 2)));
                if(count($qcat_id) == 1){
                    $temp_text .= ' industry';
                }else{
                    $temp_text .= ' industries';
                }
                array_push($qCopy,$temp_text);
            }
        }
    
        //Setting "Quiz" State
        if(isset($_GET['qstate'])){
            //Temp Values
            $temp_text = 'in <strong>'.ucwords(str_replace("-", " ",$filterstate[$_GET['qstate']])).'</strong>';
            array_push($qCopy,$temp_text);
        }
    }
    
    if(!empty($qCopy)){
        return '<div id="para-new"><span>"Great Job! Your personalized matches include businesses '.implode(', ',$qCopy).'."</span></div>'; 
    } 
}

//GTM UTM Sources
function gtm_source(){
    @$fv_campaign = json_decode($_COOKIE['fv_campaign'],true);
    if (is_array($fv_campaign) || @$_REQUEST['utm_source']) {
        $event = @$_REQUEST['utm_source'];
        if($fv_campaign['utm_source']){
            $event = $fv_campaign['utm_source'];
        }
        if(in_array($event,['acton','Act-On Software','act-on','acton*'])){
            $event = 'acton';
        }else if(in_array($event,['bing','msn','yahoo'])){
            $event = 'bing';
        }else if(in_array($event,['facebokk','facebook','fb','ig','instagram'])){
            $event = 'facebook';
        }else if(in_array($event,['google','youtube','youtube.fg'])){
            $event = 'google';
        }
        if(!is_null($event) && $event != ''){
            echo '<script>window.dataLayer = window.dataLayer || [];window.dataLayer.push({"event" : "source_'.$event.'"});</script>';
        }
    }
}

//FAQ JSON
function faq_json($array){
    $items = [];
    foreach($array as $key => $value){
        $loop = ['question','answer'];
        foreach($loop as $qa_key => $qa_value){
            $temp = str_replace('[YEAR]',date('Y'),$value[$qa_value]);
            $temp = strip_tags($temp);
            $temp = str_replace(array("\n\r", "\r\n", "\r", "\n", "\t"), '', $temp);
            $$qa_value = htmlentities($temp, ENT_QUOTES);
        }

        $items[] = '{"@type": "Question","name": "'.$question.'","acceptedAnswer": {"@type": "Answer","text": "'.$answer.'"}}';
    }
    echo '<script type="application/ld+json">{"@context": "https://schema.org","@type": "FAQPage","name": "FAQ Page","mainEntity": ['.implode(',',$items).']}</script>';
}

//Return Brochure URLs
function profile_urls($site_id,$source_id,$concept_id,$url,$nofollow = true){
    $brochure_url = '';
    $clean_url = $url;
    $clean_array = explode('/',$clean_url);
    if(count($clean_array) > 1){
        $clean_url = $clean_array[1];
    }
    $map_site_id = [4=>4,5=>5,6=>6,7=>6,17=>5,55=>5,57=>5];
    if($map_site_id[$site_id] == $source_id){
        if($map_site_id[$site_id] == 4){
            $brochure_url = str_replace("https://www.franchisegator.com/","",$url);
            if(is_null($brochure_url) || $brochure_url == '' || $brochure_url == 'https://www.franchisegator.com'){
                $brochure_url = 'javascript:;';
            }
        }else if($map_site_id[$site_id] == 5){
            $brochure_url = strtolower($url);
            if(isset($_GET['landing'])){
                $brochure_url = '/'.$_GET['landing_url'].strtolower($url);
            }
        }else{
            $url = strtolower($url);
            $url = str_replace("&","and",$url);
            $url = preg_replace("/[^A-Za-z0-9\s-]/", "", $url);
            $url = preg_replace("/[[:space:]]+/", "-", $url);
            $url = preg_replace('/-+/', '-', $url);
            $brochure_url = '/franchise/'.$url;
        }

    }else{
        if(isset($_GET['landing']) && isset($_GET['landing_url']) && $map_site_id[$site_id] == 5){
            $brochure_url .= '/'.$_GET['landing_url'];
        }
        $brochure_url .= '/brochure_franchises/'.$source_id.'/'.$concept_id.'/'.$clean_url;
        if($map_site_id[$site_id] == 4){
            $brochure_url .= '/';
        }
        if($nofollow){
            $brochure_url .= '" rel="nofollow';
        }
    }
    return $brochure_url;
}

//VERIFY SUBMISSION DATA FOR GA4
function verify_submission_data($array = []){
    $array = json_decode($array,TRUE);
    global $fields,$website_id;
    if(empty($array)){
        $temp = [
            'status' => 'SUCCESS',
            'message' => 'Thank you for your request. You should hear from the selected businesses soon!',
            'franchises' => [],
            'session_id' => 'E-'.$fields['session_id']
        ];
        $temp_array = explode(',',$fields['fbolist']);
        foreach($temp_array as $key => $value){
            $temp_item = [];
            $temp_item['franname'] = $temp_item['investment_level'] = $temp_item['rate'] = $temp_item['intended_destination'] = $temp_item['lead_id'] = $temp_item['lrq_reason'] = '';
            $temp_item['fbo_id'] = $value;
            $temp_item['site_id'] = $website_id;
            $temp_item['status'] = 'FAIL';
            $temp_item['message'] = 'Empty submissions response';
            $temp_item['fail_type'] = 'OTHER';
            array_push($temp['franchises'],$temp_item);
        }
        $array = $temp;
    }
    return $array;
}

//SUBMISSION CART CLEAR
function cart_submitted(){
    global $udid,$storage;
    if(@$_GET['thankyou']){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $udid = $_POST['session_id'];
            if(isset($response['session_id'])){
                $udid = $response['session_id'];
            }
            $storage = '<script>
                var form_session_id = document.getElementById("session_id");
                if (window.history.replaceState) {
                    window.history.replaceState( null, null, window.location.href);
                }
                localStorage.removeItem("cart");
                window.onload=function(){
                    if(form_session_id){
                        form_session_id.value = "'.$udid.'";  
                    }
                }
            </script>';
            setcookie('udid',$udid, time() + (10 * 365 * 24 * 60 * 60), "/", $matches[0] , $isSecure, true); //Expires in 10 years	
            unset($_COOKIE['abandon_cart']); 
            setcookie('abandon_cart', null, -1, '/'); 
            setcookie('refresh',true, time() + (24 * 60 * 60), "/", $matches[0] , $isSecure, true); //Expires in 1 fay
        }else if(!@$_COOKIE['refresh']){
            show_404();
        }
    } 
}

//404 Error Returned
function show_404(){
    if(!isset($_GET['showmodal'])){
        global $website_id;
        ob_start();
        header('HTTP/1.1 404 Not Found');
        if(in_array($website_id,[17])){
            include('pages/404.php');
        }else{
            include('404.php');
        }
        ob_end_flush();
        die();
    }
}

//Show/Hide Head for WordPress
function show_hide_head($html = null){
    if(!is_null($html)){
        if(!in_array(@$_GET['section'],['header','head'])){
            return $html;
        }
    }
}

//Is Paid
function is_paid(){
    global $matches,$landing,$isSecure,$website_id,$paid;
    $return = 0;
    if(isset($_GET['paid'],$_GET['landing_url'],$_GET['landing_paid']) || @$_COOKIE['paid'] == 1 || @$_GET['landing'] || $landing || $paid || @$_GET['s'] == 'dnl'){
        $return = 1;
        if (!isset($_COOKIE['paid'])) {
            setcookie('paid',$return, time() + (1 * 24 * 60 * 60), "/", $matches[0] , $isSecure, true); //Expires in 1 day	 
        } 
    }
    return $return;
}

//Creates viewport meta tag
function viewport(){
    global $viewport;
    $min_max = ', minimum-scale=1,maximum-scale=5';
    if($viewport){
        $min_max = ', user-scalable=no';
    }
    return '<meta name="viewport" content="width=device-width, initial-scale=1'.$min_max.'">';
}

// Return Canonical URLs for websites
function canonical_url($override = null){
    $actual_link = 'https://'.$_SERVER['HTTP_HOST'];
    if($override != null){
        $actual_link .= $override;
    }else{
        $actual_link .= $_SERVER['REQUEST_URI'];
        if (substr($actual_link,-strlen('.com/'))=='.com/') {
            $actual_link = rtrim($actual_link, '/');
        }
        $actual_link = strtok($actual_link, '?');
    }
    if ($override == '/franchise-USA') {
        return $actual_link;
    }else{
        return strtolower($actual_link);
    }
}

// Include Site Pages Custom CSS/JS
function custom_css_js_checker($content, $type){
    return (strpos($content, '<'.$type.'>') === false) ? '<'.$type.'>'.$content.'</'.$type.'>' : $content;
}

// Checks whether name is safe or "bottish"
function bot_safe($name){
    $count = strlen(preg_replace('![^A-Z]+!', '', $name));
    if(($count == 1 && ctype_upper($name{0})) || $count == 0){
        $return = 1;
    }else{
        $return = 0;
    }
    return $return;
}

// Cache Serverside CSS
function cache_css($array = []){
    if(isset($array)){
        $return = '';
        $root = $_SERVER['DOCUMENT_ROOT'].'/css/'; //directory where the css lives
        foreach($array as $key => $value){
            $temp = $root.$key.'.css';
            if(is_file($temp)){
                $css = '/css/'.$key.'.css?v='.date ('mdYHis', filemtime($temp)).'.css';
                $return .= ' <link rel="stylesheet" type="text/css" media="'.$value.'" href="'.$css.'">';
            }
        }
    }
    return $return;
}


// Return Banner Information
function concepts_banner($new = 0,$tag = 0){
    $return ='';
    $banners = [
        1 => 'new-franchises',
        135 => 'hot-trendy-tag',
        136 => 'now-trending-tag',
        137 => 'popular-tag',
        138 => 'fast-seller-tag'
    ];
    if(array_key_exists($new,$banners)){
        $return = $banners[$new];
    }else if(array_key_exists($tag,$banners)){
        $return = $banners[$tag];
    }
    return $return;
}

//REFERRER DATA COLLECTION
function track_netacuity($website_id,$app_url,$app_response){
    global $api_url,$udid,$ipAddress;


    //SETTING DATA
    $temp_tracking = array();
    $temp_tracking['session_id'] = $udid;
    $temp_tracking['site_id'] = $website_id;
    $temp_tracking['query_string'] = $_SERVER['QUERY_STRING'];
    $temp_tracking['browser_agent'] = $_SERVER['HTTP_USER_AGENT'];
    $temp_tracking['full_url'] = strtok('https://'.$_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"],"?");
    $temp_tracking['referer_url'] = @$_SERVER["HTTP_REFERER"];
    $temp_tracking['ip_address'] = $ipAddress;
    $temp_tracking['application_url'] = $app_url;
    $temp_tracking['application_response'] = json_encode($app_response);

    $array['data'] = $temp_tracking;

    $url = $api_url.'/api/track_netacuity';
    $ch = curl_init();  
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_HEADER, false); 
    curl_setopt($ch, CURLOPT_POST, count($array));
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($array));   
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
 
    $output=curl_exec($ch);
 
    curl_close($ch);
    return json_decode($output,true);
}

//ADDITIONAL PAGE INFORMATION
function additional_page_info(){
    global $add_page_type;
    $temp = (is_null($add_page_type)) ? 'search' : $add_page_type;
    echo '<script>var add_page_url = "'.strtok($_SERVER['REQUEST_URI'], '?').'",add_page_type = "'.$temp.'";</script>';
}