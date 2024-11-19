<?php 
    include_once('includes/global.php');

    //Meta Content
    $meta_title_override = 'Request Free Information | Franchises For Sale';
    $description_override = 'Request Free Information for franchises and business opportunities.';
    $keywords_override = 'Request Free Information,Franchises,Businesses';
    $global_robots = 'noindex, nofollow';
    $breadcrumbs['2'] = ['title'=>'Request Free Information','url'=>null];
    $uRL = '/';
    $landingUrl = '';
    $add_page_type = 'lead_form';
    if (@$_GET['landing']) {
        $uRL = '';
        $landingUrl = '/'.$prepend_url;
    }

    //Lead Form Variables
    $lead_form = $viewport = true;
    $lf_button_text = 'Request Free Information';
    $lf_company = 'Franchises For Sale';
    $lf_logo = '/images/logo.png';
    $lf_privacy_url = '/privacy-website-usage-policy';

    //Hide Steps
    function hide_steps($step,$target = 'step'){
        $current = 0;
        $class = 'lf_body';
        $hide = [null,'not_step','not_step'];
        $email_check = isset($_COOKIE['email']) ? $_COOKIE['email'] : '';
        $load_cart = isset($_COOKIE['cart_count']) ? $_COOKIE['cart_count'] : 0;
        if (($email_check && $load_cart > 0) || @$_GET['ac']) {
            $hide[0] = 'not_step';
            $hide[2] = null;
            $current = 2;
        }else if($load_cart == 0 ) {
            $hide[0] = null;
            $class = null;
        }else{
            $hide[0] = 'not_step';
            $hide[1] = null;
            $current = 1;
        }
        if($target == 'class'){
            return $class;
        }else if($target == 'current'){
            return $current;
        }else{
            return $hide[$step];
        }
    }

    //Lead Form Steps
    function lf_steps($total){
        $step = hide_steps(1,'current');
        $spans = '';
        $percent = [1=>0,2=>50];
        for ($i = 1; $i <= $total; $i++){
            $class = null;
            if($i == $step){
                $class = 'lf-good';
            }
            $spans .= '<span data-step="'.$i.'" class="'.$class.'"></span>';
        }
        return '<div class="lf-steps" style="--lf-progress-bar:'.$percent[$step].'%"><div class="lf-flex">'.$spans.'</div></div>';
    }

    //Guidant Check
    function new_checkbox_tool(){
        global $website_id;
        $return = '';
        $checkbox_data = get_json('/api/get_data_checkbox?site_id='.$website_id);
        $checkbox_data = json_decode($checkbox_data, TRUE);
        if($checkbox_data['status'] == 'success'){
            $data = $checkbox_data['data'][0];
            if(!empty($data)){
                $return = '<div id="guidant_div" class="lf-item lf-14">
                                <label class="lf-checkbox">'.$data['desc_text'].'
                                    <input type="checkbox" id="guidant" name="guidant" value="'.$data['fbo_id'].'">
                                    <span class="lf-checkmark"></span>
                                </label>
                            </div>';
            }
        }
        return $return;
    }

    //Return Hidden Fields
    function hidden_form_fields(){
        global $ipAddress,$udid,$homeState;
        $return = '';

        //Where name = variable name
        $normal = ['newsletter','utm_medium','utm_source','utm_campaign','utm_type','gclid_mlclkid','abandon_cart'];
        foreach($normal as $name){
            global $$name;
            $return .= '<input type="hidden" id="'.$name.'" name="'.$name.'" value="'.$$name.'">';
        }

        //Where value is empty
        $empty = ['submission_url','lowest','device_type','fbolist'];
        foreach($empty as $name){
            $return .= '<input type="hidden" id="'.$name.'" name="'.$name.'">';
        }

        //Where everything else
        $others = ['highest'=>0,'request'=>'invalid','ip_address'=>$ipAddress,'session_id'=>$udid,'preferred_state'=>$homeState,'form_url'=>$_SERVER['REQUEST_URI']];
        foreach($others as $name => $value){
            $return .= '<input type="hidden" id="'.$name.'" name="'.$name.'" value="'.$value.'">';
        }
        return $return;
    }
    
    //Phone Function
    function lf_phone($value = null){
        if(!is_null($value)){
            $phone = preg_replace('/[^0-9]/', '', $value );
            $phone = ltrim($phone, '0'); 
            $phone = ltrim($phone, '1'); 
            if(strlen($phone) == 10){
                echo '('.substr($phone, 0, 3).') '.substr($phone, 3, 3).'-'.substr($phone,6);
            }
        }
    }

    //Investment Form Select
    function lf_investment(){
        $investment_array = [10000,20000,30000,40000,50000,60000,70000,80000,90000,100000,150000,200000,250000,300000,350000,400000,450000,500000,500001];
        $return = '<option disabled value="">Select an Investment Level</option>';   
        foreach ($investment_array as $key => $value) {
            $temp = ($value == 500001) ? 'Over $500,000' :'$'.number_format($value);
            $return .= '<option value="'.$value.'">'.$temp.'</option>';
        }
        return $return;    
    }

    //Canadian Check
    function lf_canada(){
        global $geo;
        $return = false;
        $values = [strtolower(@$_COOKIE['country']),strtolower(@$_COOKIE['state_name']),@$geo['country']];
        $matches = ['canada','can','ca'];
        $intersect = array_intersect($values, $matches);
        if(!empty($intersect)){
            $return = true;
        }
        return $return;
    }

    //Country Form Select
    function lf_country(){
        global $filterstate,$homeState;
        $countries = ['NULL'=>'Country','USA'=>'United States','CAN'=>'Canada'];
        $return = '';   
            foreach ($countries as $key => $value) {
                $selected = '';
                if($key == 'CAN' && $homeState == 'CAN' || in_array($key,$filterstate)){
                    $selected = ' selected';
                }
                $return .= '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
        }
        echo $return;    
    }
