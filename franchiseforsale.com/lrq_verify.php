<?php
if (is_numeric($_GET['check'])) {
    $_REQUEST['dev-live'] = explode(".",$_SERVER['SERVER_NAME']);
    if ($_REQUEST['dev-live'][0] == 'dev' || $_REQUEST['dev-live'][1] == 'whitelabeldev' || strpos($_SERVER['SERVER_NAME'], "franchiseportals") !== false){
        $path = 'https://pet.franchiseportals.com/';
    }else{
        $path = 'https://franchiseinsights.franchiseportals.com/';
    }
    $path .= 'api/userconfirmation?confirmation=yes&phase='.$_GET['phase'].'&tracking_id='.$_GET['check'].'&is_email='.$_GET['type'];
    $verify = file_get_contents($path);
    echo $verify;
}
?>