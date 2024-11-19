<?php echo show_hide_head('<head>'); //OPENING HEAD TAG ?>

    <?php /* START META/ANYLTICS  */ ?>
        <?php gtm_source();if($not_dev){include_once('tracking_head.php');} ?>
        <meta charset="utf-8">
        <meta name="google" content="notranslate">
        <?php echo viewport(); ?>
    <?php /* END META/ANYLTICS  */ ?>

    <?php if(@$_GET['section'] != 'head'){ //HIDE FROM WORDPRESS INCLUDES ?>
        <?php header_meta(@$landing_page_data['page_title']); ?>
        <meta name="robots" content="<?php echo $global_robots; ?>">
    <?php } ?>

    <?php /* START CSS/JS  */ ?>
        <?php 
        $css_branding = array();
        if(isset($_GET['thankyou'])){
                    array_push($css_branding,'thankyou');
        }
        if(@$_GET['landing']){
            array_push($css_branding,'landing');
        }
        else if(@$_GET['url']){
            array_push($css_branding,'landing_new');
        }
        else{
            array_push($css_branding,'mobile');
        }
        if(@$is_index){
            array_push($css_branding,'index');
        }
        $css = implode(',',$css_branding);
        load_css($css); ?>
        <link  rel="stylesheet" type="text/css" media="only screen and (min-width: 768px)" href="/css/tablet.css?v=2023.04.19.1" >
        <link rel="stylesheet" type="text/css" media="only screen and (min-width: 1024px)" href="/css/desktop.css?v=2023.04.19.1" >
        <?php if(isset($form)){ load_css('form'); } ?> 
        <?php if(@$lead_form){ load_css('slick,lead-form'); } ?> 
        <?php if(isset($tips_and_tools)){ ?>
            <link rel="stylesheet" type="text/css" media="all" href="https://franchise-ventures-general.s3.amazonaws.com/global/tips-and-tools/tips-and-tools.css" >
            <link rel="stylesheet" type="text/css" media="all" href="/css/tips_and_tools.css?random=<?php echo mt_rand(100000, 999999);?>" >
            <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
        <?php }
        if(isset($eucheck)){ ?>
            <link href="/css/us_only.css?random=<?php echo mt_rand(100000, 999999);?>" rel="stylesheet" type="text/css">
        <?php } ?>
        <?php if(isset($splide)){ ?>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@latest/dist/css/splide.min.css">
        <?php } ?>
        <?php
            if ($stylesheet_includes) {
                echo "\t".custom_css_js_checker($stylesheet_includes,'style'). "\n";
            }
            if ($js_includes) {
                echo "\t".custom_css_js_checker($js_includes,'script'). "\n"; 
            }
        ?>
        <?php echo hide_bottom_bar(@$_GET['section']); ?>
    <?php /* END CSS/JS  */ ?>

    <?php /* START BRAND META TAGS  */ ?>
        <link rel="apple-touch-icon" sizes="180x180" href="/images/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/images/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/images/favicon-16x16.png">
    <?php /* END BRAND META TAGS  */ ?>

<?php echo show_hide_head('</head>'); //CLOSING HEAD TAG ?>