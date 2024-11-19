<?php
header('Content-Type: application/json; charset=utf-8');
$geo_ignore = true;
    if(strlen($_GET['concept_id']) == 6 && is_numeric($_GET['concept_id'])){
        $datapoints = $return = $media = $finance = $total_investment = $details = array();
        include_once($_SERVER['DOCUMENT_ROOT'].'/includes/global.php');
        $profile_results = '/api/get_profile/'.$_GET['site_id'].'/'.$_GET['concept_id'];
        $profileServices = get_json($profile_results);
        $profileServices = json_decode($profileServices,true);
        $data = $profileServices['data'][0];
        $brochure = $data['brochure'][0];
        $return['add_to_cart'] = true;
        if($data['investment'] == 0 || is_null($data['investment'])){
            $return['add_to_cart'] = false;
        }
        $details['Primary Category'] = $filtercat[$data['master_categories_id']];
        if($details['Primary Category'] == 0 || is_null($details['Primary Category'])){
            unset($details['Primary Category']);
        }
        foreach ($brochure['images'] as $key => $value) {
            $media[$value['type']] = $value;
            if($value['type'] == 'logo' && $value['sort_order'] == 1){
                $logo = $value['image_url']; 
                break;
            }
        }
        if(in_array($_GET['site_id'],[4,6,7])){
            $logo = $media['logo']['image_url'];
        }
        foreach($data['datapoints'] as $key => $value){
            if(in_array($value['field_name'],['fg_total_capital_min','TotalInvestmentMin'])){
                $finance['min'] = $value['value'];
            }else if(in_array($value['field_name'],['fg_total_capital_max','TotalInvestmentMax'])){
                $finance['max'] = $value['value'];
            }else{
                $datapoints[$value['field_name']] = $value['value'];
            }
        }

        if($finance['min']){
            $total_investment[0] = '$'.number_format($finance['min']);
        }
        if($finance['max']){
            $total_investment[1] = '$'.number_format($finance['max']);
        }

        if ($datapoints['DisplayFinanceAvailable'] != 0) {
            $details['Financing Available'] = $datapoints['FinanceAvailableType'];
        }else if($datapoints['fg_financing_boolean'] != 0) {
            $details['Financing Available'] = 'Yes';
        }

        if ($datapoints['DisplayTrainingAndSupport'] != 0 || $datapoints['fg_training_boolean'] != 0) {
            $details['Training Available'] = 'Yes';
        }

        if ($datapoints['DisplayYearFounded'] != 0) {
            $details['Established'] = $datapoints['YearFounded'];
        }elseif($datapoints['year_founded']){
            $details['Established'] = $datapoints['year_founded'];
        }

        if ($datapoints['FranchisingSince']) {
            $details['Franchising Since'] = $datapoints['FranchisingSince'];
        }elseif($datapoints['franchising_since']){
            $details['Franchising Since'] = $datapoints['franchising_since'];
        }
        if($details['Established'] == $details['Franchising Since'] && $details['Established']){
            $details['Established/Franchising Since'] = $details['Established'];
            unset($details['Established']);
            unset($details['Franchising Since']);
        }


        $return['name'] = $data['name'];
        $return['site_id'] = $data['site_id'];
        $return['rate'] = $data['rate'];
        $return['fbo_id'] = $data['fbo_id'];
        $return['investment'] = $data['investment'];
        $return['mcr'] = '$'.number_format($data['investment']);
        $return['logo'] = safe_logos($logo);
        $return['concept_type'] = ucwords($data['type']);
        if($return['concept_type'] == 'Opportunity'){
            $return['concept_type'] = 'Business Opportunity';
        }
        $return['description'] = $brochure['short_description'];
        $return['total_investment'] = implode(' - ',$total_investment);
        $return['details'] = $details;
        $return['states'] = $states;
        $json['data'] = $return;
        if (json_last_error() === JSON_ERROR_NONE) {
            echo json_encode($json);
        }else{
            echo '{"deleted":["'.$_GET['concept_id'].'"],"results":[],"status":{"code":1,"message":"success"},"remove":[],"disclaimer":""}';
        }
    }else{
        die;
    }
?>