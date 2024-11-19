
<?php isset($_GET['landing']) ? include_once('includes/menu_footer_landing.php') : include_once('includes/menu_footer.php'); ?>

<div id="bottom">
    <div class="container">
        <div id="bottom-text" class="btn-text-div text-center <?php echo @$noAddBasketBlock; ?>"><span>Add Franchises To Basket<br>  For Free Information</span></div>
        <button id="cart_bottom" class="hide" onClick="continue_form('<?php echo $prepend_url; ?>');">
            <span class="cart_count"></span>
            <span class="text">Franchise<em></em> In &nbsp;Basket</span>
            <span style="display: block;"><span class="button">Request&nbsp;Free Information</span></span>
        </button>
    </div>
</div>

<div id="formConfirm" class="modal">
    <div class="backdrop" onclick="closeModal('formConfirm');"></div>
    <div class="guts">
        <div class="content">
            <div class="close_button" onclick="closeModal('formConfirm');">&times;</div>
            <p class="h2 reveal-header">&nbsp; Please Confirm</p>
            <div class="confirmMsg">
                <br>
                <p id="msg">Are you sure you wish to REMOVE <strong id="rmConceptName"></strong> from your request basket?</p>
                <br>
                <input type="button" id="btnYes" class="success button" value="Yes">
                <input type="button" class="alert button" onclick="closeModal('formConfirm');" value="No" aria-label="Close modal on no">
            </div>
        </div>
    </div>
</div>
<div id="mini_modal" class="modal mini">
    <div class="backdrop" onclick="closeModal('mini_modal');"></div>
    <div class="guts">
        <div class="content">
            <div class="close_button" onclick="closeModal('mini_modal');">&times;</div>
            <p id="cartFullTitleMax" class="bold h2 dark-blue-text"></p>
            <p id="mini_modal_content" class="pd-15"></p>
            <button class="button" onclick="closeModal('mini_modal');"></button>     
        </div>
    </div>
</div>
<div id="max" class="modal mini">
    <div class="backdrop" onclick="closeModal('max');"></div>
    <div class="guts">
        <div class="content">
            <div class="close_button" onclick="closeModal('max');">&times;</div>
            <p id="cartFullTitleMax" class="bold h2 dark-blue-text">We're Sorry</p>
            <p class="pd-15">We only allow <span id="max_number"></span> businesses per information request. <strong>Please complete your current requests to continue.</strong></p>
            <p class="text-center"><a class="button uppercase" href="javascript:;" onClick="continue_form('<?php echo $prepend_url; ?>');"><strong>Complete Requests »</strong></a></p>
        </div>
    </div>
</div>
<div id="limit" class="modal mini">
    <div class="backdrop" onclick="closeModal('limit');"></div>
    <div class="guts">
        <div class="content">
            <div class="close_button" onclick="closeModal('limit');">&times;</div>
            <p id="cartFullTitleLimit" class="bold h2 dark-blue-text">Oops!</p>
            <p>Wow, glad you're interested in so many businesses! The <strong id="limit_number"></strong> you've inquired to will contact you shortly.</p>  
            <button class="button" onclick="closeModal('limit');">Sounds Good</button>                  
        </div>
    </div>
</div>
<div id="fly_modal" class="modal mini">
    <div class="backdrop" onclick="closeModal('fly_modal');"></div>
    <div class="guts">
        <div class="content">
        <div class="close_button" onclick="toggle_fly_modal('fly_modal','hide',true);">×</div>
        <p id="cartFullTitleMax" class="bold h2 dark-blue-text">Not sure what franchsies to choose?</p>
        <p class="pd-15">Try out our <strong>Franchise Fit Finder Quiz!</strong></p>
        <p class="text-center"><a class="button uppercase" href="javascript:;" onclick="window.location.href='/fv-quiz/find-your-franchise-fit'"><strong>Take the Quiz »</strong></a></p>
        </div>
    </div>
</div>

<div id="waiting"></div>

<div id="loading">
    <div> 
        <i class="fa fa-spinner fa-pulse"></i> 
    </div>
</div>

<!-- Staff Modal -->
<div id="aboutModal" class="modal"></div>

<div id="valid_cookies">
    <div class="flexxer">
        <div>
            <p>This site uses cookies. By continuing to browse the site you are agreeing to our use of cookies. Review our <a href="/privacy-website-usage-policy/">cookies information</a> for more details.</p>
        </div>
        <div><button class="closer" onClick="accept_cookies();">Allow</button></div>
    </div>
</div>
<?php /* <div id="phone_tip" class="tool_tip">We are asking for your phone number so the businesses you have requested more information from can call/text you to chat with you more about their opportunity.  We do not share your phone number with anyone other than the specific businesses you are interested in learning more about.</div> */ ?>
<?php if(isset($eucheck)){ include_once('includes/us_only.php'); } ?>
<?php if(isset($recaptcha)){ ?>
    <script async src="https://www.google.com/recaptcha/api.js"></script>
<?php } ?>
<?php load_js('global'); ?>
<?php if($not_dev){gtm_manager('footer');echo success_franchises($response['franchises'],'FFS',$tracking['aw'],$tracking['aw_label']);} ?> 
<?php if(isset($splide)){ ?>
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@latest/dist/js/splide.min.js"></script>
<?php } ?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css2?family=Raleway:wght@300;400;500;600&family=Source+Sans+Pro:wght@400;600&display=swap" rel="stylesheet">
<?php echo showsynd($site_id).additional_page_info(); ?>