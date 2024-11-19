//Default values and synchronous functions
var lf_country = document.getElementById('country_div');
if (lf_country !== null) {
    lf_country.onchange = function(){
        changeState();
    };
}
document.getElementById("device_type").value = navigator?.userAgentData?.platform || navigator?.platform || 'unknown';

//After window loads
window.onload=function(){
    //Set Minumum Height of Main Area
    lead_form();
    //Remove Cookies
    document.getElementById("valid_cookies").classList.remove("show");
    //Load Cart
    fillBasket();
    //Is the Cart empty
    direct_hit();
    //Telephone Format
    telephone();
    //Add Submission URL to Form
    document.getElementById("submission_url").value = localStorage.previous_page;
    //Get Suggested Listings
    getSuggestedListings();
    
    document.getElementById('investment').addEventListener('change', function() {
        ga4_datalayer('fv_available_cash_select');
    });
}

if (window.history && window.history.pushState) {
    window.addEventListener('popstate', function() {
        loadCart();
    });
}

//Submit Newsletter
function step1(landingUrl='',append=''){ 
    var xhr1 = new XMLHttpRequest(),
        xhr2 = new XMLHttpRequest(),
        url = "/newsletter.json",
        email = document.getElementById('pre_email').value,
        newsletter = document.getElementById('pre_newsletter'),
        form_type = document.getElementById('pre_form_type').value,
        data = 'form_type='+form_type;
    if(newsletter.checked == true){
        setCookie('newsletter','on');
        data += '&newsletter=1';
    }else{
        window.localStorage.setItem('newsletter', null);
        data += '&newsletter=0';
    }
    xhr2.open('GET', '/email.json?email_address=' + email, true);
    xhr2.onload = function() {
        if (xhr2.status === 200) {
            email = JSON.parse(xhr2.responseText)?.email;
        }
        data += '&subscribe='+email;
        xhr1.open("POST", url);
        xhr1.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr1.onload = function () {
            ga4_datalayer('fv_submit_form',null,null,'step1');
            window.location.href = '/'+landingUrl+'request-information';
        }
        xhr1.send(data);
    }
    xhr2.send();
}

//Get Cookie
function getCookie(name) {
    var v = document.cookie.match('(^|;) ?' + name + '=([^;]*)(;|$)');
    return v ? v[2] : null;
}

//Set Cookie
function setCookie(key,value){
    var date = new Date();
    date.setTime(date.getTime()+(1*24*60*60*1000));
    var expires = "; expires="+date.toGMTString();
    document.cookie = key+"="+value+expires+"; path=/";
}

//Previous Page
function direct_hit(){
    if(document.getElementById("fbolist").value == ''){
        document.body.classList.add("bad");
    }
}

//Non-consent form
function is_consent(check_consent) {
    var noconsent = document.getElementById("noconsent");
    noconsent.style.display = check_consent.checked ? noconsent.classList.remove("required") : noconsent.classList.add("required");
}

//Minimum Height for Lead Form area
function lead_form(){
    //Hide Menu Stuff on Steps
    if($('#form_0').hasClass('not_step')){
        if(window.innerWidth >= 970){
            var lead_form = document.getElementById('lead-form'), 
                footer = document.querySelector('footer#footer'),
                display = window.innerHeight;
            var style = lead_form.currentStyle || window.getComputedStyle(lead_form);
            var header = style.marginTop.replace('px', ''),footer = footer.offsetHeight;
            var min_height = display - header - footer;
            document.getElementById('lead-form').style.minHeight = min_height+'px';
        }
    }else{
        document.getElementById('lead-form').id = 'main';
        document.getElementById('form_0').classList.remove('not_step');
        document.getElementById('form_1').classList.add('not_step');
        document.getElementById('form_2').classList.add('not_step');
    }
}

