<?php
$recaptcha = true;
if ($_POST['contact'] == true) { 
    $copy = contact_form('advertise_with_us');
}
?>

<div id="pg-directory">
    <div id="advertise-with-us" class="advertise">
        <?php if(!is_null($copy)){ ?>
        <div class="advertise-thank-you">
            <br>
            <div class="adv-thankyou-block">
                <br>
                <p class="h3 text-center"><?php is_safe($copy['message']); ?></p>
                <br>
            </div>
            <br>
        </div>
        <?php }else{ ?>
        <div class="states-block gray-border xy-padded">
            <div class="catTitle"><p class="h3 white-text FrancoisOne">Complete The Form Below</p></div>
            <div class="white-bg gray-border contactForm">
                <?php include_once('includes/contact_form.php'); ?>
            </div>
        </div>
        <?php } ?>
    </div>
</div>