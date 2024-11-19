<?php

class visitor_cookie {

    public function __construct($api_url = "")
    {
        $this->api_url = $api_url;
    }

    public function get_ip() {
        //IP Address
        if(strlen(@$_SERVER['HTTP_CF_CONNECTING_IP'])){
            $ipAddress = $_SERVER['HTTP_CF_CONNECTING_IP'];
        }else if(strlen(@$_SERVER['HTTP_X_FORWARDED_FOR'])){
            $ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $ipAddress = $_SERVER['REMOTE_ADDR'];
        }
        return $ipAddress;
    }

    public function detect_bots($ipAddress = "") {
        $is_bot = false;
        $post_url = $this->api_url.'/api/visitor/detectbots';
        $parameters = [
            'ip' => $ipAddress,
            'user_agent' => $_SERVER['HTTP_USER_AGENT']
        ];
        //$parameters['ip'] = "157.55.39.65";
        //$parameters['user_agent'] = "Mozilla/5.0 (compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm)";
        $data = $this->httpPost($post_url,$parameters);
        if($data['status'] == 'error') {
        
        } else if($data['status'] == 'success' //successful call
            && isset($data['data']) && !empty($data['data'])) { //a bot was detected
            $is_bot = true;
        }
        return $is_bot;    
    }

    //determine if cookie is in the FV DB for this site_id. if not, then we need to signal that the cookie has to be reset and re-recorded.
    public function find_visitor_id($cookie_string, $site_id, $should_store_existing_entry = false) {
        $visitor_id = 0;
        $post_url = $this->api_url.'/api/visitor/cookie/find';
        $parameters = [
            'site_id' => $site_id,
            'cookie_string' => $cookie_string
        ];
        $data = $this->httpPost($post_url,$parameters, "GET");
        if($data['status'] == 'error') { //bad call? then no visitor_id can be returned.
            //do nothing.
        } else if($data['status'] == 'success' && isset($data['data'])) { //successful call
                if(@$data['data']['id']) {
                    $visitor_id = $data['data']['id']; //found a visitor id! let's send it back.
                } else {
                    //do nothing. no matching cookie was found so we need to set the cookie again.
                }
        }

        if(!$visitor_id && $should_store_existing_entry) {
            $post_url = $this->api_url.'/api/visitor/cookie/store';
            $parameters = [
                'site_id' => $site_id,
                'cookie_string' => $cookie_string
            ];
            $data = $this->httpPost($post_url, $parameters); //store the cookie in the DB.
            if($data['status'] == 'success' && isset($data['data'])) {
                $visitor_id = $data['data']['id'];
            }
        }         
        return $visitor_id;
    }

    public function set_visitor_cookie($cookie_info, $site_id) {
        //echo "setting cookie!";
        
        //Saves user's first PHP session value + date.
        $cookie_string = base64_encode('visitor_id='.session_id() . "|setdate=".date("Y-m-d H:i:s")); //store date to make more unique cookie value
        setcookie('fvvisitor', $cookie_string, strtotime("+1 year"), "/", $cookie_info['domain'], $cookie_info['isSecure'], true); //Expires in 1 year
        $_COOKIE['fvvisitor'] = $cookie_string; //set this explicitly for immediate use (setcookie only sets for subsequent page loads)
        //echo  " | " .$_COOKIE['fvvisitor'] . " | " . base64_decode($_COOKIE['fvvisitor']);

        $post_url = $this->api_url.'/api/visitor/cookie/store';
        $parameters = [
            'site_id' => $site_id,
            'cookie_string' => $cookie_string
        ];
        $data = $this->httpPost($post_url, $parameters); //store the cookie in the DB.
        if($data['status'] == "success" && isset($data['data'])) {
            //print_r($data);
            return @$data['id'];
        }
    }

    public function record_site_visit($visitor_id, $site_id, $session_id, $ip) {

        $post_url = $this->api_url.'/api/visitor/session/record';
        $parameters = [
            'site_id' => $site_id,
            'visitor_id' => $visitor_id,
            'session_id' => $session_id,
            'ip' => $ip,
            'user_agent' => $_SERVER['HTTP_USER_AGENT']
        ];
        //print_r($parameters);
        $data = $this->httpPost($post_url, $parameters); //store the cookie in the DB.
        if($data['status'] == "success" && isset($data['data'])) {
            return $data['id'];
        }

    }

    public function is_recent_visitor($cookie_string, $site_id) {
        $post_url = $this->api_url.'/api/visitor/recentvisits';
        $parameters = [
            'site_id' => $site_id,
            'cookie_string' => $cookie_string
        ];
        $data = $this->httpPost($post_url,$parameters);
        if($data['status'] == "success" && isset($data['data'])) {
            return $data['visit_count'];
        }
    }

    public function record_franlinks_visit($data) {
        $post_url = $this->api_url.'/api/visitor/franlinks/visit/record';
        $parameters = $data;
        $data = $this->httpPost($post_url,$parameters);
        //print_r($data);
        if($data['status'] == "success" && isset($data['data'])) {
            return true;
        }

        return false;
    }

    //JSON POST CURL FUNCTION
    public function httpPost($url,$params = [],$request = 'POST',$content_type = 'default')
    {
        $array = [];
        if(is_array($params)){
            $array = $params;   
        }
        $postData = $content_type == 'default' ? json_encode($array) : $array;
        $header = $content_type == 'default' ? array('Content-Type: application/json') :array('Content-Type: multipart/form-data');
        $ch = curl_init();  
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $request);
        if($request == 'POST'){
            curl_setopt($ch, CURLOPT_HEADER, false); 
            curl_setopt($ch, CURLOPT_POST, count($array));
        }
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);   
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    
        $output=curl_exec($ch);
    
        curl_close($ch);
        return json_decode($output,true);
    
    }
}