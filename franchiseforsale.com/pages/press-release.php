<?php 
    $replace = 'https://assets.franchiseforsale'; 
    $search = 'https://assets.franchiseopportunities'; 
    $press_release = str_replace($search, $replace, $press_array['press_release_content']);
    $prContentMain = $press_release;
    $prContent = explode(' ', $prContentMain );
    $prImage = [];
    foreach($prContent as $k=>$word){
        if (strpos($word, 'https://assets.franchiseforsale.com/') !== false) {
            array_push($prImage, strtolower($word));
        }else{
            array_push($prImage, $word);
        }
    }

    $prContentAll = implode(' ', $prImage);
?>

<div id="pg-directory">
    <div id="dirStates" class="privacyPolicy">
        <div class="states-block gray-border xy-padded">
            <div class="catTitle"><p class="h3 white-text FrancoisOne"><?php echo date("l, M d, Y", strtotime($press_array['release_date'])); ?></p></div>
            <div class="white-bg gray-border pd-15"><br>
                <p class="h3 FrancoisOne"><?php echo $press_array['title']; ?></p>
                <p><a class="topPressLink" href="<?php echo $prepend_url.$temp_url; ?>">Please click here for more information on <u><?php echo $profile['name']; ?></u></a></p>
                <div id="pressReleaseText" class="pressReleaseText"><?php echo html_entity_decode($prContentAll); ?></div>
                <br>
            </div>
        </div>
    </div>
</div> 