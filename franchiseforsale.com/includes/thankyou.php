<?php
if (is_array(@$response['franchises'])) {
    //Names of submitted leads as a comma delimited list - alphanumeric
    $ty_success = 0;
    $thank_you_message = '';
        $products_array = [];
    if($response['status'] == 'success'){
        $ty_success++;
       $events .= ',event11'; 
    }else{
       $events .= ',event12';         
    }
    if($newsletter == 1){
       $events .= ',event9'; 
    }
	foreach ($response['franchises'] as $key => $value){
        $temp_franName = preg_replace('/[^A-Za-z0-9 -]/', '', $value['franname']); 
        $temp_eVar1 = str_replace(' ', '-', $value['franname']);
        $temp_eVar1 = preg_replace('/[^\w-]/', '', $temp_eVar1);
        $temp_eVar1 = strtolower($temp_eVar1);
        if($response['status'] == 'success'){
            $temp_event = '1';
            $temp_rate =  $value['rate']/10;
        }else{
            $temp_event = '2';
            $temp_rate = '';
        }
        $temp_product = $value['fbo_id'].';'.$temp_franName.';;'.$temp_rate.';event10=1|event1'.$temp_event.'=1;eVar6=';
        $temp_product .= $units[$value['site_id']];
        $temp_product .= ';eVar16='.$value['lead_id'].'.'.$value['site_id'];
        array_push($products_array,$temp_product);
        array_push($eVar1_array,$temp_eVar1);
    }
    $eVar1 = implode(',', $eVar1_array);
    $products = implode(',', $products_array);
    $thank_you_message = '<p>You will receive your free information very soon!</p><p>Based on your previous selections, we also recommend that you consider these <em>additional</em> opportunities.</p>';   
    $thank_you_button = 'See My Recommendations'; 
    if($response['status'] == 'lrq_pending'){
        $thank_you_message = '<p>'.$response['message'].'</p>';
        $thank_you_icon = 'warning';
        $thank_you_button = 'Okay, Thanks';
    }
}
?>