//Re-fill Basket List
function refillBasket(fboIds){
    var xhr1 = new XMLHttpRequest(),
        selected_basket_html = '';
    xhr1.open('GET', '/lf-cart.json?site_id='+lfid+'&fboid_list='+fboIds);
    xhr1.onload = function() {
        if (xhr1.status === 200) {
            var cartObj = JSON.parse(xhr1.responseText);
            var cartArr = cartObj.data;
            if (cartArr.length != 0) {
                cartArr.forEach(value => {
                    var sInvest = value.investment;
                    if(sInvest == null){
                        data_invest = 0;
                    }
                    selected_basket_html += '<li id="selected'+value.fbo_id+'" onclick="view_summary('+value.site_id+','+value.concepts_id+');">';
                    selected_basket_html += '<div class="selected_logo"><div>';
                    selected_basket_html += '<img src="'+value.image_url+'">';
                    selected_basket_html += '</div></div>';
                    selected_basket_html += '<div class="selected_details">';
                    selected_basket_html += '<h4><i class="svg concept"></i>'+value.name+'</h4>';
                    selected_basket_html += '<h4><i class="svg cost"></i>'+format_investment(sInvest)+'</h4>';
                    selected_basket_html += '<span class="trash"><i class="svg trashcan"></i></span>';
                    selected_basket_html += '</div>';
                    selected_basket_html += '</li>';
                    current = null;
                });
                var selections = 'You have <strong>'+cartArr.length+'</strong> selection';
                if(cartArr.length != 1){
                    selections += 's';
                }
                document.getElementById('selected_count').innerHTML = selections;
                document.querySelector('.selected_wrapper').style.width = (document.querySelector('.lf-left').offsetWidth - 4)+'px';
                document.getElementById('selected_basket').innerHTML = selected_basket_html;
                setTimeout(()=>{
                    $('#selected_basket').slick({
                        dots: false,
                        infinite: true,
                        speed: 300,
                        slidesToShow: 2,
                        adaptiveHeight: true
                    });
                }, 100);
            }else{
                let clear_fboids = fboIds.split(','),
                    clear_cart = JSON.parse(localStorage.getItem('cart'));
                for (var i = 0; i < clear_fboids.length; i++) {
                    if(clear_fboids[i].length == 5){
                        cartStorage(clear_fboids[i],null,null,'delete');
                        delete clear_cart['ID'+clear_fboids[i]];
                    }
                }
                setTimeout(function() {
                    window.localStorage.setItem('cart', JSON.stringify(clear_cart));
                }, 250);
            }
        }else{
            console.log('Request failed.  Returned status of ' + xhr2.status);
        }
    };
    xhr1.send();  
}

//Fill Basket List
function fillBasket(){
    var selected_basket = document.getElementById('selected_basket')
            empty = '',
            basket = '',
            highest = 0,
            lowest = 99999999;
    if (localStorage.cart) {
        var load_cart = JSON.parse(localStorage.getItem('cart'));
        if (Object.keys(load_cart).length === 0 && load_cart.constructor === Object) { 
            basket = ',';
            selected_basket.innerHTML = empty;
            document.getElementById('lead-form').id = 'main';
            document.getElementById('main').style = null;
            document.body.classList.remove('lf_body');
            document.getElementById('form_0').classList.remove('not_step');
            document.getElementById('form_1').classList.add('not_step');
            document.getElementById('form_2').classList.add('not_step');
        }else{
            Object.keys(load_cart).forEach(function(key) {
                var fboid = key.replace("ID", "");
                basket += fboid+','; 
                if(load_cart[key]['investment'] >= highest){
                    highest = load_cart[key]['investment'];
                }
                if(load_cart[key]['investment'] <= lowest){
                    lowest = load_cart[key]['investment'];
                }
            });
            var fboIds = basket.substring(0, basket.length - 1);

            refillBasket(fboIds); 

            var guidant = document.getElementById("guidant_div");
            var xhr2 = new XMLHttpRequest();
            xhr2.open('GET', '/guidant.json');
            xhr2.onload = function() {
                if (xhr2.status === 200) {
                    var data = JSON.parse(xhr2.responseText),gc = getCookie('gc'); 
                    if(data && Array.isArray(data['data'])){
                        var dataObject = data['data'][0];
                        if (guidant !== null) {
                            if(dataObject['active']==1 && highest >= dataObject['min_cash']){
                                guidant.classList.add('min');
                            }else{
                                guidant.classList.remove('min');
                            } 
                        }
                    }
                }else{
                    console.log('Request failed.  Returned status of ' + xhr2.status);
                }
            }
            xhr2.send();
        }       
    }else{ 
        selected_basket.innerHTML = empty;  
    }
    document.getElementById("fbolist").value = fboIds;
    document.getElementById("highest").value = highest;
    if(lowest == 99999999){
        lowest = 0;
    }
    document.getElementById("lowest").value = lowest;
    preselectInvestment();
}

