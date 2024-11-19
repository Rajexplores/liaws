
<?php
    $splide = false;
    $meta = array();
    $section = $_GET['section'];
    $meta[$section]['description'] = 'Franchise Opportunities Network is a leading franchise lead generation company based in Atlanta, GA. Let us help you find quality prospects to grow your franchise or business opportunity.';
    $meta[$section]['keywords'] = 'franchise,Franchise Opportunities,franchises,franchise information,franchisee,franchisor,franchising opportunities,franchising industries,financing,franchise loans,required investment,financial assistance,franchise development,franchisee,small business opportunities,franchised opportunity,franchise information,free newsletter,franchise consulting,business to business concept,franchise directory,entrepreneur,franchiseopportunitiesnetwork.com';
    if (empty($_GET['section'])) {
        $section = 'home';
        $splide = true;
        $meta[$section]['title'] = 'Franchise Opportunities Network | Franchise Lead Generation Company';
    }elseif ($_GET['section'] == 'profiles') {
        $meta[$section]['title'] = 'Franchise Opportunities Network | Staff Profile Page';
        $meta[$section]['description'] = 'Franchise Opportunities Network Staff Profiles - Learn More About Our Team';
    }elseif ($_GET['section'] == 'testimonials') {
        $meta[$section]['title'] = 'Franchise Opportunities Network - Read testimonials from franchises that have been successful using Franchise Opportunities Network to grow their franchise.';
        $meta[$section]['description'] = 'Franchise Opportunities Network Staff Profiles - Learn More About Our Team';
    }elseif ($_GET['section'] == 'media') {
        $meta[$section]['title'] = 'Franchise Opportunities Network | Advertising Package';
        $meta[$section]['description'] = 'Download Our Media Kit -- Learn More About Our Franchise Lead Generation Services';
    }elseif ($_GET['section'] == 'contact-us') {
        $meta[$section]['title'] = 'Franchise Opportunities Network | Contact Us';
        $meta[$section]['description'] = 'Franchise Opportunities Network - Answering all franchise questions and addressing franchise issues.';
    }elseif ($_GET['section'] == 'newclient') {
        $meta[$section]['title'] = 'Franchise Opportunities Network | New Client';
        $meta[$section]['description'] = 'Onboarding information for new clients including pertinent forms, checklists, best practices etc.';
    }elseif ($_GET['section'] == 'media-kit') {
        $meta[$section]['title'] = 'Franchise Opportunities Network | View Media Kit';
    }elseif ($_GET['section'] == 'leadprocess') {
        $meta[$section]['title'] = 'Franchise Opportunities Network | Lead Process';
    }else{
        $meta[$section]['title'] = 'No Such Page';
    }
?>


<!DOCTYPE html>
<html lang="en-US">
    <?php include_once('includes/head.php');?>
    <body>
        <?php include_once('includes/header.php');?>
        <?php include_once('pages/'.$section.'.php');?>
        <div class="space-70"></div>
        <?php include_once('includes/footer.php');?>
        <script src="https://www.google.com/recaptcha/api.js"></script>
        <script>
            var triggerForm = true;
            function onSubmit(token) {
                if(triggerForm == true){
                    triggerForm = false;
                    let form = document.getElementById('contact-form');
                    if (!form.checkValidity()) {
                        form.reportValidity();
                        triggerForm = true;
                        return false;
                    } else {let loading = document.getElementById('waiting');
                        loading.classList.add('wait');
                        document.getElementById('recaptcha_token').value = token;
                        form.submit();
                    }
                }
            }
        </script> 
    </body>
</html>