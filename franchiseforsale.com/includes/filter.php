<div id="filter"> 
    <form id="filter_form" action="<?php echo search_value(); ?>" method="GET" class="search-form" name="search-form">
        <div class="intro-item2-row"> 
            <div class="form-group"> 
                <select name="industry" id="category" class="form-control" onchange="filter_action('category','<?php echo $prepend_url; ?>')">
                <?php create_select($filtercat,'Industry',$cat_id,$skippedCategories); ?>
                </select> 
            </div> 
            <div class="form-group"> 
                <select name="state" id="state" class="form-control" onchange="filter_action('state','<?php echo $prepend_url; ?>')">
                    <optgroup label="Global">
                        <option value="CAN" <?php echo $homeState == 'CAN' ? 'selected' : '' ?>>Canada</option>
                        <!-- <option value="INT" <?php //echo $homeState == 'INT' ? 'selected' : '' ?>>International</option> -->
                    </optgroup> 
                    <optgroup label="USA">
                        <?php create_select($filterstate,'State',$homeState); ?>
                    </optgroup>
                    
                </select> 
            </div> 
            <div class="form-group"> 
                <select name="investment" id="investment_filter" class="form-control" onchange="filter_action('investment','<?php echo $prepend_url; ?>')"><?php investment_select(@$_GET['investment']); ?></select>
            </div> 
            <div class="form-group search-button"> 
                <button id="search-btn" class="btn btn-lg btn-submit" onClick="filter_go(); return false;">Search in <span id="current_state"><?php echo $home_statename; ?></span></button>
            </div> 
        </div> 
    </form>  
</div>