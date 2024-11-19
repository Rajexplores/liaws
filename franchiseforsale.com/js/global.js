//Lazy Load
!function(t,e){"object"==typeof exports?module.exports=e(t):"function"==typeof define&&define.amd?define([],e):t.LazyLoad=e(t)}("undefined"!=typeof global?global:this.window||this.global,function(t){"use strict";function e(t,e){this.settings=s(r,e||{}),this.images=t||document.querySelectorAll(this.settings.selector),this.observer=null,this.init()}"function"==typeof define&&define.amd&&(t=window);const r={src:"data-src",srcset:"data-srcset",selector:".lazyload",root:null,rootMargin:"0px",threshold:0},s=function(){let t={},e=!1,r=0,o=arguments.length;"[object Boolean]"===Object.prototype.toString.call(arguments[0])&&(e=arguments[0],r++);for(;r<o;r++)!function(r){for(let o in r)Object.prototype.hasOwnProperty.call(r,o)&&(e&&"[object Object]"===Object.prototype.toString.call(r[o])?t[o]=s(!0,t[o],r[o]):t[o]=r[o])}(arguments[r]);return t};if(e.prototype={init:function(){if(!t.IntersectionObserver)return void this.loadImages();let e=this,r={root:this.settings.root,rootMargin:this.settings.rootMargin,threshold:[this.settings.threshold]};this.observer=new IntersectionObserver(function(t){Array.prototype.forEach.call(t,function(t){if(t.isIntersecting){e.observer.unobserve(t.target);let r=t.target.getAttribute(e.settings.src),s=t.target.getAttribute(e.settings.srcset);"img"===t.target.tagName.toLowerCase()?(r&&(t.target.src=r),s&&(t.target.srcset=s)):t.target.style.backgroundImage="url("+r+")"}})},r),Array.prototype.forEach.call(this.images,function(t){e.observer.observe(t)})},loadAndDestroy:function(){this.settings&&(this.loadImages(),this.destroy())},loadImages:function(){if(!this.settings)return;let t=this;Array.prototype.forEach.call(this.images,function(e){let r=e.getAttribute(t.settings.src),s=e.getAttribute(t.settings.srcset);"img"===e.tagName.toLowerCase()?(r&&(e.src=r),s&&(e.srcset=s)):e.style.backgroundImage="url('"+r+"')"})},destroy:function(){this.settings&&(this.observer.disconnect(),this.settings=null)}},t.lazyload=function(t,r){return new e(t,r)},t.jQuery){const r=t.jQuery;r.fn.lazyload=function(t){return t=t||{},t.attribute=t.attribute||"data-src",new e(r.makeArray(this),t),this}}return e});
//Global Variables
var submitted_cookie_days = 1,
    cart_max = 10,
    submission_max = 21;

//Get Cookie
function getCookie(name) {
    var v = document.cookie.match('(^|;) ?' + name + '=([^;]*)(;|$)');
    return v ? v[2] : null;
}
if(getCookie('perfmax')){
    submission_max = getCookie('perfmax');
}

function setCookie(key,value){
    var date = new Date();
    date.setTime(date.getTime()+(1*24*60*60*1000));
    var expires = "; expires="+date.toGMTString();
    document.cookie = key+"="+value+expires+"; path=/";
}

function siteID(siteid) {
    if(siteid == 4){
		return 'FG';
	}
	if(siteid == 5){
		return 'FON';
	}
	if(siteid == 6 || siteid == 7){
		return 'FCN';
	}
};

//Continue to Form
function continue_form(prepend_url = '',target = 'footer') {  
    var cart = localStorage.getItem('cart');    
    if (localStorage.cart) { 
        var count = Object.keys(cart).length;
    }else{
        var count = 0;
    }
    if(count >= 1){
        ga4_event('view_cart');
        localStorage.previous_page = window.location.href; 
        var form = 'request-information';
        window.location.href = '/'+prepend_url+form;
    }
}

//Check Cookies
function check_cookies() {  
    var accept_cookies = localStorage.getItem('accept_cookies');     
    if (localStorage.accept_cookies) { 
        //Do Nothing
    }else{
        document.getElementById("valid_cookies").classList.remove("show");
    }
}
//Accept Cookies
function accept_cookies() {
  localStorage.accept_cookies = 'Yes';
  document.getElementById("valid_cookies").classList.remove("show");
}

//Filter toggle
var filter_toggle = document.getElementById('filter_toggle');
if (filter_toggle !== null) {
    filter_toggle.onclick = function(){
        document.getElementById('filter_form').classList.toggle("closed");
    };
}
var toc_toggle = document.getElementById('toc-toggle');
if (toc_toggle !== null) {
    toc_toggle.onclick = function(){
        document.getElementById('toc-slider').classList.toggle("closed");
        document.getElementById('toc-search-chevron').classList.toggle('rotate');
    };
}

//Toggle Button
function toggleMenu(){
    document.getElementById('mobile-menu').classList.toggle('hide');
    document.getElementById('menu-icon').classList.toggle('open');

}

function toggleSubMenu(submenuClass){
    document.querySelector('.'+submenuClass+' ul').classList.toggle('is-active');
    document.querySelector('.'+submenuClass).classList.toggle('rotate180');
}


function close_menu(){
    document.getElementById("modal_open").classList.remove("show");
    document.body.classList.remove("opened");
    document.getElementById('menuToggle').classList.remove("open");
    for (var i = 0; i < submenus.length; i++) {
        submenus[i].classList.remove("show");
    }
}

