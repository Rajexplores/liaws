<?php


    $live_url = 'https://admin.franchiseportals.com/webservices/';
    $neustar_url = $live_url.'validations/neustar.php';

    $neustar_fields = array();
    $neustar_fields['neustar'] = 'yes';
    $neustar_fields['first_name'] = htmlspecialchars($_POST['first_name']);
    $neustar_fields['last_name'] = htmlspecialchars($_POST['last_name']);
    $neustar_fields['phone'] = htmlspecialchars($_POST['phone']);
    $neustar_fields['email'] = htmlspecialchars($_POST['email']);
    $neustar_fields['session_id'] = uniqid(rand(), true);

    $nu_fields_string = http_build_query($neustar_fields);

    //open connection 
    $nu_ch = curl_init();

    //set the url, number of POST vars, POST data
    curl_setopt($nu_ch,CURLOPT_URL, $neustar_url);
    curl_setopt($nu_ch,CURLOPT_POST, true);
    curl_setopt($nu_ch,CURLOPT_POSTFIELDS, $nu_fields_string);

    //So that curl_exec returns the contents of the cURL; rather than echoing it
    curl_setopt($nu_ch,CURLOPT_RETURNTRANSFER, true); 

    //execute post
    $nu_result = curl_exec($nu_ch);
    $nu_returned = json_decode($nu_result,true);

    echo json_encode($nu_returned);

?>