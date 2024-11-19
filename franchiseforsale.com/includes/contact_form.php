

<form method="post" class="ct-form" id="contact-form" action="<?php echo $_SERVER['REQUEST_URI']; ?>/updated">
    <input type="hidden" id="contact" name="contact" value="true">
    <input type="hidden" id="recaptcha_token" name="recaptcha_token">
    <input type="hidden" id="inquiry_type" name="inquiry_type" value="contact_us">
    <div id="formError" class="pd-15 hide"><h3>Please Correct The Following Errors:</h3><ul id="errors"></ul></div>
    <div class="ct-form-row"> 
        <div class="medium-6">
            <label>First Name <span>(Required)</span></label>
            <div class="ff Name"><span></span> 
                <input class="form-controls" type="text" data-val="true" data-val-maxlength="The field First Name must be a string or array type with a maximum length of '25'." data-val-maxlength-max="25" data-val-regex="First name can contain only alphabets, and should be at least two characters long" data-val-regex-pattern="[a-zA-Z]{2,}" data-val-required="First Name Is Required" id="visitor_firstname" minlength="2" maxlength="25" name="visitor_firstname" required>
            </div>
        </div>
        <div class="medium-6">
            <label>Last Name <span>(Required)</span></label>
            <div class="ff Name"><span></span> 
                <input class="form-controls" type="text" data-val="true" data-val-maxlength="The field Last Name must be a string or array type with a maximum length of '25'." data-val-maxlength-max="25" data-val-regex="Last name can contain only alphabets, and should be at least two characters long" data-val-regex-pattern="[a-zA-Z]{2,}" data-val-required="Last Name Is Required" id="visitor_lastname" minlength="1" maxlength="25" name="visitor_lastname" required>
            </div>
        </div>
    </div>
    <div class="ct-form-row">
        <div class="medium-6">
            <label>Country</label>
            <div class="ff Country"><span></span> 
                <select class="country_ddl form-controls" data-val="true" data-val-required="Country Is Required" id="visitor_country" name="visitor_country">
                    <option value="USA">United States</option>
                    <option value="CAN">Canada</option>
                    <option value="GBR">United kingdom</option>
                    <option value="IND">India</option>
                    <option value="VCS">Other</option>
                </select>
            </div>
        </div>
    </div>
    <div class="ct-form-row">
        <div class="medium-6">
            <label>E-mail <span>(Required)</span></label>
            <div class="ff Email"><span></span> 
                <input class="form-controls" type="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" data-val="true" data-val-email="The email address is invalid" data-val-maxlength="The field E-Mail must be a string or array type with a maximum length of '100'." data-val-maxlength-max="100" data-val-required="Email Is Required" id="visitor_email" maxlength="100" name="visitor_email" required>
            </div>
        </div>
        <div class="medium-6">
            <label>Phone <span>(Required)</span></label>
            <div class="ff Phone"><span></span> 
                <!-- <input class="form-controls" type="number" placeholder="enter numbers only" data-val="true" data-val-contactdata="Phone Number is invalid" data-val-contactdata-type="phone" data-val-maxlength="The field Phone must be a string or array type with a maximum length of '25'." data-val-maxlength-max="25" data-val-required="Phone Number Is Required" id="visitor_phone" min="10" max="15" name="visitor_phone" oninput="javascript: if (this.value.length > this.max) this.value = this.value.slice(0, this.max);" required> -->
                <input class="form-controls" type="tel" placeholder="enter numbers only" id="visitor_phone" maxlength="14" name="visitor_phone" required>
            </div>
        </div>
    </div>
    <div class="ct-form-row">
        <div class="medium-6">
            <label>Company Name</label>
            <div class="ff Company"><span></span> 
                <input class="form-controls" type="text" data-val="true" data-val-maxlength="The field Company Name must be a string or array type with a maximum length of '70'." data-val-maxlength-max="70" data-val-regex="Invalid Character(s) In Company Name" data-val-regex-pattern="[a-zA-Z0-9\s\-',&quot;#@!]+" id="visitor_company" maxlength="70" name="visitor_company">
            </div>
        </div>
    </div>
    <div class="ct-form-row">
        <div class="franchiseComments pd-15">
            <label title="click to add comments">Add Comments ▼<span>(Required)</span></label>
            <div class="ff Comments" style="display: block;"><span></span> 
                <textarea class="form-controls" style="resize:none" rows="3" data-val="true" data-val-maxlength="The field Add Comments must be a string or array type with a maximum length of '4000'." data-val-maxlength-max="4000" data-val-regex="Comments cannot contain < or > character(s)" data-val-regex-pattern="[^<>]+" data-val-required="Comments Are Required" id="visitor_message" maxlength="4000" name="visitor_message" required></textarea>
            </div>
        </div>
    </div>
    <div class="ct-form-row">
        <div class="pd-15"><br> 
            <button type="button" class="g-recaptcha btn-orange text-center uppercase" 
            data-sitekey="6LfeKDMgAAAAAO4hONnboJPGGoqs5Hk18995nrwk" 
            data-callback='onSubmit' 
            data-action='submit'>Contact FranchiseForSale.com</button>
            <!-- <input type="button" class="btn-orange text-center uppercase" value="Contact FranchiseForSale.com" onclick="validateContactForms('<?php echo $_SERVER['REQUEST_URI']; ?>');"> -->
        </div>
    </div>
    <input id="EmailWithWarning" name="EmailWithWarning" type="hidden"> 
    <input name="__RequestVerificationToken" type="hidden" value="">
</form>

<script>
    var telEl = document.querySelector('#visitor_phone');

    telEl.addEventListener('keyup', (e) => {
    var val = e.target.value;
    e.target.value = val
        .replace(/\D/g, '')
        .replace(/(\d{1,3})(\d{1,3})?(\d{1,4})?/g, function(txt, f, s, t) {
        if (t) {
            return `(${f}) ${s}-${t}`
        } else if (s) {
            return `(${f}) ${s}`
        } else if (f) {
            return `(${f})`
        }
        });
    });
</script>