//Preselect Investment
function preselectInvestment(lowest=0){
    var options = document.getElementById("investment"),
        continue_check = true,
        highest = lowest == 0 ? parseInt(document.getElementById("highest").value) : lowest;
    for(i=0; i<options.options.length; i++){
        if(continue_check == true){
            var number = parseInt(options.options[i].value);
            if(!isNaN(number) && number >= highest){
                if (lowest != 0) {
                    return number;
                }else{
                    document.getElementById('investment').value = number;
                }
                continue_check = false;
            }
        }
    }
}

//Update Cart Count
function updateTotal(){
    var load_cart = JSON.parse(localStorage.getItem('cart')),
        total = document.getElementsByClassName("cart_count");     
    if (localStorage.cart) { 
        var count = Object.keys(load_cart).length;
    }else{
        var count = 0;
    }
    setCookie('cart_count',count);
    for (i = 0; i < total.length; i++) {
        total[i].innerHTML = count;
    }
}

// Get Suggested Listings
function getSuggestedListings(){
    var preferred_state = document.getElementById('preferred_state').value,
        highest = document.getElementById('highest').value,
        fboIds = document.getElementById('fbolist').value,
        units = {'4':'FG','5':'FON','6':'FCN','7':'FCN'};
    var fboIdsArr = fboIds.split(',');
    if (fboIdsArr.length === 0 || fboIdsArr.length > 3) {
        document.getElementById('suggested_container').classList.add('hide');
        if(window.innerWidth >= 970){
            document.getElementById('selected_container').classList.add('open');
        }
    }else if(fboIdsArr.length < 4){ 
        var xhr = new XMLHttpRequest();
        xhr.open('GET', '/suggestions.json?state_code='+preferred_state+'&max='+highest+'&per_page=5&requestlist='+fboIds, true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                var suggestObj = xhr.responseText.replace(/'/g, '');
                var suggestObj = JSON.parse(suggestObj);
                var suggestedListings = '';
                var suggestedArr = suggestObj.data;
                const arr = Array.from(suggestedArr);
                if (arr.length != 0) {
                    arr.forEach(value => {
                        var data_invest = parseInt(value.investment.toLocaleString().replace(/\D/g, ''));
                        if(value.investment == null){
                            data_invest = 0;
                        }
                        let decode = document.createElement("textarea");
                        decode.innerHTML =  value.name;
                        var data_name = decode.value.replace(/[^\w\s!?]/g,'');
                        suggestedListings += '<li id="suggested'+value.fbo_id+'">';
                        suggestedListings += '<div class="suggested_logo"><div>';
                        suggestedListings += '<img src="'+value.image_url+'">';
                        suggestedListings += '</div></div>';
                        suggestedListings += '<div class="suggested_details">';
                        suggestedListings += '<div class="suggested_header">';
                        suggestedListings += '<h4><i class="svg concept"></i>'+value.name+'</h4>';
                        suggestedListings += '<h4><i class="svg cost"></i>'+format_investment(value.investment)+'</h4>';
                        suggestedListings += '</div>';
                        suggestedListings += '<p>'+truncateString(value.ShortDesc,150)+'</p>';
                        suggestedListings += '<div class="suggested_buttons">';
                        suggestedListings += '<a href="javascript:;" class="suggested_modal" onclick="view_summary('+value.site_id+','+value.concepts_id+');"></a>';
                        suggestedListings += '<a href="javascript:;" class="button_'+value.fbo_id+' suggested_add" onclick="button_concept('+value.fbo_id+',\''+data_name+'\','+data_invest+',\''+units[value.site_id]+'\','+value.rate+');">&nbsp;Cart</a>';
                        suggestedListings += '</div>';
                        suggestedListings += '</div>';
                        suggestedListings += '</li>';
                        current = null;
                    });
                    document.getElementById('suggested_wrapper').style.width = document.querySelector('.lf-left').offsetWidth+'px';
                    document.getElementById('suggestions').innerHTML = suggestedListings;
                    document.getElementById('suggested_container').classList.remove('hide');
                    $('#suggestions').slick({
                        dots: false,
                        infinite: true,
                        speed: 300,
                        slidesToShow: 1,
                        adaptiveHeight: true
                    });
                }
            }else{
                console.log('Request failed.  Returned status of ' + xhr.status);
            }
        };
        xhr.send(); 
    }  
}

//Truncate String
function truncateString(str, max) {
    var index = str.indexOf(" ", max);
    return index === -1 ? str : str.substring(0, index)+ "...";
}

