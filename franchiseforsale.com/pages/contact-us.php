<?php
$recaptcha = true;
if ($_POST['contact'] == true) { 
    $copy = contact_form();
}
?>

<div id="pg-directory">
    <div id="dirStates" class="advertise">
        <?php if (!is_null($copy)) { ?>
            <br>
            <div class="contact-us-thanks gray-border white-bg xy-padded"><br><p class="h3 text-center"><?php is_safe($copy['message']); ?></p><br></div>
            <br>
        <?php } else{ ?>
        <div class="states-block gray-border xy-padded">
            <div class="catTitle"><p class="h3 white-text">Thoughts and Suggestions</p></div>
            <div class="white-bg gray-border xy-padded mb-1">
                <p>In an effort to continue to better serve our customers, we invite you to provide us feedback on how we are doing -- particularly -- feedback on:</p>
                <ul>
                    <li>Suggested improvements to our website that would improve the user experience.</li>
                    <li>Things that you find that are broken, not working well, or just plain annoying.</li>
                    <li>Additional resources or tools that would be helpful to you as you try to find the right franchise or business opportunity for sale.</li>
                </ul>
                <p>To provide feedback, please contact us via our contact form or call us toll free at <a href="tel:1-888-363-3390">1-888-363-3390</a>.</p>
            </div>
            <div class="catTitle"><p class="h3 white-text">Complete The Form Below</p></div>
            <div class="white-bg gray-border contactForm">
                <?php include_once('includes/contact_form.php'); ?>
            </div>
        </div>
        <?php } ?>
    </div>
</div>
<?php load_js('contact'); ?>