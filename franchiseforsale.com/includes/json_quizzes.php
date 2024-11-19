<?php
    header('Content-Type: application/json; charset=utf-8');
    $response = '{"status":"error","message":"Response Failed to Store"}';
    if(isset($_REQUEST)){
        $api_url = 'https://franchiseinsights.franchiseportals.com';
        $subdomain_check = explode('.',$_SERVER['HTTP_HOST']);
        if (!in_array($subdomain_check[0],['www','www2'])){
            $sub = 'www';
            if(!is_numeric($subdomain_check[0])){
                $sub = $subdomain_check[0];
            } 
            $api_url = 'https://'.$sub.'.franchiseinsights.franchiseportals.com';  
        }
        $url = $api_url.'/api/quiz_results';
        $postData = file_get_contents('php://input');
        $header = array('Content-Type: application/json');
        $ch = curl_init();  
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_HEADER, false); 
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);   
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    
        $response = curl_exec($ch);
        curl_close($ch);
    }
    echo $response;
    die;
?>