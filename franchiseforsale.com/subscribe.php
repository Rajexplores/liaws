
<?php 
include_once('includes/global.php');

if (isset($_POST['newsletter_email'])) {
    $visitor_email = str_replace(array("\r", "\n", "%0a", "%0d"), '', $_POST['newsletter_email']);
    $visitor_email = filter_var($visitor_email, FILTER_VALIDATE_EMAIL);
}

if($_POST && empty($_POST['name'])){
    $sanitized_a = filter_var($_POST['newsletter_email'], FILTER_SANITIZE_EMAIL);
    if (filter_var($sanitized_a, FILTER_VALIDATE_EMAIL)) {
        $fields['email'] = $visitor_email;
        $fields['session_id'] = htmlspecialchars($_COOKIE['udid'], ENT_QUOTES);
        $fields['landing_id'] = @$_COOKIE['landing_id'];
        $fields['form_type'] = $_POST['form_type'];
        if($_GET['unsubscribed']){
            $fields['unsubscribe'] = 'Y';
        }

        $url = $prefix.'.com/'.$website_id.'/newsletter.php';
        
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
        $subscribed = $returned['message'];
    }
}

$bCls = 'banner-bg mx-height';
// $bWidth = 'w-100';
$bTitle = 'Newsletter Sign-Up';
$blockTitle = 'Subscribe For Free';
$noteHide = '';
$hideBtn = 'hide';
$bdesc = '';

$formUrl = '/'.$prepend_url.'newsletter-sign-up/subscribe/updated';

if($_GET['unsubscribe'] || $_GET['unsubscribed']){ 
    $bTitle = 'Newsletter Opt Out';
    $blockTitle = 'Unsubscribe'; 
    $hide = '';
    $bdesc = $subscribed;
}

if($_GET['unsubscribe']){
    $bdesc = 'If you wish to Unsubscribe from our Newsletter, follow the instructions below.';
    $formUrl ='/'. $prepend_url.'newsletter-sign-up/unsubscribe/updated';
    $bdesc = 'If you wish to Unsubscribe from our Newsletter, follow the instructions below.';
}

$breadcrumbs['2']['title'] = 'Newsletter Subscribe';

$banner = '<section id="banner" class="landing-dir-banner '.$bCls.'">
            <div class="container">
                <div class="introBack banner-intro-bg '.$bWidth.'">
                    <h1 class="white-text">'.$bTitle.'</h1>
                    <p class="paragraphWrapper white-text'.$hide.'">
                        '.$bdesc.'
                    </p>
                </div>
            </div>
        </section>';


$subscribeTitle = '<p class="h4">Stay up to date on the newest franchises hitting the market.</p>';
$subscribeDesc = '<p>Sign up for our free information service with bi-weekly updates on the latest franchise and business opportunities. We\'ll also send you customized emails for opportunities we think you\'d like.</p>';

$meta_title_override = 'Sign-Up To Receive The Newsletter | FranchiseForSale.com';
$description_override = 'Stay up-to-date on the latest franchise opportunities -- sign-up for the free newsletter. The newsletter will keep you informed of the latest franchises available in your area. Sign up now!';
$keywords_override = 'newsletter,sign-up,franchises';

if($_GET['unsubscribe']){
    $subscribeTitle = '<p class="h4">To Unsubscribe, enter your Email Address then click the button below:</p>';
    $subscribeDesc = '';
    $noteHide = 'hide';
    $hideBtn = '';
    $meta_title_override = 'Unsubscribe From THe Newsletter | FranchiseForSale.com';
    $description_override = 'Unsubscribe from FranchiseForSale.com free e-mail newsletter.  The newsletter will keep you informed of the latest franchises available in your area.';
    $keywords_override = 'newsletter,unsubscribe,franchises';

    $breadcrumbs['2']['title'] = 'Newsletter Unsubscribe';
}

$breadcrumbs['2']['url'] = '';
 
?> 
<!doctype html>
<html lang="en-US" translate="no">
<?php include_once('includes/head.php'); ?>
    <body>
        <?php include_once('includes/header.php'); ?>
        <main id="main" class="subscribe-main">
            <?php echo $banner; ?>
            
            <div id="copy" class="subscribe container">
                <div id="pg-directory">
                    <div id="dirStates" class="resources">
                        <div class="states-block gray-border xy-padded">
                            <div class="catTitle"><p class="h3 white-text"><?php echo $blockTitle; ?></p></div>
                            <div class="pd-15 white-bg gray-border">
                            <?php if($_GET['subscribed']){ 
                                ?><script>window.addEventListener('DOMContentLoaded', () => {newsletter_signup();});</script><?php
                                echo '<p class="h3 text-center xy-padded">Thank You For Subscribing To Our Newsletter.</p>';
                            }elseif($_GET['unsubscribed']){ 
                                echo '<p class="h3 text-center xy-padded">The Email Address has been unsubscribed from our Newsletter. You will no longer receive any Newsletter Emails from us at this address.</p>';
                            }else{ ?>
                                <br>
                                <?php echo $subscribeTitle; ?>
                                <?php echo $subscribeDesc; ?>
                                <form id="subscribe-form" data-action="<?php echo $formUrl; ?>" method="post" novalidate="novalidate" onsubmit="event.preventDefault(); subscription();">
                                    <input type="hidden" id="newsletter_phone" name="phone" class="hide">
                                    <input type="hidden" id="newsletter_form_type" name="form_type" class="hide" value="direct_link">
                                    <div class="hide">
                                        <a class="valsum_anchor"></a>
                                        <div class="validation-summary-valid formError"><span>Please Correct The Following Errors:</span><ul><li style="display:none"></li></ul></div>
                                    </div>
                                    <input type="hidden" id="name" name="name" value="">
                                    <div class="email-block large-4">
                                        <label>Enter Your E-Mail Address <span>(Required)</span></label>
                                        <div class="ff Email"><span></span> 
                                            <input type="email" id="newsletter_email" name="newsletter_email" class="form-controls" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" maxlength="100" data-val="true" data-val-email="The Email Address Is Invalid" data-val-required="Email Address Is Required" required>
                                        </div>
                                    </div>
                                    <div class="sub-row <?php echo $noteHide; ?>">
                                        <div class="newsletter-note">
                                            <em>Unsubscribe at any time by <a href="/<?php echo $prepend_url ?>newsletter-sign-up/unsubscribe">clicking here</a>.</em> 
                                            <br>
                                            <p>Note: FranchiseForSale.com is part of the Franchise Opportunities Network. The email(s) that you will receive will come from FranchiseOpportunities.com.</p>
                                            <button class="g-recaptcha btn-orange uppercase" 
                                            data-sitekey="6LfeKDMgAAAAAO4hONnboJPGGoqs5Hk18995nrwk" 
                                            data-callback='onSubmit' 
                                            data-action='submit'>Subscribe Now</button>
                                        </div>
                                    </div>
                                    <div class="sub-row mt-1 <?php echo $hideBtn; ?>">
                                        <button type="button" class="g-recaptcha btn-orange uppercase" 
                                        data-sitekey="6LfeKDMgAAAAAO4hONnboJPGGoqs5Hk18995nrwk" 
                                        data-callback='onSubmit' 
                                        data-action='submit'>Unsubscribe Now</button>
                                    </div>
                                    <input name="__RequestVerificationToken" type="hidden" value="">
                                </form><br>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <?php include_once('includes/footer.php'); ?>
        <script>
            function onSubmit(token) {
                document.getElementById("subscribe-form").submit();
            }
        </script>
    </body>
</html>