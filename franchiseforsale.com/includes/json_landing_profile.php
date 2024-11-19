<?php
// header('Content-Type: application/json; charset=utf-8');
$path = $_SERVER['DOCUMENT_ROOT'];
$path .= '/includes/global.php';
include_once($path);

$temp_checker = get_json('/api/profiles_mapping/'.$site_id.$is_pending);
// debug($temp_checker);die;
$checker = json_decode($temp_checker,true);

function return_logo($array){
    foreach($array as $key => $value){
        if($value['type'] == 'logo'){
            return $value['image_url'];
        }else{
            continue;
        }
    }
}

    // if(isset($_GET['name']) == 5){
    if ($site_id == 4) {
        $temp_name = '/'.$_GET['profile_type'].'/'.$_GET['name'].'/';
    }elseif ($site_id == 5){
        $temp_name = '/franchise/'.strtolower($_GET['name']);
    }else{
        $temp_name = strtolower($_GET['name']);
    }
    if (array_key_exists($temp_name, $checker['data'])) {
        $profileData = '';
        $isProfile = true;
        $media = [];
        $concept_id = $checker['data'][$temp_name];
        // $concept_id = $_GET['idlist'];
        $profile_results = '/api/get_profile/'.$site_id.'/'.$concept_id;
        $profileServices = get_json($profile_results);
        $profileServices = json_decode($profileServices,true);
        // echo $profileServices;
        $profile = $profileServices['data']['0'];
        $data_name = preg_replace('/[^0-9a-zA-Z ]/','',$profile['name']);

        $brochure = $profile['brochure']['0'];
        if ($site_id == 4) {
            $logo = return_logo($brochure['images']);
        }else{
            foreach ($brochure['images'] as $key => $value) {
                if($value['type'] == 'logo' && $value['sort_order'] == 2){
                    $logo = $value['image_url']; 
                    break;
                }
                $media[$value['type']] = $value;
            }
        }
        
        if($site_id == 6 || $site_id == 7){
            $logo = $media['logo']['image_url'];
        }
        $shortDescription = $brochure['short_description'];
        $conceptType = strtoupper($profile['type']);

        foreach($profile['datapoints'] as $key => $value){
            $datapoints[$value['field_name']] = $value['value'];
        }

        $data_invest = preg_replace('/[^0-9]/','',$datapoints['TotalInvestmentMin']);
        if(empty($datapoints['TotalInvestmentMin'])){
            $data_invest = 0;
        }

        $financing = '';
        if ($datapoints['DisplayFinanceAvailable'] != 0) {
            $financing = $datapoints['FinanceAvailableType'];
        }else if($datapoints['fg_financing_boolean'] != 0) {
            $financing = 'Yes';
        }

        $training = '';
        if ($datapoints['DisplayTrainingAndSupport'] != 0) {
            $training = $datapoints['TrainingAndSupport'];
        }else if($datapoints['fg_training_boolean'] != 0) {
            $training = 'Yes';
        }

        $established = '';
        if ($datapoints['DisplayYearFounded'] != 0) {
            $established = $datapoints['YearFounded'];
        }elseif($datapoints['year_founded']){
            $franchising = $datapoints['year_founded'];
        }

        $franchising = '';
        if ($datapoints['FranchisingSince']) {
            $franchising = $datapoints['FranchisingSince'];
        }elseif($datapoints['franchising_since']){
            $franchising = $datapoints['franchising_since'];
        }

        $profileData .= '<img src="'.$logo.'" class="p_modal"/>';
        $profileData .= '<h1>'.$data_name.'</h1>';
        $profileData .= '<p class="text-center concept-type">( '.$conceptType.' )</p>';
        $profileData .= '<p>'.$shortDescription.'</p>';
        $profileData .= '<div class="profile_body">';
        $profileData .= '<div class="col-md-2 col-lg-4 text-center"><div class="pd-15">';
        $profileData .= '<h4>Investment Level</h4>';
        $profileData .= '<p>'.$profile['investment'].'</p>';
        $profileData .= '</div></div>';
        if (!empty($financing)) {
            $profileData .= '<div class="col-md-2 col-lg-4 text-center"><div class="pd-15">';
            $profileData .= '<h4>Financing Available</h4>';
            $profileData .= '<p>'.$financing.'</p>';
            $profileData .= '</div></div>';
        }
        if (!empty($training)) {
            $profileData .= '<div class="col-md-2 col-lg-4 text-center"><div class="pd-15">';
            $profileData .= '<h4>Training Available</h4>';
            $profileData .= '<p>'.$training.'</p>';
            $profileData .= '</div></div>';
        }
        $profileData .= '<div class="col-md-2 col-lg-4 text-center"><div class="pd-15">';
        $profileData .= '<h4>Primary Category</h4>';
        $profileData .= '<p>'.$profile['category_name'].'</p>';
        $profileData .= '</div></div>';
        if (!empty($established)) {
            $profileData .= '<div class="col-md-2 col-lg-4 text-center"><div class="pd-15">';
            $profileData .= '<h4>Established</h4>';
            $profileData .= '<p>'.$established.'</p>';
            $profileData .= '</div></div>';
        }
        if (!empty($franchising)) {
            $profileData .= '<div class="col-md-2 col-lg-4 text-center"><div class="pd-15">';
            $profileData .= '<h4>Franchising Since</h4>';
            $profileData .= '<p>'.$franchising.'</p>';
            $profileData .= '</div></div>';
        }
        $profileData .= '</div>'; 

        $button = '<button class="basket" onclick="checkCart('.$profile['fbo_id'].',\''.$data_name.'\','.$data_invest.',\''.$units[$profile['site_id']].'\','.$isProfile.','.$profile['rate'].');closeModal(&#39;profile_view&#39;);"><span>Add To Request Info Basket</span></button>';
        $profileData .= '<div class="listing result-checkbox">';
        $profileData .= '<input type="checkbox" class="temp-checkbox checkbox_'.$profile['fbo_id'].'" value="'.$profile['fbo_id'].'" id="checkbox_'.$profile['fbo_id'].'">';
        $profileData .= $button;
        $profileData .= '</div>';

        $data = ['status' => 'success','id'=>$profile['fbo_id'] ,'data'=>$profileData];
        header('Content-Type: application/json');
        echo json_encode($data);
    }
?>