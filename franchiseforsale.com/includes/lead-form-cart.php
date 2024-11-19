<?php
$returned = ['status'=>'failure'];
$geo_ignore = true;
$path = $_SERVER['DOCUMENT_ROOT'];
$path .= '/includes/global.php';
include_once($path);

//Cleans Up Text
function fix_text($text){
    $return = preg_replace("/[^a-zA-Z0-9&.'-_\s ]+/", "", $text);
    $return = str_replace("'","&apos;",$return);
    $return = str_replace("&2013265929;","&eacute;",$return);
    $return = str_replace("&2013266094;","&reg;",$return);
    $return = preg_replace( "/\r|\n/", "", $return );
    $return = htmlspecialchars_decode($return);          
    $return = str_replace("&amp;","&",$return);
    return $return;
}

if($_GET['fboid_list'] && is_numeric($_GET['site_id'])){
    $returned = ['status'=>'success','total'=>0,'data'=>[]];
    $url = $prefix.'.com/'.$_GET['site_id'].'/fv-searchresults.php?paid='.is_paid().'&filter=all&idlist='.$_GET['fboid_list'];
    $json = file_get_contents($url);
    $array = json_decode($json, true);
    $returned['total'] = count($array['data']['data']);
    $temp_fboid_list = explode(',',$_GET['fboid_list']);
    foreach($array['data']['data'] as $key => $value){
        if(!in_array($value['fbo_id'],$temp_fboid_list)){
            continue;
        }
        $returned['data'][] = [
            'fbo_id' => $value['fbo_id'],
            'concepts_id' => $value['concepts_id'],
            'site_id' => $value['site_id'],
            'image_url' => $value['image_url'],
            'rate' => $value['rate'],
            'name' => fix_text($value['name']),
            'investment' => $value['investment']
        ];
    };

}else {
    $returned = ['status'=>'failure','total'=>0];
}
header('Content-Type: application/json');
echo json_encode($returned);
?>