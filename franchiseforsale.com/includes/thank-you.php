<?php
    // echo $request_URL;
    // debug($response);
    $resubmit_thankyou_url = '/thank-you';
    $check_folder = explode('/', ltrim($_SERVER['REQUEST_URI'], '/'));
    if(in_array($check_folder[0],['find-a-franchise','great-franchises-a','great-franchises-b','great-franchises-c','great-franchises-d','great-franchises-e','great-franchises-r','great-franchises-demo'])){
        $resubmit_thankyou_url = '/'.$check_folder[0].'/thank-you';
    }
    $response_results = ['SUCCESS'=>array(),'FAIL'=>array(),'LRQ_PENDING'=>array()];
    $concepts_recheck = [];
    $min_recheck = 9999999;
    if (is_array(@$response['franchises'])) {
        foreach($response['franchises'] as $key => $value){
            if(($value['status'] == 'FAIL' && $value['fail_type'] != 'INVESTMENT') || $value['fbo_id'] == 11072 || ($value['status'] == 'LRQ_PENDING' && $value['lrq_reason'] != 125)){
                continue;
            }
            if($value['status'] == 'FAIL' && $value['investment_level'] <= $min_recheck){
                $min_recheck = $value['investment_level'];
            }
            $temp_response_results = ['name'=>$value['franname'],'investment'=>$value['investment_level'],'fbo_id'=>$value['fbo_id']];
            array_push($response_results[$value['status']],$temp_response_results);
        }
        foreach($response_results['FAIL'] as $key => $value){
            if($value['investment'] >= $min_recheck){
                array_push($concepts_recheck,$value['fbo_id']);
            }
        }
    }else if($response['status'] == 'FAIL'){
        $udid = uniqid(rand(), true);
        setcookie('udid',$udid, time() + (10 * 365 * 24 * 60 * 60), "/", $matches[0] , $isSecure, true); //Expires in 10 years	
    }

    //INVESTMENT RESUBMIT
    function recheck_form_investment(){
        global $investment_array,$min_recheck;
        $return = '<option value="">Select an Investment Level</option>';   
            foreach ($investment_array as $key => $value) {
                if($value < $min_recheck){
                    continue;
                }
                $return .= '<option value="'.$value.'">';
                $return .= '$'.number_format($value);
                $return .= '</option>';
        }
        return $return;    
    }

    //LIST LOOP
    function results_loop($array = [],$status){
        $return = '';
        if(!empty($array)){
            foreach($array as $key => $value){
                if($status == 'SUCCESS'){
                    $return .= '<li><i class="green-text">&#10004;</i><strong>'.$value['name'].'</strong></li>';
                }else if($status == 'FAIL'){
                    $return .= '<li><i class="failed-text">&times;</i><strong>'.$value['name'].'</strong> is not currently accepting inquiries with less than $'.number_format($value['investment']).' in available cash to invest .</li>';
                }else{
                    //DO NOTHING
                }
            }
            return '<ul class="listing_concepts">'.$return.'</ul>';
        }
    }

    //FUNCTION POST LOOP
    function post_loop($full = true){
        $return = '';
        foreach($_POST as $key => $value){
            if(in_array($key,['session_id'])){
                continue;
            }
            if($full != false){
                if(in_array($key,['investment','fbolist'])){
                    continue;
                }
            }
            $temp_post = htmlspecialchars($value);
            $return .= '<input type="hidden" name="'.$key.'" value="'.$temp_post.'">';
        }
        return $return;
    }

    quiz_typ_modal_survey();
?>
<?php if ((!empty(@$response_results['SUCCESS']) || !empty(@$response_results['LRQ_PENDING'])) && empty(@$response_results['FAIL'])) { //SUCCESSFUL LEADS ?>
    <div id="thanks" class="modal mini" style="display: block;">
        <div class="backdrop" onclick="closeModal('thanks');"></div>
        <div class="guts">
            <div class="modal-content"> 
                <div class="homepage-title"><h3 class="text-center">Thank You</h3></div>
                <p><?php echo $thank_you_message; ?></p>
                <button class="button" onClick="closeModal('thanks');"><?php echo $thank_you_button; ?></button>
            </div>
        </div>
    </div>
<?php } ?>
<?php if (!empty(@$response_results['FAIL'])) { //FAILED LEADS WITH INVESTMENT FIXES ?>
    <section id="banner" class="results-banner-all banner-bg-black  ">
        <div class="container flex">
            <div class="introBack banner-intro-bg w-58">
                <h1>Requests with Errors...</h1><div id="sub-text"><span><p class="sub-title"></p><p class="headerParagraph failed-text"><strong>Uh-oh!</strong></p><p class="headerParagraph white-text">You've received an error while requesting information from the franchise(s) below:</p>
            <?php echo results_loop($response_results['FAIL'],'FAIL'); ?></span>
                <div id="bannerDesc" class="paragraphWrapper hide"> 
                    <div><p class="white-text headerParagraph "></p>
                    <a class="white-text readMoreHideSmall hide" onclick="toggleBanner('hide','thank-you');"></a>
                    </div>
                </div>
                <a id="hideSubText" class="white-text readMoreHideSmall hide" onclick="toggleHidden('bannerDesc','hide');">Hide More About Thank You</a>
                </div>
            </div> 
            <div class="resubmit">
                <form method="post" action="<?php echo $resubmit_thankyou_url; ?>" onsubmit="document.getElementById('waiting').classList.add('wait')">
                    <input type="hidden" name="session_id" value="<?php echo $response['session_id']; ?>">
                    <input type="hidden" name="fbolist" value="<?php echo implode(',',$concepts_recheck); ?>">
                    <?php echo post_loop(); ?>
                    <h4>Update your information</h4>
                    <p>Would you like to change your available cash to invest and try again?</p>
                    <div class="ff Dollar"><span></span> 
                        <select name="investment" required><?php echo recheck_form_investment(); ?></select>
                    </div>
                    <button>Try Again</button>
                </form>
            </div>
        </div>
    </section>
<?php }else if($response['status'] == 'FAIL'){ ?>
    <div id="thanks" class="modal mini warning" style="display: block;">
        <div class="backdrop" onclick="closeModal('thanks');"></div>
        <div class="guts">
            <div class="modal-content"> 
                <div class="homepage-title"><h3 class="text-center">Oops!</h3></div>
                <p>Sorry, there was a problem submitting your request. Try Again?</p>
                <form method="post" action="<?php echo $resubmit_thankyou_url; ?>" onsubmit="document.getElementById('waiting').classList.add('wait')">
                    <?php echo post_loop(false); ?>
                    <input type="hidden" name="session_id" value="<?php echo $response['session_id']; ?>">
                    <button>Okay</button>
                </form>
            </div>
        </div>
    </div>
<?php } ?>