<?php
//DEFAULT VARIABLES AND ARRAYS
$temp_utm_source = strtolower($utm_source);
$temp_utm_medium = strtolower($utm_medium);
$temp_page_path = strtok($_SERVER['REQUEST_URI'], '?');
if(isset($_GET['override_page_path'])){
    $temp_page_path = htmlspecialchars($_GET['override_page_path']);
}
$tracking = [
    'bing' => '4030231',
    'meta' => '885342675643204',
    'aw' => 'AW-1071346778',
    'aw_label' => '4-9ZCN7KyAIQ2ujt_gM'
];
$bots = ['bot','crawl','spider','lighthouse'];

//THESE ALWAYS LOAD...EVEN FOR BOTS
$base_load_scripts = [
    "gtag('js', new Date())",
    "gtag('config', '".$tracking['aw']."')"
];
$base_load_functions = [
    'https://www.googletagmanager.com/gtag/js?id='.$tracking['aw']=>'1'
];

//THESE LOAD ONLY FOR NON-BOTS
$load_scripts = [
    "gconversion()",
    "gtag('event', 'page_view', { 'page_path': '".$temp_page_path ."?page=pv_".return_page_id($temp_page_path)."', 'send_to': '".$tracking['aw']."' })",
    "ActOn.Beacon.track()"
];
$load_functions = [
    'https://business.franchiseforsale.com/cdnr/forpci2/acton/bn/tracker/43546'=>'1'
];

//FUNCTION TO LOAD SCRIPTS
function load_tracking_scripts($return,$bot = false){
    global $load_functions,$base_load_functions;
    $array = array_merge($base_load_functions,$load_functions);
    if($bot){
        $array = $base_load_functions;
    }
    foreach($array as $key => $value){
        $return .= '{file: "'.$key.'",async:!'.$value.'},';
    }
    return rtrim($return, ",");
}

//FUNCTION TO LOAD SCRIPTS
function fb_json(){
    global $email, $phone, $zipcode, $country, $geo, $response, $temp_page_path;
    $return = '';
    $temp = '';
    $checks = ['email'=>'em','phone'=>'ph','zipcode'=>'zp'];
    foreach($checks as $key => $value){
        if(isset($$key)){
            $temp = hash("sha256", $$key);
            $return .= '"'.$value.'": "'.$temp.'",';
        }
    }
    $return .= '"st": "'.hash("sha256", geo_formatter($geo['state'],'state')).'",';
    $return .= '"country": "'.hash("sha256", geo_formatter($geo['country'],'country')).'",';
    if(isset($_COOKIE['_fbp'])){
        $return .= '"fbp": "'.$_COOKIE['_fbp'].'",';
    }
    if($return != ''){
        $temp = ', { '.rtrim($return, ",").' });';
    }
    if(!is_array(@$response['franchises'])){
        $temp .= ' fbq("track", "PageView", { eventID: "pv_'.return_page_id($temp_page_path).'" });';
    }
    return $temp;
}
    //DETECTING BOTS OR NOT
    if(isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/bot|crawl|slurp|spider|mediapartners|lighthouse/i', $_SERVER['HTTP_USER_AGENT'])){
        //CURRENTLY A BOT DO NOTHING;
    }else{ ?>
        <?php /*---------------GOOGLE AND ACT-ON---------------*/ ?>
        <script>function gtag(){dataLayer.push(arguments)}window.dataLayer=window.dataLayer||[];let fileList=[],getready=!1;try{fileList=[<?php echo load_tracking_scripts($return); ?>]}catch(a){fileList=[]}function loadScripts(){if(fileList.index>=fileList.length)return;let i,e=fileList[fileList.index].file;(i=document.createElement("script")).src=e,i.async=!1,i.loadAsync=fileList[fileList.index].async,i.onload=function(){e=null!=this.src?this.src:this.href,this.loadAsync||(fileList.index++,setTimeout(function(){loadScripts()},10))},fileList[fileList.index].async&&(fileList.index++,setTimeout(function(){loadScripts()},10)),document.head.appendChild(i)}function setReadyListener(){const i=()=>{if("undefined"==typeof ActOn)return setTimeout(i,100);<?php echo implode(',',$base_load_scripts).','.implode(',',$load_scripts); ?>};i()}fileList.index=0,setTimeout(function(){loadScripts(),setReadyListener()},1500);</script>
        <?php /*---------------BING---------------*/ ?><script>(function(w,d,t,r,u){var f,n,i;w[u]=w[u]||[],f=function(){var o={ti:"<?php echo $tracking['bing']; ?>"};o.q=w[u],w[u]=new UET(o),w[u].push("pageLoad")},n=d.createElement(t),n.src=r,n.async=1,n.onload=n.onreadystatechange=function(){var s=this.readyState;s&&s!=="loaded"&&s!=="complete"||(f(),n.onload=n.onreadystatechange=null)},i=d.getElementsByTagName(t)[0],i.parentNode.insertBefore(n,i)})(window,document,"script","//bat.bing.com/bat.js","uetq");</script>
        <?php /*---------------FACEBOOK---------------*/ ?>
        <script>!function(f, b, e, v, n, t, s) { if (f.fbq) return; n = f.fbq = function() { n.callMethod ? n.callMethod.apply(n, arguments) : n.queue.push(arguments) }; if (!f._fbq) f._fbq = n; n.push = n; n.loaded = !0; n.version = '2.9.55'; n.queue = []; t = b.createElement(e); t.async = !0; t.src = v; s = b.getElementsByTagName(e)[0]; s.parentNode.insertBefore(t, s) }(window, document, 'script', 'https://connect.facebook.net/en_US/fbevents.js'); fbq("init", '<?php echo $tracking['meta']; ?>'<?php echo fb_json(); ?></script>
        <?php /*---------------FALLBACK---------------*/ ?>
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MGPN999" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<?php }  ?>