?> 
<!doctype html>
<html lang="en-US" translate="no">
<?php include_once('includes/head.php'); ?>
    <body class="<?php echo hide_steps(0,'class'); ?>" onresize="resized_form();">
        <?php include_once('includes/header.php'); ?>
        <?php /* Begin Global Lead Form */ ?>
        <main id="lead-form">
            <div id="form_0" class="container <?php echo hide_steps(0); ?>">
                <?php /* No items in cart */ ?>
                <div class="catContainer pd-1">
                    <br>
                    <div class="catTitle"> 
                        <div class="h3 white-text">Empty Cart</div>
                    </div>
                    <div class="white-bg gray-border pd-15">
                        <br>
                        <p class="h3">Please add items to your cart before requesting information.</p>
                        <p><a class="bold" href="<?php echo $landingUrl.$uRL; ?>">Get started here.</a> Search our great selection franchises / business opportunities and add them to your basket for free information. Happy searching!</p>
                    </div>
                </div>
            </div>
            <div class="lf-container">
                <header class="lf-header">
                    <div class="div1"><img src="<?php echo $lf_logo; ?>" alt="<?php echo $lf_company; ?>"></div>
                    <div class="div2"><?php echo lf_steps(2); ?></div>
                </header>
            </div>
            <form id="form_1" class="<?php echo hide_steps(1); ?> lf-container" onsubmit="event.preventDefault(); step1('<?php echo $prepend_url; ?>');">
                <?php /* Step 1: Newsletter SignUp */ ?>
                <section class="lf-flex lf-flex-center">
                    <h2 class="lf-h2 lf-extra-top">Request Free Information</h2> 
                    <div class="lf-item lf-email">
                        <label for="pre_email">Email Address</label> 
                        <i class="svg email"></i> 
                        <input type="email" id="pre_email" name="pre_email" maxlength="64" autofocus required value="<?php is_safe(@$email); ?>">
                        <input type="hidden" id="pre_form_type" name="pre_form_type" value="step_1">
                    </div>
                    <div class="lf-item lf-flex lf-desktop-flex-center lf-14">
                        <label class="lf-checkbox">Please email me about other franchises I should know about
                            <input type="checkbox" id="pre_newsletter" name="pre_newsletter" checked>
                            <span class="lf-checkmark"></span>
                        </label>
                    </div>
                    <div class="lf-item lf-center lf-extra-top">
                        <button type="submit" class="lf-button">Proceed To Step 2</button>
                    </div>
                    <div class="lf-item lf-center lf-14">
                        <p>We respect your trust in us. We will only share your information with the specific businesses you have&nbsp;requested.</p>
                    </div>
                </section>
            </form>
            <form id="form_2" class="<?php echo hide_steps(2); ?> lf-container" method="post" data-action="/<?php echo $prepend_url; ?>thank-you">
                <?php /* Step 2: Lead Submission */ ?>
                <?php echo hidden_form_fields(); ?>
                <input name="work_phone" type="text" value="" style="display:none !important" tabindex="-1" autocomplete="work-phone"/>
                <section class="lf-grid lf-extra-top">
                    <div class="lf-left">
                        <div id="suggested_container" class="hide">
                            <h3 class="lf-h3 border-gradient">You may also like</h3>
                            <div id="suggested">
                                <div id="suggested_wrapper"><ul id="suggestions"></ul></div>
                            </div>
                        </div>
                        <div id="selected_container">
                            <div id="selected_toggle" onclick="toggle_mobile();">
                                <h3 class="lf-h3">Your Selections</h3>
                                <i class="svg chevron"></i>
                            </div>
                            <div class="selected_wrapper">
                                <span id="selected_count"></span>
                                <ul id="selected_basket"></ul>
                            </div>
                        </div>

                    </div>
                    <div class="lf-right">
                        <h2 class="lf-h2">Request Free Information</h2> 
                        <div class="lf-item">
                            <label for="fullname">Full Name</label> 
                            <i class="svg person"></i> 
                            <input type="text" id="fullname" name="fullname" required value="<?php is_safe(@$fullname); ?>" autofocus>
                        </div>
                        <div class="lf-item">
                            <label for="email">Email Address</label> 
                            <i class="svg email"></i> 
                            <input type="email" id="email" name="email" maxlength="64" required value="<?php is_safe(@$email); ?>">
                        </div>
                        <div class="lf-item">
                            <label for="phone">Phone Number</label> 
                            <i class="svg phone"></i> 
                            <input type="tel" id="phone" name="phone" value="<?php lf_phone(@$phone); ?>" maxlength="14">
                        </div>
                        <?php if(lf_canada()){ ?>
                            <div id="city_div" class="lf-item">
                                <label for="city">City / Providence</label> 
                                <i class="svg city"></i> 
                                <input type="city" id="city" name="city" maxlength="50" required value="<?php is_safe(@$city); ?>">
                            </div>
                        <?php } ?>
                        <div class="lf-item">
                            <label for="zipcode">ZIP / Postal Code</label> 
                            <i class="svg zipcode"></i> 
                            <input name="zipcode" id="zipcode" type="text" maxlength="7" required value="<?php is_safe(@$zipcode); ?>" oninput="this.value = this.value.replace(/[^0-9a-zA-Z ]/g, '');">
                        </div>
                        <?php if(lf_canada()){ ?>
                            <div id="country_div" class="lf-item">
                                <label for="location">Country</label> 
                                <i class="svg location"></i> 
                                <select id="country" name="country" required>
                                    <?php lf_country(); ?>
                                </select>  
                            </div>
                        <?php } ?>
                        <div id="investment_div" class="lf-item">
                            <div class="lf-investment">
                                <label for="investment">Available Cash To Invest</label> 
                                <i class="svg investment"></i> 
                                <select id="investment" name="investment" required>
                                    <?php echo lf_investment(); ?>
                                </select>
                            </div>
                            <label class="lf-checkbox lf-14">I prefer not to provide my Available Cash to Invest, but I do have access to the Minimum Cash Required for the franchises I am inquiring about.
                                <input type="checkbox" id="investment_toggle" name="investment_toggle" onchange="toggleInvestment();">
                                <span class="lf-checkmark"></span>
                            </label> 
                        </div>
                        <?php echo new_checkbox_tool(); ?>
                        <div id="noconsent" class="lf-item lf-flex lf-desktop-flex-center lf-14">
                            <h3 class="noconsent">Please provide your consent to allow businesses to provide the information you requested</h3>    
                            <label class="lf-checkbox">By pressing "<?php echo $lf_button_text; ?>", you agree that <?php echo $lf_company; ?> and businesses you selected may call/text/email you at the number you provided above, including for marketing purposes related to your inquiry. This contact may be made using automated or pre-recorded/artificial voice technology. Data and message rates may apply. You don't need to consent as a condition of any purchase. You may opt-out of SMS at any time by replying <strong>STOP</strong> to the phone number we texted you from. You also agree to our <a href="<?php echo $lf_privacy_url; ?>" target="_blank">Privacy Policy &amp; Terms of Use</a>.
                                <input type="checkbox" id="privacy-accept" name="privacy-accept" checked onclick="is_consent(this);" required>
                                <span class="lf-checkmark"></span>
                            </label>
                        </div>
                        <div class="lf-item lf-center lf-extra-top">
                            <button type="submit" class="lf-button" onClick="sendRequest(); return false;"><?php echo $lf_button_text; ?></button>
                        </div>
                    </section>
            </form>
            <div id="fails" onclick="fail_modal();"></div>
            <div class="processing"></div>
            <div id="added_to_cart" class="hide"></div>
        </main>
        <div id="summary_modal" class="lf-modal">
            <div class="lf-overlay" onclick="all_modal('summary_modal');"></div>
            <div class="lf-guts">
                <div class="lf-innards">
                    <div class="lf-content"></div>
                    <button class="lf-close" type="button" onclick="all_modal('summary_modal');"><span aria-hidden="true">&times;</span></button>
                </div> 
            </div>
        </div>
        <div id="remove_modal" class="lf-modal">
            <div class="lf-overlay" onclick="all_modal('remove_modal');"></div>
            <div class="lf-guts">
                <div class="lf-innards">
                    <div class="lf-content">
                        <h3 class="lf-h3">Please Confirm</h3>
                        <p id="msg">Are you sure you wish to REMOVE <strong id="rmConceptName"></strong> from your request basket?</p>
                        <div class="remove_buttons">
                            <a href="javascript:;" id="btnYes" class="yes" onclick="remove_concept();">Yes</a>
                            <a href="javascript:;" class="no" onclick="all_modal('remove_modal');">No</a>
                        </div>
                    </div>
                    <button class="lf-close" type="button" onclick="all_modal('remove_modal');"><span aria-hidden="true">&times;</span></button>
                </div> 
            </div>
        </div>
        <div id="final_modal" class="lf-modal">
            <div class="lf-overlay" onclick="all_modal('final_modal');"></div>
            <div class="lf-guts">
                <div class="lf-innards">
                    <div class="lf-content">
                        <h3 class="lf-h3">Oops!</h3>
                        <p id="msg">You have removed all companies from your list. You can undo this or continue searching franchise businesses.</p>
                        <div class="final_buttons">
                            <a href="javascript:;" class="undo" onclick="all_modal('final_modal');all_modal('remove_modal');">Undo</a>
                            <a href="javascript:;" class="search" onclick="keep_searching();">Keep Searching</a>
                        </div>
                    </div>
                    <button class="lf-close" type="button" onclick="all_modal('final_modal');"><span aria-hidden="true">&times;</span></button>
                </div> 
            </div>
        </div>
        <?php include_once('includes/footer.php'); ?>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
        <script>var lfid = <?php echo $website_id; ?>;</script>
        <?php load_js('slick').load_js('lead-form'); ?>
        <?php /* End Global Lead Form */ ?>
    </body>
</html>