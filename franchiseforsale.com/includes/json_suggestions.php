<?php
$returned = ['status'=>'failure'];
$path = $_SERVER['DOCUMENT_ROOT'];
$path .= '/includes/global.php';
include_once($path);

if (isset($_GET['state_code'])) {
    global $requested;
    $returned = ['status'=>'success','data'=>[]];
    $requested = @$_GET['requestlist'];
    $query = [
        'min' => 0,
        'max' => @$_GET['max'],
        'per_page' => 5,
        'filter' => 'suggestions'
    ];
    if(isset($_GET['per_page'])){
        $query['per_page'] = $_GET['per_page'];
    }
    $array = return_results_array($website_id,is_paid(),'&'.http_build_query($query));
    foreach($array['data']['data'] as $key => $value){
        $returned['data'][] = [
            'fbo_id' => $value['fbo_id'],
            'concepts_id' => $value['concepts_id'],
            'site_id' => $value['site_id'],
            'image_url' => $value['image_url'],
            'name' => addslashes($value['name']),
            'rate' => $value['rate'],
            'ShortDesc' => addslashes(strip_tags($value['ShortDesc'])),
            'investment' => $value['investment']
        ];
    };

}else {
    $returned = ['status'=>'failure'];
}
header('Content-Type: application/json');
echo json_encode($returned);
?>