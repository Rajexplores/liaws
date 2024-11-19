

<footer class="footer">
    <div class="">
        <div class="row">
            <div class="col-md-4">
                <ul class="no-bullet bold">
                    <li><a href="https://www.franchiseopportunities.com" target="_blank">FranchiseOpportunities.com</a></li>
                    <li><a href="https://www.franchiseforsale.com" target="_blank">FranchiseForSale.com</a></li>
                    <li><a href="https://www.foodfranchise.com" target="_blank">FoodFranchise.com</a></li>
                    <li><a href="https://www.businessbroker.net" target="_blank">BusinessBroker.net</a></li>
                </ul>
            </div>
            <div class="col-md-3 col-lg-2">
                <ul class="no-bullet bold">
                    <li><a href="/profiles">Staff Profiles</a></li>
                    <li><a href="/media">Media Kit</a></li>
                    <li><a href="/contact-us">Contact Us</a></li>
                    <li><a href="/portal/login">Client Login</a></li>
                </ul>
            </div>
            <div class="col-md-5 col-lg-6">
                <div class="row">
                    <div class="col-lg-6 pd-0">
                        <label class="bold">Connect</label>
                        <ul class="social menu">
                            <li><a name="facebook" href="http://www.facebook.com/franchiseopps" target="_blank"> 
                                <span class="Facebook"></span> 
                                <span class="hide">Facebook</span> </a>
                            </li>
                            <li><a name="twitter" href="https://twitter.com/Franchise_Opps" target="_blank"> 
                                <span class="Twitter"></span> 
                                <span class="hide">Twitter</span> </a>
                            </li>
                            <li><a name="contact-us" href="/contact-us"> 
                                <span class="News"></span> 
                                <span class="hide">Contact Us</span> </a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-lg-6 address pd-0">
                        <small class="dark-gray-text"> <br class="hidden-lg"> 150 Granby Street, <br> Norfolk, VA 23510 <br> <span class="tel"><a href="tel:888-363-3390">888-363-3390</a></span> </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <hr>
            <div class="row">
                <div class="col-md-10 col-lg-6 pd-0">
                    <div class=""><p class="h4 dark-gray-text">Download Our Apps</p></div>
                    <div class="row">
                        <div class="col-md-7 apps FO pd-0">
                            <p class="bold dark-gray-text">Franchise Opportunities</p>
                            <ul class="no-bullet bold">
                                <li><a target="_blank" href="https://itunes.apple.com/us/app/own-a-franchise/id571310589?ls=1&amp;mt=8">For Apple</a></li>
                                <li><a target="_blank" href="https://play.google.com/store/apps/details?id=com.Franchiseopportunities.OwnAFranchise">For Android</a></li>
                            </ul>
                        </div>
                        <hr class="hidden-lg">
                        <div class="col-md-5 apps BBN pd-0">
                            <p class="bold dark-gray-text">Business Broker Network</p>
                            <ul class="no-bullet bold">
                                <li><a target="_blank" href="https://itunes.apple.com/us/app/businesses-for-sale/id1097116749?mt=8">For Apple</a></li>
                                <li><a target="_blank" href="https://play.google.com/store/apps/details?id=net.businessbroker.businessbroker&amp;hl=en">For Android</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="">
                    <hr class="hidden-lg">
                    <div class="text-right dark-gray-text">
                        <span class="copyright"><small>Copyright &copy; <?php echo date('Y'); ?>. FranOppNetwork.com</small></span>
                    </div>
                </div>
                <hr class="hidden-sm">
            </div>
        </div>
    </div>
</footer>
<div id="waiting"></div>
<div id="testimonial-video-modal" class="modal fade">
    
</div>

<script>
    function toggleNavbar(){
        document.getElementById('nav-menu').classList.toggle('hidden-md');
    }

    function toggleBio(param){
        var id = param.getAttribute('data-id');
        document.getElementById(id).classList.toggle('hide');
    }

    document.addEventListener("click", (evt) => {
        var bioList = document.querySelectorAll('.dropdown-pane');
        var dataId = evt.target.getAttribute('data-id') ? evt.target.getAttribute('data-id') : evt.target.getAttribute('id');
        for (let index = 0; index < bioList.length; index++) {
            const element = bioList[index];
            if (dataId != element.getAttribute('id')) {
                if (!document.getElementById(element.getAttribute('id')).classList.contains('hide')) {
                    document.getElementById(element.getAttribute('id')).classList.add('hide');
                }
            }
        }
    });

    function setvideo(videoUrl){
        var videoBlock = '<p class="h4 text-center extra-bold">Improve Engagement With Prospective Franchisees.</p>';
        videoBlock += '<video preload="none" id="video_ctrl" controls="controls" style="width:100%;" autoplay><source src="'+videoUrl+'">Your browser does not support HTML5 Video</video>';
    
        document.getElementById('videoElement').innerHTML = videoBlock;
    
    }

    window.onload = function(){  
        var url = window.location.href;
        if (url.indexOf('#') > -1){
            console.log(url.substring(url.lastIndexOf('#') + 1));
            var scrollId = url.substring(url.lastIndexOf('#') + 1);
            document.getElementById('id-'+scrollId).style.border = '1px solid red';
        }
    }  

    function showModal(title, url){
        var modal = '<div class="modal-dialog modal-dialog-centered">';
        modal += '<div class="modal-dialog modal-block">';
        modal += '<div class="modal-content">';
        modal += '<div class="modal-header">';
        modal += '<h5 class="modal-title" id="exampleModalLabel">'+title+'</h5>';
        modal += '<button type="button" class="btn-close" onclick="closeModal()"></button>';
        modal += '</div>';
        modal += '<div class="modal-body">';
        modal += '<video controls autoplay>';
        modal += '<source src="'+url+'" type="video/mp4">';
        modal += '</video>';
        modal += '</div>';
        modal += '</div>';
        modal += '</div>';
        modal += '</div>';

        document.getElementById('testimonial-video-modal').innerHTML = modal;
        document.getElementById('testimonial-video-modal').classList.add('show');
        // document.body.classList.add("modal-open");
    }

    function closeModal(){
        document.getElementById('testimonial-video-modal').innerHTML = '';
        document.getElementById('testimonial-video-modal').classList.remove('show');
        // document.body.classList.remove("modal-open");
    }
</script>
