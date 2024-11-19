<?php
    chdir('../'); //we change directory to root so all include files can still function (currently they all have relative include paths). -MLK, 4/5/2022

    include_once('includes/global.php');
    $quiz = get_json('/api/quiz/url/'.$_GET['url']);
    $quiz_data = json_decode($quiz,true);
    $quiz_id = $quiz_data['quiz'][0]['id'];
    $url = 'https://admin.franchiseventures.com/quiz-preview/';
    $subdomain_check = explode('.',$_SERVER['HTTP_HOST']);
    if ($subdomain_check[0] != 'www'){
        $url = 'https://'.$subdomain_check[0].'.admin.franchiseventures.com/quiz-preview/'; 
    }
    $url .= $quiz_id.'?site_id='.$website_id;
    if(isset($_GET['modal_include'])){
        $url .= '&modal_include='.$_GET['modal_include'];
    }
    if(isset($_GET['preview'])){
        $url .= '&preview='.$_GET['preview'];
    }
    if(isset($geo['state'])){
        $url .= '&state='.$geo['state'];
    }
    $return = file_get_contents($url) or die("Error: Cannot create object");
    echo $return;
    die;
?>