//Filter Change
function filter_action(event = null,prepend_url=''){
    if(event != null){
        ga4_datalayer('fv_'+event+'_select');
    }
    if(prepend_url != ''){
        if(!prepend_url.startsWith('/')){
            prepend_url = '/'+prepend_url;
        }
        if(prepend_url.endsWith('/')){
            prepend_url = prepend_url.slice(0, -1);
        }
    }
    if (document.getElementById("filter")) {
        var subdirectory = location.pathname.split('/'),
            state_id = document.getElementById('state'),
            //Change from category to category and added .value BB 11/17/2022
            category = document.getElementById('category').value,
            current_state = document.getElementById('current_state'),
            investment_id = document.getElementById('investment_filter'),
            state = state_id.options[state_id.selectedIndex].text.replace(/\s+/g, '-').toLowerCase(),
            // category = category_id.options[category_id.selectedIndex].text.replace(/[&\',']/g, '').replace(/\s+/g, '-').toLowerCase(),
            investment = 'all-investment-amounts',
            path = '';
            if (document.getElementById('investment_filter')){
                investment = investment_id.value;
            }
            //Commented out to fix issue BB 11/17/2022
            // console.log(category);
            // if(category == 'green-franchises'){
            //     category = 'green-eco-friendly-franchises';
            // }else if(category == 'education-franchises'){
            //     category = 'education-training-franchises';
            // }else if(category == 'sports-recreation-franchises'){
            //     category = 'recreation-sports-franchises';
            // }else if(category == 'manufacturing-franchises'){
            //     category = 'manufacturing';
            // }else if(category == 'All Industries'){
            //     category = 'all-industries';
            // }
            if(category == 'All Industries'){
                category = 'all-industries';
            }
        current_state.innerHTML = state_id.options[state_id.selectedIndex].text;
        if(category == 'all-industries' || category == '----------------'){
            path = prepend_url+'/state/'+state+'-franchises';
            if(investment != 'all-investment-amounts'){
                path = prepend_url+'/industry/'+category+'/state/'+state+'/investment/'+investment;
            }
        }else{  
            path = prepend_url+'/industry/'+category;
            if(investment != 'all-investment-amounts'){
                path += '/state/'+state+'/investment/'+investment;
            }else{
                path += '?state='+state_id.value;
            }
        }
        document.getElementById('filter_form').action = path;
    }
}

function filter_action_affordable(){
    if (document.getElementById("filter")) {
        var state_id = document.getElementById('state'),
            category_id = document.getElementById('category'),
            current_state = document.getElementById('current_state'),
            investment_id = document.getElementById('investment_filter'),
            state = state_id.options[state_id.selectedIndex].text.replace(/\s+/g, '-').toLowerCase(),
            category = category_id.options[category_id.selectedIndex].text.replace(/[&\',']/g, '').replace(/\s+/g, '-').toLowerCase(),
            investment = investment_id.value,
            path = '';
        current_state.innerHTML = state_id.options[state_id.selectedIndex].text;
        // console.log(category);
        if(category == 'all-categories' || category == 'all-industries' || category == '----------------'){
            path = '/afforable-business-franchises.html?state='+state_id.value;
            if(investment != 'all-investment-amounts'){
                path += '&investment_filter='+investment;
            }
        }else{
            path = '/afforable-business-franchises.html?cat_name=';

            if(category == 'food-restaurant'){
                category = 'food-and-restaurant';
            }
            if(category == 'computers-internet' || category == 'computer-ecommerce-internet'){
                category = 'computers-and-internet';
            }
            path += category+'&state='+state_id.value;
            if(investment != 'all-investment-amounts'){
                path += '&investment_filter='+investment;
            }
        }
        document.getElementById('filter_form').action = path;
    }
}

//Filter Redirect
function filter_go(){
    ga4_datalayer('fv_filter_search_results');
    var redirect = document.getElementById('filter_form').action;
    location.href = redirect;
}

//Toggle Investment
function toggleInvestment() {
  // Get the checkbox
  var checkBox = document.getElementById("investment_toggle"),display,required;
  if (checkBox.checked == true){
    display = "none";
    required = false;
  } else {
    display = "block";
    required = true;
  }
     document.getElementById("investment_amount").required = required; 
     document.getElementById("investment_amount").style.display = display;
}

//Submitted Listings
function submitted(submissions){
    var submitted_count = getCookie('submitted_count'),total;
    if (typeof submissions !== 'undefined') {
        if (submitted_count) { 
            total = parseInt(submitted_count)+parseInt(submissions);
        }else{
            total = parseInt(submissions);
        }
        var d = new Date();
        d.setTime(d.getTime() + (submitted_cookie_days*24*60*60*1000));
        var expires = "expires="+ d.toUTCString();
        document.cookie = "submitted_count=" + total + ";" + expires + ";path=/";
    }
}