//Toggle Investment
function toggleInvestment() {
    document.getElementById("investment_div").classList.remove('checked'); 
    var checkBox = document.getElementById("investment_toggle"),
        required = true;
    if (checkBox.checked == true){
        document.getElementById("investment_div").classList.add('checked'); 
        required = false;
    }
    ga4_datalayer('fv_available_cash_toggle');
    document.getElementById("investment").required = required;  
}

//Submit Request
function sendRequest(){
    if (false === document.getElementById('privacy-accept').checked) {
        return;              
    }
    if (false === document.getElementById('investment_toggle').checked) {
        var base_line = parseInt(document.getElementById('investment').value),
            lowest = parseInt(document.getElementById('lowest').value),
            errors = '<div>Please update your Available Cash to Invest.</div>';
            /* errors = '<div><h3><i>&#9888;</i>Please Correct The Following Errors:</h3>Please update your Available Cash to Invest.</div>'; */
        if(lowest > base_line){
            document.getElementById('fails').innerHTML = errors;
            document.body.classList.add("overflow");
            return;              
        }            
    }
    var xhr = new XMLHttpRequest(),
        url = "/validator.json",
        post_loop = ['fullname','email','zipcode','country','phone','newsletter','fbolist','session_id'],
        data = "validate=true&allClients=1",
        temp_investment = document.getElementById('investment').value,
        arrayLength = post_loop.length; 
    for (var i = 0; i < arrayLength; i++) {
        if(post_loop[i] == 'fullname'){
            if (document.getElementById('fullname')) {
                    let fullname = document.getElementById('fullname').value,
                        temp_last = '';
                    fullname = fullname.trim();
                    let temp_name = fullname.split(' ');
                    if(temp_name.length > 1){
                        temp_last = temp_name.join('-').trim();
                    }
                    data += "&firstname="+temp_name[0]+"&lastname="+temp_last;
            }else{
                data += "&firstname=&lastname=";
            }
        }else{
            var temp_value = '';
            if (document.getElementById(post_loop[i])) {
                temp_value = document.getElementById(post_loop[i]).value;
                if(post_loop[i] == 'phone'){
                    temp_value = temp_value.replace(/[^0-9]/g, '');
                }
            }
            data += "&"+post_loop[i]+"="+temp_value;
        }
    }
    if (true === document.getElementById('investment_toggle').checked) {
        temp_investment = document.getElementById('highest').value;        
    }
    data += "&investment="+temp_investment;
    xhr.open("POST", url);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var json = JSON.parse(xhr.responseText);
            if(json.status == 'failure'){
                var errors = '<div>';
                /* var errors = '<div><h3><i>&#9888;</i>Please Correct The Following Errors:</h3'; */
                Object.keys(json.message).forEach(function(key) {
                    errors += json.message[key]+"<br/>";
                });
                errors += '</div>';
                document.getElementById('fails').innerHTML = errors;
                document.body.classList.add("overflow");
            }else{
                var session_id = global_cookie.udid;
                if (typeof(json.new_session_id) != 'undefined' && json.new_session_id != null){
                    session_id = json.new_session_id; 
                    document.getElementById('session_id').value = json.new_session_id;
                    var date = new Date(),
                    cookie_domain = '.'+window.location.hostname.replace(/^[^.]+\./g, "");
                    date.setTime(date.getTime()+(1*24*60*60*1000));
                    document.cookie = "udid="+session_id+"; expires="+date.toGMTString()+"; Domain="+cookie_domain+";secure; path=/";
                }
                ga4_datalayer('fv_submit_form',null,null,'lead_submission');
                ga4_event('begin_checkout');
                var action = document.getElementById('form_2').getAttribute('data-action');
                document.getElementById('waiting').classList.add('wait');  
                document.getElementById('request').value='accept';
                document.getElementById('form_2').action = action;
                document.getElementById("form_2").submit();
            }
        }
    };
    xhr.send(data);
};

// Button Add/Remove
function button_concept(fbo_id,name,investment,id,rate){
    var button = document.querySelector('.button_'+fbo_id),
        temp_height = $('#selected_basket').height();
    if(button.classList.contains('added')){
        document.getElementById('rmConceptName').innerHTML = '"'+name+'"';
        sessionStorage.setItem('rmFboid',fbo_id);
        all_modal('remove_modal','show');
    }else{
        $('.button_'+fbo_id).addClass('added'); 
        add_concept(fbo_id,name,investment,id,rate);
        $('#selected_basket').css('overflow',temp_height+'px').attr('class','').html('');
        fillBasket();
        $('#selected_basket').attr('style','');
        if(button.classList.contains('added')){
            setTimeout(()=>{
                all_modal('summary_modal'); 
                setTimeout(()=>{
                    $('#suggestions button.slick-next.slick-arrow').click();
                }, 100);
            }, 500);
        }
    }
}

