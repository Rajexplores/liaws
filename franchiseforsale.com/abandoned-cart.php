<?php 
include_once('includes/global.php');
//Units
$unit_ids = [
    4 => 'FG',
    5 => 'FON',
    6 => 'FCN',
    7 => 'FCN'
];

//UPDATE VALUES
function update_value($value,$type){
    if($type == 'investment'){
        $value = preg_replace('/\D/', '', $value);
    }
    $return = $value;
    if(empty($value) || $value == '' || is_null($value)){
        if($type == 'name'){
            $return = 'Name Unavailable';
        }else if(in_array($type,['rate','investment'])){
            $return = 0;
        }
    }
    return $return;
}
 //var_dump($_GET);
 if(isset($_GET['q'])){
    $fields['session_id'] = htmlspecialchars($_GET['q']);
    $url = $api_url.'/api/getCart';

    //url-ify the data for the POST
    $fields_string = http_build_query($fields);
   
    //open connection 
    $ch = curl_init();
   
    //set the url, number of POST vars, POST data
    curl_setopt($ch,CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_POST, true);
    curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
   
    //So that curl_exec returns the contents of the cURL; rather than echoing it
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
   
    //execute post
    $result = curl_exec($ch);
    $returned = json_decode($result,true);

    if(empty($returned['data'])){
        header('Location: /');
        exit;
    }else{
        $email_address = $returned['data'][0]['email_address'];
        if(@$_GET['email']){
            $email_address = $_GET['email'];
        }

        //Track entry
        $tracking_url = $api_url.'/api/trackCart';
        $tracking_array = [
            'session_id'=>$fields['session_id'],
            'site_id'=>$returned['data'][0]['site_id'],
            'email_address'=>$email_address
        ];
        //url-ify the data for the POST
        $tracking_string = http_build_query($tracking_array);

        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $tracking_url);
        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $tracking_string);

        //So that curl_exec returns the contents of the cURL; rather than echoing it
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
        $tracking_response = curl_exec($ch);
        $tracked = json_decode($tracking_response,true);

        //Set Cookies
        setcookie('abandon_cart',true, time() + (1 * 24 * 60 * 60), "/", $matches[0] , $isSecure); //Expires in 1 day	
        setcookie('udid',$fields['session_id'], time() + (10 * 365 * 24 * 60 * 60), "/", $matches[0] , $isSecure); //Expires in 10 years	
        setcookie('email',$email_address, time() + (1 * 365 * 24 * 60 * 60), "/", $matches[0] , $isSecure); //Expires in 1 years	

        $script = '';
        $fbo_ids = array();
        foreach($returned['data'] as $key => $value){
            if(!is_null($value['fbo_id'])){
                array_push($fbo_ids,$value['fbo_id']);
            }
        }
        $json = file_get_contents('https://'.$_SERVER['SERVER_NAME'].'/lf-cart.json?site_id='.$site_id.'&fboid_list='.implode(',',$fbo_ids));
        $json_data = json_decode($json, TRUE);
        if($json_data['status'] == 'success' && $json_data['total']){
            foreach($json_data['data'] as $key => $value){
                $script .= '"ID'.$value['fbo_id'].'":{
                                "name":"'.update_value($value['name'],'name').'",
                                "investment":"'.update_value($value['investment'],'investment').'",
                                "id":"'.$unit_ids[$value['site_id']].'",
                                "rate":"'.update_value($value['rate'],'rate').'"
                            },';
            }
        }
        $script = '{'.substr($script,0,-1).'}';
    }

    //close connection
    curl_close($ch);
 }else{
    header('Location: /');
    exit;
 }
?>
<!doctype html>
<html lang="en-US" translate="no">
    <head>
    <title>Redirecting...</title>
    <meta name="robots" content="noindex,follow">
</head> 
    <body>
        <script>
            function fill_cart(){
                window.localStorage.setItem('cart', JSON.stringify(<?php echo $script;?>));
                window.location.replace("/request-information?ac=true");
            };
            fill_cart();
        </script>
    </body>
</html>