//Update Cart Count
function updateTotal(){
    var load_cart = JSON.parse(localStorage.getItem('cart')),
        total = document.getElementsByClassName("cart_count"),
        bottom = document.getElementById('bottom'),
        bottomTxt = document.getElementById('bottom-text'),
        bottomCart = document.getElementById('cart_bottom');     
        // console.log(load_cart);
    if (localStorage.cart) { 
        var count = Object.keys(load_cart).length;
    }else{
        var count = 0;
    }
    setCookie('cart_count',count);
    for (i = 0; i < total.length; i++) {
        total[i].innerHTML = count;
    }
    bottom.classList.add('pd-btm-60');
    if(count >= 1){
        document.getElementById('cart').classList.add('fill');  
        if(count >= 2){
           bottom.className = "show plural";
        }else{
           bottom.className = "show";
        } 
        bottomTxt.classList.add('hide');
        bottomCart.classList.remove('hide');
    }else{    
        // document.getElementById('lower').style.paddingBottom = 'initial';   
        document.getElementById('cart').classList.remove('fill'); 
       bottom.className = ""; 
       bottom.classList.remove('pd-0');
       if (bottomTxt.classList.contains('no-add-basket')) {
            bottomTxt.classList.add('hide');
            bottom.classList.add('pd-0');
            bottom.classList.remove('pd-btm-60');
       }
       bottomCart.classList.add('hide');
    }
}

