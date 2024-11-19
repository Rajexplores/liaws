
<?php
    function showHighLow(){
        global $prepend_url;
        $noHighLow = '';
        if ($_GET['landing']) {
            $lowInvestmentUrl = $prepend_url.'industry/low-cost-franchises';
            $highInvestmentUrl = $prepend_url.'investment-level/250001';
        }else{
            $lowInvestmentUrl = 'low-cost-franchises';
            $highInvestmentUrl = 'high-investment-franchises';
        }
        if (in_array($_GET['landing_url'], ['great-franchises-a','great-franchises-b','great-franchises-c','great-franchises-d','great-franchises-e','great-franchises-r','great-franchises-demo'])) {
            $noHighLow = '';
        }else{
            $noHighLow = '<div class="search-high-low">
                <div class="catTitle"><p class="h3 white-text">Investment Level By Range</p></div>
                <div class="states-list-block gray-border">
                    <ul>
                        <li><a href="/'.$lowInvestmentUrl.'">Search Low Cost Franchises</a></li>
                        <li><a href="/'.$highInvestmentUrl.'">Search High Investment Franchises</a></li>
                    </ul>
                </div>
                <br>
            </div>';
        }

        echo $noHighLow;
    }
?>


<div id="pg-directory">
    <div id="dirStates">
        <div class="states-block gray-border xy-padded">
            <?php showHighLow(); ?>
            <div class="catTitle"><p class="h3 white-text">Investment Level</p></div>
            <div class="states-list-block gray-border">
                <?php createInvestmentList(); ?>
            </div>
        </div>
    </div>
</div>