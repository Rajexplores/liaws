<?php

include_once('includes/global.php');


if ($_POST['contact'] == true) { 
    $copy = contact_form();
}
?>

<section id="contact-us" class="page-wrapper">
    <section class="hero covered contactUs short-img medium-black-bg" style="background-image:url('/images/hero-images/contactlrg.jpg');">
        <div class="row">
            <div class="columns">
                <div class="textContainer anchored">
                    <div class="accent hidden-md"></div>
                    <br>
                    <h1 class="white-text extra-bold no-margin-bottom text-shadow">Contact Us</h1>
                    <p class="h4 white-text bold text-shadow">Please complete the form below or call us toll free at <a class="white-text extra-bold" href="tel:1-888-363-3390">1-888-363-3390</a>. <br>We would be glad to help you.</p>
                    <div class="accent hidden-lg"></div>
                    <br>
                </div>
            </div>
        </div>
    </section>
    <br>
    <?php if(!is_null($copy)){ ?>
        <div class="row">
            <div class="columns">
                <h3 class="extra-bold">THANK YOU</h3>
                <p><span style="font-size:2em;color:#007f00">✔</span> Your information has been sent successfully.</p>
                <p>Thank you for requesting more information on the Franchise Opportunities Network.</p>
                <p>A member of our team will reach out to you shortly to provide you with more information.</p>
                <p>If you need immediate assistance, please fell free to call us to Toll Free: <a href="tel:18883633390">1-888-363-3390</a></p>
                <br><br>
            </div>
        </div>
    <?php }else{ ?>
    <div class="contactForm row">
        <div class="columns small-centered col-md-10 col-lg-9 xy-padded light-gray-bg medium-gray-bdr mx-auto">
            <form id="contact-form" method="post" action="/contact-us/thank-you">
                <input type="hidden" id="contact" name="contact" value="true">
                <input type="hidden" id="site_name" name="site_name" value="FONE">
                <input type="hidden" id="recaptcha_token" name="recaptcha_token">
                <div class="row">
                    <div class="columns alert-text bold">
                        <div id="formError" class="validation-summary-valid formError hide" data-valmsg-summary="true">
                            <span>Please Correct The Following Errors:</span>
                            <ul><li style="display:none"></li></ul>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="columns col-md-6">
                        <label>First Name <span><small class="secondary-text">(Required)</small></span></label>
                        <div class="ff Name">
                            <span></span> 
                            <input type="text" data-val="true" data-val-maxlength="The field First Name must be a string or array type with a maximum length of '50'." data-val-maxlength-max="50" data-val-regex="First name can contain only alphabets, and should be at least two characters long" data-val-regex-pattern="[a-zA-Z]{2,}" data-val-required="First Name Is Required" id="visitor_firstname" minlength="2" maxlength="25" name="visitor_firstname" required>
                        </div>
                    </div>
                    <div class="columns col-md-6">
                        <label>Last Name <span><small class="secondary-text">(Required)</small></span></label>
                        <div class="ff Name">
                            <span></span> 
                            <input type="text" data-val="true" data-val-maxlength="The field Last Name must be a string or array type with a maximum length of '50'." data-val-maxlength-max="50" data-val-regex="Last name can contain only alphabets, and should be at least two characters long" data-val-regex-pattern="[a-zA-Z]{2,}" data-val-required="Last Name Is Required" id="visitor_lastname" minlength="1" maxlength="25" name="visitor_lastname" required>
                        </div>
                    </div>
                </div>
                <div class="row y-padded">
                    <div class="columns col-md-6">
                        <label>Phone <span><small class="secondary-text">(Required)</small></span></label>
                        <div class="ff Phone">
                            <span></span> 
                            <input type="number" placeholder="enter numbers only" data-val="true" data-val-contactdata="Phone Number is invalid" data-val-contactdata-type="phone" data-val-maxlength="The field Phone must be a string or array type with a maximum length of '25'." data-val-maxlength-max="25" data-val-required="Phone Number Is Required" id="visitor_phone" minlength="10" maxlength="15" name="visitor_phone" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" required>
                        </div>
                    </div>
                    <div class="columns col-md-6">
                        <label>E-mail <span><small class="secondary-text">(Required)</small></span></label>
                        <div class="ff Email">
                            <span></span> 
                            <input type="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" data-val="true" data-val-email="The email address is invalid" data-val-maxlength="The field E-Mail must be a string or array type with a maximum length of '100'." data-val-maxlength-max="100" data-val-required="Email Is Required" id="visitor_email" maxlength="100" name="visitor_email" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="columns franchiseComments">
                        <label title="click to add comments">Add Comments</label>
                        <div class="ff Comments">
                            <span></span> 
                            <textarea style="resize:none" rows="3" cols="20" data-val="true" data-val-maxlength="The field Add Comments must be a string or array type with a maximum length of '4000'." data-val-maxlength-max="4000" data-val-regex="Comments cannot contain < or > character(s)" data-val-regex-pattern="[^<>]+" data-val-required="Comments Are Required" id="visitor_message" maxlength="4000" name="visitor_message"></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="columns submit">
                        <br> 
                        <!-- <input type="submit" value="submit" class="button extra-bold uppercase large"> -->
                        <button type="button" class="g-recaptcha button extra-bold uppercase large" 
                        data-sitekey="6LfdijYgAAAAAE2Os0bJcviMOIPb5R67xnyIqb9v" 
                        data-callback='onSubmit' 
                        data-action='submit'>Submit</button>
                    </div>
                </div>
                <input id="EmailWithWarning" name="EmailWithWarning" type="hidden"> 
                <input name="__RequestVerificationToken" type="hidden" value="CfDJ8DAP3b4WttNJm731Kmuk6MslAysZnECKOodkV2OL23opWEoCRl6gt0Ku0XTc3FIy8-8kkIPMLiMKLcU92UrzQ04R4juPcOuBHc3CtCEJ0Y-a_-m0_1RHKibnuU2NaRdHt3NSOElY5a92EGHWn18Bk7I">
            </form>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="columns small-centered col-md-10 col-lg-9 mx-auto">
            <p>Toll Free Phone: <a href="tel:1-888-363-3390">1-888-363-3390</a></p>
        </div>
    </div>
    <?php } ?>
    <br>
</section> 