//Scroll to Top
function toTop(){
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

//Store in Cart
function cartStorage(fboid,min_capital,name,type,rate = 0,position = ''){
    var temp_name = null;
    if(type == 'add'){
        temp_name = name.replace(/[^a-zA-Z ]/g,"").replace(" ","+");
    }
    var xmlHttp = new XMLHttpRequest(),
        storage_url = '/cart.json?fbo_id='+fboid+'&name='+temp_name+'&rate='+rate+'&min_capital='+min_capital+'&type='+type+'&position='+position,
        loop = ["url", "type"];
    loop.forEach(function(item) {
        var variable = window['add_page_'+item];
        if (typeof(variable) != 'undefined' && variable != null){
            storage_url += '&page_'+item+'='+variable;
        }
    });
    // console.log(storage_url);
    xmlHttp.open('GET', storage_url);
    xmlHttp.onload = function() {
        //console.log(data);
        if (xmlHttp.status === 200) {
            // var data = JSON.parse(xmlHttp.responseText);
            var purchase_event = 'add_to_cart';
            if(type == 'delete'){
                purchase_event = 'remove_from_cart';
                dimension = 'remove_item_from_cart';
                label = 'removed from';
            }
            ga4_event(purchase_event,fboid);
        }else{
            console.log('Request failed.  Returned status of ' + xmlHttp.status);
        }
    }
    xmlHttp.send( null );
    return xmlHttp.responseText;
}

//Add to Cart
function addCart(fboid,franName,investment,id,rate,position){
    var key = 'ID'+fboid;
    if (localStorage.cart) {
        var new_cart = JSON.parse(localStorage.getItem('cart'));
        new_cart[key] = {['name']:franName,['investment']:investment,['id']:id,['rate']:rate,['position']:position};
    }else{
        var new_cart = {[key]:{['name']:franName,['investment']:investment,['id']:id,['rate']:rate,['position']:position}};
    }
    window.localStorage.setItem('cart', JSON.stringify(new_cart));
    cartStorage(fboid,investment,franName,'add',rate,position);
    updateTotal();  
}
//Remove from Cart
function removeCart(fboid){
    var key = 'ID'+fboid,
        new_cart = JSON.parse(localStorage.getItem('cart'));
    delete new_cart[key];
    window.localStorage.setItem('cart', JSON.stringify(new_cart));
    cartStorage(fboid,null,null,'delete');
    updateTotal();
}
//Add/Remove from Cart
function checkCart(fboid,franName,investment,id, isProfile = false,rate,position = ''){
    var checker = isProfile ? document.querySelector('.checkbox_'+fboid) : document.getElementById('checkbox_'+fboid),
        total = document.getElementById('total').innerHTML,
        submitted_count = getCookie('submitted_count');  
    if (checker === null){
        removeCart(fboid); 
    }else{
        if(checker.checked == true){
            removeCart(fboid);
            if(isProfile){
                document.querySelectorAll('.checkbox_'+fboid).forEach(el => el.checked = false);
            }else{
                //document.getElementById("profile").classList.remove("checked");
                checker.checked = false;
            }
            
        }else{
            var submitted_total_count = parseInt(total)+parseInt(submitted_count);
            if (parseInt(submitted_total_count) >= submission_max) { 
                document.getElementById('limit_number').innerHTML = submitted_total_count+" businesses"; 
                document.getElementById("limit").style.display = 'block';
            }else if(total >= cart_max){
                document.getElementById('max_number').innerHTML = cart_max+" businesses"; 
                document.body.classList.add("overflow");
                document.getElementById("max").style.display = 'block';
            }else{
                addCart(fboid,franName,investment,id,rate,position);
                if(isProfile){
                    document.querySelectorAll('.checkbox_'+fboid).forEach(el => el.checked = true);
                }else{
                    //document.getElementById("profile").classList.add("checked");
                    checker.checked = true;
                }   
            }   
        }
    }
};

//Modal
function modal(modal){
    document.body.classList.add("overflow");
    document.getElementById(modal).style.display = 'block';
};

function closeModal(modal){
    if(modal.length){
        if(document.getElementById(modal).style.display == 'block'){
            document.body.classList.remove("overflow");
            document.getElementById(modal).style.display = 'none';
        }
    }else{
        document.body.classList.remove("overflow");
        var modals = document.getElementsByClassName("modal");
        for (i = 0; i < modals.length; i++) {
            modals[i].style.display = 'none';
        }        
    }
};

//Check if in Cart
function loadCart(){
    if (localStorage.cart) {   
        // console.log('called');
        var load_cart = JSON.parse(localStorage.getItem('cart'));
        Object.keys(load_cart).forEach(function(key) {
            var fboid = key.replace("ID", ""),
                checker = document.getElementById('checkbox_'+fboid),
                profile_checker = document.querySelectorAll('.checkbox_'+fboid);
            if (typeof(checker) != 'undefined' && checker != null){
                checker.checked = true;
            }
            if (typeof(profile_checker) != 'undefined' && profile_checker != null){
                document.querySelectorAll('.checkbox_'+fboid).forEach(el => el.checked = true);
            }
        })
        
    }
    updateTotal();
}

//Cookie Variables
const global_cookie = cookie_var();

window.addEventListener('load', function() {
    //Check Cookies
    check_cookies();
    //Load Cart
    loadCart();
    //Lazy Load
    lazyload();
     
    window.addEventListener("scroll", loadCart); 
});

if (window.history && window.history.pushState) {
    window.addEventListener('popstate', function() {
        loadCart();
    });
}

// Send Request

// added based on a comment, this will also hide any menu when clicked anywhere else
document.addEventListener("click", function () {
  var last = document.querySelector('#mobile-menu .show');
  if (last) last.classList.remove("show");
});

// var bottom1 = document.getElementById("bottom");
// if(document.getElementById("bottom") && bottom1.classList.contains('show')){
//     document.querySelector('footer#lower').style.padding = '60px';
// }

// document.querySelector('footer#lower').style.padding = '60px';

function toggleSitemap(id){
    var coll = document.getElementById(id).style.display;
    if(coll == 'block'){
        document.getElementById(id).style.display = 'none';
    }else{
        document.getElementById(id).style.display = 'block';
    }
}

function toggleFilter(){
    var coll = document.getElementById('filters').style.display;
    var icon = document.querySelector('#filter_toggle i');
    if(coll == 'flex'){
        document.getElementById('filters').style.display = 'none';
        icon.classList.remove("fa-chevron-up");
        icon.classList.add("fa-chevron-down");
    }else{
        document.getElementById('filters').style.display = 'flex';
        icon.classList.remove("fa-chevron-down");
        icon.classList.add("fa-chevron-up");
    }
} 

function toggleFilterExpand(){
    var coll = document.getElementById('dirFilter');
    if(coll.classList.contains('hide')){
        coll.classList.remove('hide');
        document.getElementById('filter_expand').innerHTML = '<i class="fa fa-chevron-up" aria-hidden="true"></i>';
    }else{
        coll.classList.add('hide');
        document.getElementById('filter_expand').innerHTML = '<i class="fa fa-chevron-down" aria-hidden="true"></i>';
    }
} 

function isInViewport(element) {
    const rect = element.getBoundingClientRect();
    return (
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
        rect.right <= (window.innerWidth || document.documentElement.clientWidth)
    );
}

function toggleFacts(){
    document.getElementById('stateFacts').classList.toggle('hide');
}

function toggleBanner(cls, section=null){
    if(cls != null){
        console.log(cls);
        document.querySelector('.paragraphWrapper').classList.toggle(cls);
    }
    if(document.querySelector('.readMoreToggleMedUp')){
        document.querySelector('.readMoreToggleMedUp').classList.toggle('hide');
    }
    if(document.getElementById('readMoreTopMore')){
        document.getElementById('readMoreTopMore').classList.toggle('hide');
    }
    if(section == 'hot-and-trendy-franchises'){
        document.querySelector('.introBack').classList.toggle('w-100')        
    }
    if(section == 'top-franchises'){
        document.getElementById('top50show').classList.toggle('hide');
    }
    
    document.getElementById('banner').classList.toggle('banner-bg-black');
}

function toggleCls(){
    document.querySelector('.subCatList').classList.toggle('hide');
    if(document.querySelector('.l2Link-span').textContent == 'Show'){
        document.querySelector('.l2Link-span').textContent = 'Hide';
    }else if((document.querySelector('.l2Link-span').textContent == 'Hide')){
        document.querySelector('.l2Link-span').textContent = 'Show';
    }
}

function toggleHidden(id, cls, type=''){
    console.log(id+'-'+cls+'-'+type);
    if(cls == '' || cls == null){
        var cls = 'hide';
    }
    document.getElementById(id).classList.toggle(cls);

    if(id == 'bannerDesc' || id == 'sub-text'){
        if (document.getElementById('readMoreTop')) {
            document.getElementById('readMoreTop').classList.toggle('hide');
        }

        if (document.getElementById('readMoreTopMore')) {
            document.getElementById('readMoreTopMore').classList.toggle('hide');
        }

        if (type == 1) {
            document.getElementById('bannerDesc').classList.remove('hide');
        }
        
        document.getElementById('hideSubText').classList.toggle('hide');
    } 

    if (document.getElementById(id).classList.contains(cls)) {
        document.querySelector('.main-results #banner').style.backgroundImage = '';
    }else{
        document.querySelector('.main-results #banner').style.backgroundImage = 'none';
    }
    
}



var coll = document.getElementsByClassName("collapsible");
var ci;

for (ci = 0; ci < coll.length; ci++) {
    coll[ci].addEventListener("click", function() {
        if(this.classList.contains("li-active")){
            this.classList.remove("li-active");
            var content = this.nextElementSibling;
            content.classList.remove('show');
        }else{
            var cj;
            for (cj = 0; cj < coll.length; cj++) {
                coll[cj].classList.remove("li-active");
                let content1 = coll[cj].nextElementSibling;
                content1.classList.remove('show');
            }

            this.classList.add("li-active");
            var content = this.nextElementSibling;
            content.classList.add('show');
        }    
    });
}

function toggleReviseSearch(){
    console.log('toggle');
    document.getElementById('dirFilter').classList.toggle('hide-for-small');
}

function carouselVideoClick(tabName, clsName) {
    var i, x, tablinks;
    var cls = document.querySelector('.'+clsName);
    x = cls.getElementsByClassName("tabs-panel");
    for (i = 0; i < x.length; i++) {
      x[i].classList.remove('show');
    }
    document.getElementById('tabVideo').click();
    tablinks = cls.getElementsByClassName("tabs-title");
    for (i = 0; i < x.length; i++) {
      tablinks[i].className = tablinks[i].className.replace(" is-active", "");
    }
    document.getElementById(tabName).classList.add('show');
    document.getElementById('tabVideo').className += " is-active";

    document.getElementById('tabVideo').scrollIntoView();    
  }


function togglePhotoCredit(tag1){
    tag1.nextElementSibling.classList.toggle('hide');
}

function toggleProfileViewFacts(){
    var qFacts = document.querySelector('#profile .quickFacts .qFWrapper');
    qFacts.classList.add('full');
    document.getElementById('viewQuickFacts').classList.add('hide');
}

function setProfileVideo(url,alt=''){
    // console.log(url);
    document.querySelector('.video-display-block').classList.add('show');
    var elm = '';
    if (url.includes('youtube')) {
        url = url.replace("watch?v=", "embed/");
        elm = '<iframe width="100%" height="410px" src="'+url+'" frameborder="0" allowfullscreen></iframe>';
    }else{
        elm = '<video preload="none" id="video_ctrl" src="'+url+'" alt="'+alt+'" controls="controls" autoplay></video>';
    }
    // document.getElementById("video_ctrl").src= url;
    // document.getElementById("video_ctrl").setAttribute('alt', alt);
    document.getElementById('videoElement').innerHTML = elm;
    document.getElementById("videoDisplayLink").href= url;
}
 
backToTopId = document.getElementById("backToTop");

var myScrollFunc = function() {
  var y = window.scrollY;
  if (y >= 500) {
    backToTopId.classList.add('active');
  } else {
    backToTopId.classList.remove('active');
  }
};

if(backToTopId){
    window.addEventListener("scroll", myScrollFunc);
}


function topFunction() {
    document.body.scrollTop = 0; // For Safari
    document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
}

if(window.location.search){
    // console.log(window.location.search);
    if(window.location.search == '?interest-ads'){
        toggleHidden('fullPolicy',null);
        var elmnt = document.getElementById("interest-ads");
        elmnt.scrollIntoView();
        document.getElementById('interest-ads').style.border = 'solid #f26721 2px';
        document.getElementById('interest-ads').style.marginBottom = '1rem';
        document.getElementById('interest-ads').style.padding = '.9375rem';
    }
}

var lowCostBlock = document.getElementById('low-cost-facts');
if(lowCostBlock != null){
    var lowCostColl = lowCostBlock.querySelectorAll(".collapsible");
    var l;
    
    for (l = 0; l < lowCostColl.length; l++) {
      lowCostColl[l].addEventListener("click", function() {
        this.classList.toggle("active");
        var content = this.nextElementSibling;
        if (content.style.maxHeight){
          content.style.maxHeight = null;
        } else {
          content.style.maxHeight = content.scrollHeight + "px";
        }
      });
    }
}

// Name Search

function nameSearch(){
    var nameText = document.getElementById('txtSearch').value;
    var url = '/name/'+nameText;
    document.getElementById('nameSearchForm').action = url;
}

// Set smart banner cookie
function setSmartBannerCookie(){
    var date = new Date();
    date.setTime(date.getTime()+(1*24*60*60*1000));
    var expires = "; expires="+date.toGMTString();
    document.cookie = "smartbanner=true"+expires+"; path=/";
}

function getMobileOperatingSystem() {
    var userAgent = navigator.userAgent || navigator.vendor || window.opera;
  
    if (/windows phone/i.test(userAgent)) {
        return "Windows Phone";
    }

    if (/android/i.test(userAgent)) {
        return "Android";
    }

    if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
        return "iOS";
    }

    return "unknown";
}