// Add to Cart
function add_concept(fboid,franName,investment,id,rate){
    var key = 'ID'+fboid;
    if (localStorage.cart) {
        var new_cart = JSON.parse(localStorage.getItem('cart'));
        new_cart[key] = {['name']:franName,['investment']:investment,['id']:id,['rate']:rate};
    }else{
        var new_cart = {[key]:{['name']:franName,['investment']:investment,['id']:id,['rate']:rate}};
    }
    window.localStorage.setItem('cart', JSON.stringify(new_cart));
    cartStorage(fboid,investment,franName,'add',rate);
    updateTotal();
}

//Remove from Cart
function remove_concept(){
    if(sessionStorage.getItem('rmFboid') != null){
        if (localStorage.cart) {
            var load_cart = JSON.parse(localStorage.getItem('cart'));
            if (Object.keys(load_cart).length === 1 && load_cart.constructor === Object) { 
                all_modal('final_modal','show');
            }else{
                var temp_height = $('#selected_basket').height(),
                    fbo_id = sessionStorage.getItem('rmFboid');
                $('.button_'+fbo_id).removeClass('added'); 
                var key = 'ID'+fbo_id,
                    new_cart = JSON.parse(localStorage.getItem('cart'));
                cartStorage(fbo_id,null,null,'delete');
                setTimeout(function() {
                    delete new_cart[key];
                    window.localStorage.setItem('cart', JSON.stringify(new_cart));
                    updateTotal();
                    $('#selected_basket').css('height',temp_height+'px').attr('class','').html('');
                    fillBasket();
                    sessionStorage.removeItem('rmFboid');
                    $('#selected_basket').attr('style','');
                    all_modal('remove_modal');
                }, 250);
            }
        }
    }
}

//Investment String
function format_investment(reinvest){
    reinvest = reinvest.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    var nameSplit = reinvest.split(".");
    var tempvestment = '$'+nameSplit[0];
    if(nameSplit.length == 3){
        var temptrim = Number(nameSplit[1]).toString();
        if(temptrim != 0){
            tempvestment += ','+temptrim+',000';
        }else{
            tempvestment += 'M';
        }
    }else if(nameSplit.length == 2){
        var temptrim = Number(nameSplit[1]).toString();
        if(temptrim != 0){
            tempvestment += ','+temptrim;
        }else{
            tempvestment += 'K';
        }
    }
    return tempvestment;
}

function changeState(){
    var city = document.getElementById('city_div'),
        country = document.getElementById('country');
    if (typeof(country) != 'undefined' && country != null){
        if (country.value == 'USA') {
            city.classList.add('hide');
        }else{
            city.classList.remove('hide');
        }
    }
}

function toggle_mobile(){
    ga4_datalayer('fv_your_selections_toggle');
    var element = document.getElementById("selected_container");
    element.classList.toggle("open");
    $('#selected_basket').slick('refresh');
}

function telephone(){
    var phone = document.getElementById("phone");
    phone.addEventListener('keyup', (e) => {
        var val = e.target.value,
            key = event.key;
        if (['0','1'].includes(val)){
            val = '';
        }
        e.target.value = val
            .replace(/\D/g, '')
            .replace(/(\d{1,3})(\d{1,3})?(\d{1,4})?/g, function(txt, f, s, t) {
            if (t) {
                return `(${f}) ${s}-${t}`
            } else if (s) {
                return `(${f}) ${s}`
            } else if (f) {
                if (key === "Backspace" || key === "Delete") {
                    return `(${f}`
                }else{
                    return `(${f})`
                }
            }
        });
    });
}

