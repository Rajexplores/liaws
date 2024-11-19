<?php

$cities_data = get_json('/api/citypage_cities');
$cities_data = json_decode($cities_data,true);

function createCityList(){
    global $cities_data;

    $tempStates = '<ul>';
    foreach ($cities_data['data'] as $key => $value) {
        $tempStates .= '<li><a href="/near-me/'.$value['cid'].'">'.$value['display_name'].'</a></li>';
    }
    $tempStates .= '</ul>';

    echo $tempStates;
}


?>

<div id="pg-directory">
    <div id="dirStates">
        <div class="states-block gray-border xy-padded">
            <div class="catTitle"><p class="h3 white-text">Cities</p></div>
            <div class="states-list-block gray-border">
                <?php createCityList(); ?>
            </div> 
        </div>
    </div>
</div>  