function toggleSmartBanner(){
    return;
    var myElem = document.getElementById('myElementId');
    if (myElem != null){
        var os = getMobileOperatingSystem();
        // console.log(os);
    
        var smartBanner = getCookie('smartbanner') ? getCookie('smartbanner') : false;
    
        if (!smartBanner) {
            if(screen.width < 768){
                if(os == 'Android'){
                    document.getElementById('download-app').style.display = 'block';
                    document.getElementById('main').classList.add('padding-tp-65');
                }
            }
        }
    };
}


function closeSmartBanner(){
    document.getElementById('download-app').style.display = 'none';
    document.getElementById('main').classList.remove('padding-tp-65');
    setSmartBannerCookie();
}

window.addEventListener('DOMContentLoaded', function() {
    toggleSmartBanner();
});

const validateEmail = (email) => {
    return String(email)
        .toLowerCase()
        .match(
        /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
        );
};

//View Profile Modal
function view_profile(data_fboname,sent,button_text){
    var profile = '',
    requested = sent;
    var profile_type = '';
    console.log(data_fboname);
    var options = ['franchise','franchises','financing','opportunities','licenses','services','consultants','dealerships','distributorships'];
    for (let i = 0; i < options.length; i++) {
        const element = options[i];
        if (data_fboname.indexOf(element) !== -1) {
            data_fboname = (data_fboname.replace('/'+element+'/','')).replace(/\/$/, "");
            profile_type = element;
        }
    }
    if (profile_type != '') {
        profile_type = '-'+profile_type;
    }
    console.log('/profile-'+data_fboname+profile_type+'.json');
    document.getElementById('loading').classList.add('wait'); 
    xhr = new XMLHttpRequest();
    xhr.open('GET', '/profile-'+data_fboname+profile_type+'.json');
    xhr.onload = function() { 
        if (xhr.status === 200) {
            var data = JSON.parse(xhr.responseText);
            profile = data['data'];
            var fboid = data['id'];
            var checker = document.getElementById('checkbox_'+fboid)
            document.getElementById('profile_content').innerHTML = profile;
            document.body.classList.add("overflow");
            document.getElementById('loading').classList.remove('wait'); 
            document.getElementById("profile_view").style.display = "block";
            if (checker.checked == true) {
                document.querySelectorAll('.checkbox_'+fboid).forEach(el => el.checked = true);
            }
            // wl_trackModalProfile(data_fboid,fran_name.toLowerCase(),siteID(id));
        }
        else {
            console.log('Request failed.  Returned status of ' + xhr.status);
        }
    };
    xhr.send();
}

