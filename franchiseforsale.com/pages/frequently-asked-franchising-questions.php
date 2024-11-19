<?php
    $faq_url = $api_url.'/api/faq/55/id/3/';
    $faq_json = file_get_contents($faq_url);
    $faq_temp_array = json_decode($faq_json, true); 
    $faq_array = $faq_temp_array['data'][3]['items'];

    //Create FAQ Q & A
    function faq_qa($array){
        $return = '';
        foreach($array as $key => $value){
            $return .= '<p id="q'.$value['order'].'"><span>Question:</span> '.str_replace('[YEAR]',date('Y'),$value['question']).'</p>';
            $return .= preg_replace('/<p>/', '<p><span class="answer">Answer:</span> ', str_replace('[YEAR]',date('Y'),$value['answer']), 1);
        }
        return $return;
    }
?>
<div id="pg-directory"> 
    <div id="dirStates" class="FAQ">
        <div class="states-block gray-border xy-padded">
            <div class="catTitle">
                <p class="h3 white-text">Frequently Asked Questions</p>
            </div>
            <div class="gray-border white-bg xy-padded">
                <?php echo faq_qa($faq_array); ?>
            </div>
        </div>
    </div>
</div>
<?php faq_json($faq_array); ?>