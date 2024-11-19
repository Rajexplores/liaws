<?php 
include_once('includes/global.php');
$breadcrumbs['1']['title'] = '404 - Page Not Found';
$breadcrumbs['1']['url'] = '';
?>
<!doctype html>
<html lang="en-US" translate="no">
<?php include_once('includes/head.php'); ?>
    <body>
        <?php include_once('includes/header.php'); ?>
        <main id="main" class="pages-main">
            <section id="banner" class="landing-dir-banner banner-bg mx-height pd-error">
                <div class="container text-center">
                    <div class="introBack banner-intro-bg w-100 text-left">
                        <h1 class="white-text">404 - Page Not Found</h1>
                        <div class="paragraphWrapper ">
                            <p class="headerParagraph white-text">We're sorry but the page you requested could not be found.</p>
                            <p class="headerParagraph white-text">We sincerely apologize for the inconvenience. Below are some suggested links that will get you back on track!</p>
                            <ul class="white-text">
                                <li>Go to <a href="/">FranchiseForSale.com Home Page</a></li>
                                <li><a href="/industry">Search Franchises By Industry</a></li>
                                <li><a href="/resources/frequently-asked-franchising-questions">Questions &amp; Answers On Franchising</a></li>
                                <li><a href="/resources">Resources For Buying and Selling a Franchise</a></li>
                                <li><a href="/contact-us">Contact Us</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>
        </main>
        <?php include_once('includes/footer.php'); ?>
    </body>
</html>