//Close Modal Buttons
var closeButtons=document.getElementsByClassName('close_button');  
for (i = 0; i < closeButtons.length; i++) {
    closeButtons[i].addEventListener("click", closeModal);
}

//Toggle Read More
function toggleMore(target){
    var read_more = document.getElementById("read_more"+target),
    more_text = document.getElementById("more_text"+target);
    read_more.classList.add("toggled");
    more_text.classList.add("toggled");
}

// For profile tabs
function netWorthTab(evt, tabName, clsName) {
    var i, x, tablinks;
    var cls = document.querySelector('.'+clsName);
    x = cls.getElementsByClassName("tabs-panel");
    for (i = 0; i < x.length; i++) {
      x[i].classList.remove('show');
    }
    tablinks = cls.getElementsByClassName("tabs-title");
    for (i = 0; i < x.length; i++) {
      tablinks[i].className = tablinks[i].className.replace(" is-active", "");
    }
    if(tabName != 'video'){
      if(document.querySelector('video')){
        document.querySelector('video').pause();
      }
   }
    document.getElementById(tabName).classList.add('show');
    evt.currentTarget.className += " is-active";
}
function toggle_filter() {
  var toggle_filter = document.getElementById("toggle_filter"), toggle_target = document.getElementById("toggle_target");
  if(toggle_filter){
    toggle_filter.classList.toggle("open");
    toggle_target.classList.toggle("mobile_close");
  }
}

window.addEventListener('DOMContentLoaded', function() {
    if (window.screen.width < 768) {
        toggle_filter();
    }
});

//Newsletter SignUp
function newsletter_signup(){
    ga4_datalayer('fv_newsletter_signup');
}

//GA4 DataLayer Push
function ga4_datalayer(event,fbo_id = null,target = null,type = null){
    window.dataLayer = window.dataLayer || [];
    var push = '"event": "'+event+'", "session_id": "'+global_cookie.udid+'"';
    if(fbo_id != null){
        push += ',"fbo_id" : '+fbo_id;
    }
    if(target != null){
        push += ',"click_location" : "'+target+'"';
    }
    if(type != null){
        push += ',"submit_type" : "'+type+'"';
        push += ',"email" : "'+global_cookie.email+'"';
    }
    var final = JSON.parse('{'+push+'}');
    window.dataLayer.push(final);
}

//Google Events (single item)
function ga4_single(event,fbo_id,name,id,rate){
    var value = Number(rate).toFixed(2),
        unit_mapping = {'4':'FG','5':'FON','6':'FCN','7':'FCN'},
        affliation = id;
    if(typeof affliation == 'number'){
        affliation = unit_mapping[id];
    }
    window.dataLayer = window.dataLayer || [];
    window.dataLayer.push({ ecommerce: null });  // Clear the previous ecommerce object.
    window.dataLayer.push({
        event: event,
        ecommerce: {
            currency: "USD",
            value: value,
            items: [
                {
                    item_id: fbo_id,
                    item_name: name,
                    item_category: global_cookie.landing_id,
                    affiliation: affliation,
                    price: value,
                    quantity: 1
                }
            ]
        }
    });
}