//New View Summary Modal
function view_summary(site_id,concept_id){
    var xhr = new XMLHttpRequest();
    xhr.open('GET', '/lf-'+site_id+'-'+concept_id+'.json');
    xhr.onload = function() {
        if (xhr.status === 200) {
            var data = JSON.parse(xhr.responseText),
                results = data['data'],
                fran_name = results.name.replace(/[^\x20-\x7E]/g, '').replace('&#2013265929;', 'é').replace(/\\/g, '').replace(/\'/g, '').replace('&amp;', '&').replace(';', ''),
                left = '<div><div class="summary_logo"><div><img src="'+results.logo+'"/></div></div><h2 class="lf-h2 mobile">'+fran_name+'</h2></div>',
                temp_top = '<h2 class="lf-h2 desktop">'+fran_name+'</h2><h3 class="lf-h3 border-gradient">About</h3><p>'+results.description.replace(/[^\x20-\x7E]/g, '').replace(/\\/g, '')+'</p>',
                temp_bottom = '<div><strong>Cash Required</strong><span>'+results.mcr+'</span></div>',
                temp_list = '<li><strong>Business Type:</strong><span>'+results.concept_type+'</span></li>',
                fboIds = document.getElementById('fbolist').value,
                added = '';

                let decode = document.createElement("textarea");
                //console.log(results.name);
                decode.innerHTML =  results.name;
                var data_name = decode.value.replace(/[^\w\s!?]/g,'');
            if(fboIds.includes(results['fbo_id'])){
                added = ' added';
            }

            if(results.total_investment != '') {
                temp_bottom += '<div><strong>Total Investment</strong><span>'+results.total_investment+'</span></div>';
            }
            if(results.details.length !== 0){
                for (var k in results.details){
                    temp_list += '<li><strong>'+k+':</strong><span>'+results.details[k]+'</span></li>';
                }
            }
            temp_top += '<h3 class="lf-h3 border-gradient">Details</h3><ul>'+temp_list+'</ul>';
            left += '<div class="summary_buttons desktop"><a href="javascript:;" class="button_'+results['fbo_id']+' suggested_add'+added+'" onclick="button_concept('+results['fbo_id']+',\''+data_name+'\','+results['investment']+',\''+results['site_id']+'\','+results['rate']+');">&nbsp;Cart</a></div><div class="summary_box mobile">'+temp_bottom+'</div>'; 
            var summary_content = '<div class="summary_left">'+left+'</div><div class="summary_right"><div>'+temp_top+'</div><div class="summary_buttons mobile"><a href="javascript:;" class="button_'+results['fbo_id']+' suggested_add'+added+'" onclick="button_concept('+results['fbo_id']+',\''+data_name+'\','+results['investment']+',\''+results['site_id']+'\','+results['rate']+');">&nbsp;Cart</a></div><div class="summary_box desktop">'+temp_bottom+'</div></div>';
            document.querySelector('#summary_modal .lf-content').innerHTML = summary_content;
            document.body.classList.add("overflow");
            all_modal('summary_modal','show'); 
            ga4_single('view_item',fboIds,data_name,results['site_id'],results['rate']);
        }
        else {
            console.log('Request failed.  Returned status of ' + xhr.status);
        }
    };
    xhr.send();
}

// Modal Function
function all_modal(modal,type = 'hide'){
    if(type == 'show'){
        document.getElementById(modal).style.display = 'block';
        document.body.classList.add("overflow");
    }else{
        document.getElementById(modal).style.display = 'none'; 
        if($('.lf-modal:visible').length == 0){
            document.body.classList.remove("overflow");   
        }     
    }
}

//Resized Window
function resized_form(){
    if($('#selected_basket').length > 0){
        $('#selected_basket').slick('unslick');
        document.querySelector('.selected_wrapper').style.width = (document.querySelector('.lf-left').offsetWidth - 4)+'px';
        $('#selected_basket').slick({
            dots: false,
            infinite: true,
            speed: 300,
            slidesToShow: 2,
            adaptiveHeight: true
        });
    }
    if(!$('#suggested_container').hasClass('hide')){
        $('#suggestions').slick('unslick');
        document.getElementById('suggested_wrapper').style.width = document.querySelector('.lf-left').offsetWidth+'px';
        $('#suggestions').slick({
            dots: false,
            infinite: true,
            speed: 300,
            slidesToShow: 1,
            adaptiveHeight: true
        });
    }
}

//Fail Modal
function fail_modal(){
    $('#fails').html('');
    document.body.classList.remove("overflow");
}

//Empty Cart Return
function keep_searching(){
    window.localStorage.removeItem('cart');
    var return_url = localStorage.previous_page;
    if (typeof return_url === 'undefined') {
        return_url = window.location.href.replace('request-information','').replace('information-request','');
    }
    window.location.href = return_url;
}