//Google Events (all items)
function ga4_event(event,fbo_id = null){
    var cart_items =  JSON.parse(localStorage.getItem('cart')),items =[],sum = 0,
        unit_mapping = {'4':'FG','5':'FON','6':'FCN','7':'FCN'};
        // console.log(cart_items);
    if(cart_items == null || cart_items == 'null'){
        return;
    }else if (Object.keys(cart_items).length === 0 && cart_items.constructor === Object) { 
        // Empty Cart
    }else{
        Object.keys(cart_items).forEach(function(key) {
            var fboid = key.replace("ID", "");
            var temp_item = '';
            if(fbo_id == null || (fbo_id != null && fbo_id == fboid)){
                var affliation = cart_items[key]['id'],price = 0.00;
                if(typeof affliation == 'number'){
                    affliation = unit_mapping[affliation];
                }
                sum += cart_items[key]['rate'];
                temp_item += '"item_id": "'+fboid+'",';
                temp_item += '"item_name": "'+cart_items[key]['name']+'",';
                temp_item += '"item_category": "'+global_cookie.landing_id+'",';
                if(event == 'view_cart' || event == 'begin_checkout'){
                    if(localStorage.previous_page){
                        var item_category2 = localStorage.previous_page.replace(/^.*\/\/[^\/]+/, '')
                        temp_item += '"item_category2": "'+item_category2+'",';
                    }
                    if(event == 'begin_checkout'){
                        var item_category3 = window.location.href.replace(/^.*\/\/[^\/]+/, '')
                        temp_item += '"item_category3": "'+item_category3+'",';
                    }
                }
                temp_item += '"affiliation": "'+affliation+'",';
                if(cart_items[key]['rate'] !== undefined && cart_items[key]['rate'] != ''){
                    price = Number(cart_items[key]['rate']).toFixed(2);
                }
                temp_item += '"price": '+price+',';
                temp_item += '"quantity": 1';
                items.push('{'+temp_item+'}');
            }
        });
        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push({ ecommerce: null });  // Clear the previous ecommerce object.
        window.dataLayer.push({
            event: event,
            ecommerce: {
                currency: 'USD',
                value: Number(sum).toFixed(2),
                items: JSON.parse('['+items+']')
            }
        });
    }
}

//Load Listings Data
function footerResults(start = 0,limit = 96,category = '') {   
    var xhr1 = new XMLHttpRequest(),
        url = '/concepts.json?p='+limit,
        results = '';
    if(category != ''){
        url += '&c='+category;
    }
    xhr1.open('GET', url);
    xhr1.onload = function() {
        if (xhr1.status === 200) {
            var footerObj = JSON.parse(xhr1.responseText);
            var footerArr = footerObj.data;
            if (footerArr.length != 0) {
                var i = 0;
                footerArr.forEach(value => {
                    if(i >= start && i < limit){
                        results += footerListing(value,i);
                    }
                    i++;
                });
            }
            var h2_check = document.getElementById('footer_title_h2');
            if(h2_check){
                document.getElementById('results').innerHTML += results;    
                let site_footer_listings = document.getElementById('site_footer_listings');
                if (typeof(site_footer_listings) != 'undefined' && site_footer_listings != null){
                    site_footer_listings.classList.add('ready');
                }
                if(start == 0){
                    footerTitle(category,footerObj.state);
                    document.getElementById('footer_results').classList.remove('hide');
                }
                loadCart();
                document.getElementById('loading').classList.remove('wait');  
            }
        }else{
            console.log('Request failed.  Returned status of ' + xhr2.status);
        }
    };
    xhr1.send();  
}

//Return Footer Listings
function footerListing(value,i){
    var listing = '';
    listing += '<div class="result-item listing" id="listing_'+value.fbo_id+'" data-invest="'+footerPad(value.investment, 8)+'" data-name="'+value.name+'" data-id="'+value.site_id+'" data-fbo="'+value.fbo_id+'" data-order="'+footerPad(i, 4)+'">';
    listing += '<div class="item" id="result_'+value.fbo_id+'">';
    listing += '<div class="category"><span>'+value.category_name+'</span></div>';
    listing += '<div class="result-img"><img class="concept-logo lazyload" src="'+value.image_url+'" alt="'+value.name+'" title="'+value.name+'"></div>';
    listing += '<h3 class="text-center">'+value.name+'</h3>';
    listing += '<p>'+value.ShortDesc+'</p>';
    listing += '<div class="cash-block"><h4>Cash Required: $'+Number(value.investment).toLocaleString()+'</h4></div>';
    listing += '<div class="result-checkbox">';
    listing += '<input type="checkbox" class="temp-checkbox" value="'+value.fbo_id+'" id="checkbox_'+value.fbo_id+'">';
    listing += '<button class="basket" onclick="checkCart('+value.fbo_id+',\''+value.name+'\','+value.investment+',\''+siteID(value.site_id)+'\',false,'+value.rate+');"><span>Get free info</span></button>';
    listing += '</div>';
    listing += '</div>';
    listing += '</div>';
    return listing;
}

//Return Remaining Results
function footerLoadMore(start = 12,category=''){
    document.getElementById('waiting').classList.add('wait');  
    document.getElementById('footer_results').classList.add('hide');
    footerResults(start,999,category);
}

//Return Footer Title
function footerTitle(category = '',state = ''){
    var h2_div = document.getElementById('footer_title_h2');
    if(h2_div){
        var categories = {1:'Automotive',4:'Business Opportunities',3:'Business Services',5:'Child-Related',6:'Cleaning',7:'Computers & Internet',8:'Education',9:'Financial Services',11:'Food & Restaurant',10:'Health & Fitness',16:'Healthcare & Senior Care',12:'Home Services',17:'Home-Based',21:'Low Cost',2:'Personal Care',25:'Mobile',13:'Pet Care',24:'Printing, Copy, Shipping',20:'Real Estate',14:'Retail',18:'Sports and Recreation',19:'Staffing and Personnel',15:'Travel & Lodging',22:'Vending Machine',23:'Veteran\'s',28:'Work from Home'},
            states = {'al':'Alabama','ak':'Alaska','az':'Arizona','ar':'Arkansas','ca':'California','co':'Colorado','ct':'Connecticut','de':'Delaware','dc':'District of Columbia','fl':'Florida','ga':'Georgia','hi':'Hawaii','id':'Idaho','il':'Illinois','in':'Indiana','ia':'Iowa','ks':'Kansas','ky':'Kentucky','la':'Louisiana','me':'Maine','md':'Maryland','ma':'Massachusetts','mi':'Michigan','mn':'Minnesota','ms':'Mississippi','mo':'Missouri','mt':'Montana','ne':'Nebraska','nv':'Nevada','nh':'New Hampshire','nj':'New Jersey','nm':'New Mexico','ny':'New York','nc':'North Carolina','nd':'North Dakota','oh':'Ohio','ok':'Oklahoma','or':'Oregon','pa':'Pennsylvania','ri':'Rhode Island','sc':'South Carolina','sd':'South Dakota','tn':'Tennessee','tx':'Texas','ut':'Utah','vt':'Vermont','va':'Virginia','wa':'Washington','wv':'West Virginia','wi':'Wisconsin','wy':'Wyoming'},
            h2 = 'Browse ';
        if(category != ''){
            h2 += '<!--googleoff: index-->'+categories[category]+'<!--googleon: index--> ';
        }
        h2 += 'Franchises ';
         if(state != ''){
            h2 += '<!--googleoff: index-->in '+states[state]+'<!--googleon: index-->';
        }
        h2_div.innerHTML = h2;
    }
}

//Return Numbers with Lead Zero
function footerPad(num, size) {
    return ('000000000' + num).substr(-size);
}


var fly_time;
//Inactivity Checker
var inactivityTime = function () {
    window.onload = resetTimer;
    // DOM Events
    document.onload = resetTimer;
    document.onmousemove = resetTimer;
    document.onmousedown = resetTimer; // touchscreen presses
    document.ontouchstart = resetTimer;
    document.onclick = resetTimer;     // touchpad clicks
    document.onkeydown = resetTimer;   // onkeypress is deprectaed
    document.addEventListener('scroll', resetTimer, true); // improved; see comments

    function fly_modal() {
        toggle_fly_modal('fly_modal','show');
    }

    function resetTimer() {
        clearTimeout(fly_time);
        if(getCookie('fff_quiz_modal')==1){
            return;
        }
        fly_time = setTimeout(fly_modal, 10000)
    }
};

if(window.location.pathname=='/great-franchises-q'){
    window.onload = function() {
        inactivityTime();
    }
}

function toggle_fly_modal(modal,type = 'hide',cookie=false){
    if(getCookie('fff_quiz_modal')==1){
        return;
    }
    if(type == 'show'){
        document.getElementById(modal).style.display = 'block';
        document.body.classList.add("overflow");
    }else{
        clearTimeout(fly_time);
        if(cookie==true){
            var date = new Date();
            date.setDate(date.getDate() + 1);
            var expires = "; expires="+date.toGMTString();
            document.cookie = "fff_quiz_modal"+"=1"+expires+"; path=/";
        }
        document.getElementById(modal).style.display = 'none'; 
        if(document.querySelector('#fly_modal').length == 0){
            document.body.classList.remove("overflow");   
        }     
    }
}

document.addEventListener('mouseleave', function(event) {
    if(window.location.pathname=='/great-franchises-q'){
        toggle_fly_modal('fly_modal','show');
    }
}, false);

// Cookie Variables
function cookie_var(){
    let cookie_json = new XMLHttpRequest();
    cookie_json.open('get','/cookie.json',false);
    cookie_json.send(null);
    return JSON.parse(cookie_json.responseText);          
}

// Sitemap Toggles
function toggleSitemap(id){
    var coll = document.getElementById(id).classList.contains('open');
    if(coll){
        document.getElementById(id).classList.remove('open');  
    }else{
        document.getElementById(id).classList.add('open');  
    }
}

//Submit Subscription
function subscription(){
    let phone = document.querySelector('#subscribe-form #newsletter_phone').value,
        all_required = true;
    document.getElementById('subscribe-form').querySelectorAll('[required]').forEach(function(i) {
        if (!all_required) return;
        if (!i.value) { 
            all_required = false;  
            return; 
        }
    });
    if((phone === '' || phone === null) && all_required){
        var action = document.getElementById('subscribe-form').getAttribute('data-action');
        document.getElementById('subscribe-form').action = action;
        document.getElementById('waiting').classList.add('wait');  
        document.getElementById('subscribe-form').submit();
    }else{
        mini_modal('Your email address is required in order to receive your <strong>Free Information</strong>.');
    }
}

// Mini Modals
function mini_modal(text,title = 'We&apos;re Sorry',button = 'Okay'){
    document.querySelector('#mini_modal button').innerHTML = button;
    document.getElementById('cartFullTitleMax').innerHTML = title;
    document.getElementById('mini_modal_content').innerHTML = text;  
    document.body.classList.add("overflow");
    document.getElementById('mini_modal').style.display = 'block';
}

function addListenerMulti(element, eventNames, listener) {
    var events = eventNames.split(' ');
    for (var i=0, iLen=events.length; i<iLen; i++) {
        element.addEventListener(events[i], listener, false);
    }
}