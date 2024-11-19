<?php 
include_once('includes/global.php');
$title = $directory_array[$_GET['section']]['title'];
$long_title = $directory_array[$_GET['section']]['long_title'];  
$page_url = $directory_array[$_GET['section']]['page_url'];  

$breadcrumbsTitle = url_to_title($_GET['section']);
if($_GET['section'] == 'state'){
    $bTitle = 'Search By '.url_to_title($_GET['section']);
    $breadcrumbsTitle = 'State';
    $bgImg = 'style="--bg-tablet: url(\'/images/hero-images/ffs/states-selectmed.jpg\'); --bg-desktop: url(\'/images/hero-images/ffs/states-selectlrg.jpg\');"';
    // $bgImg = '/images/states-selectlrg.jpg';
    $bWidth = 'md-w-50 sm-w-66';
    $bdesc = '<p class="white-text headerParagraph">FranchiseForSale.com offers great franchises and business opportunities in your state of residence. No matter where you live, you can find a great franchise to buy. Pick your State below to find great franchises waiting for buyers like you! Search now, buy a franchise, and enjoy success!</p>';
    $meta_title_override = 'Search Franchises For Sale By US States | FranchiseForSale.com';
    $description_override = 'Search franchises for sale in your State. Find fantastic franchises and business opportunities for sale near you - search FranchiseForSale.com now!';
    $keywords_override = 'franchise,state search,business opportunities,business for sale,search by state';
    if(($_GET['landing_url'] == 'great-franchises-a') || ($_GET['landing_url'] == 'great-franchises-b')){
        $bdesc = '<p class="headerParagraph white-text">Franchiseforsale.com offers great franchises and business opportunities for sale in all 50 U.S. States. Have fun browsing the many franchises available in your State and request free information from those that interest you the most! We wish you all the best in finding a great franchise to buy!</p>';
    }
}elseif($_GET['section'] == 'investment-level'){
    $bTitle = 'Search By '.url_to_title($_GET['section']);
    $breadcrumbsTitle = 'Investment Level';
    $bgImg = 'style="--bg-tablet: url(\'/images/hero-images/ffs/investment-levelmed.jpg\'); --bg-desktop: url(\'/images/hero-images/ffs/investment-levellrg.jpg\');"';
    // $bgImg = '/images/investment-levellrg.jpg';
    $bWidth = 'w-66 sm-w-66';
    $bCls = 'mx-height';
    $bdesc = '<p class="white-text headerParagraph">No matter what your budget, FranchiseForSale.com has a fantastic franchise or business opportunity for you. Browse a variety of franchises at different investment levels within your desired overall investment range and budget. Please keep in mind that many franchisors can assist you in securing financing to help you with the purchase of your new franchise. Search now to find a great franchise that fits your investment criteria.</p>';
    $meta_title_override = 'Search Franchises for Sale By Investment Level | FranchiseForSale.com';
    $description_override = 'Search franchises by investment level. Find great franchises for sale that fit your desired budget. Great selection all price points - search now!';
    $keywords_override = 'franchise,investment level search,business opportunities,business for sale,search by asking price';

    if(($_GET['landing_url'] == 'great-franchises-a') || ($_GET['landing_url'] == 'great-franchises-b')){
        $bdesc = '<p class="headerParagraph white-text">Franchiseforsale offers a wide range of franchises and business opportunities for sale at many different price points. No matter what your budget, you can find a great franchise or business opportunity that fits your desired interests and investment level! We wish you all the best in your search.</p>';
    }
}elseif($_GET['section'] == 'industry'){
    $bTitle = 'Search Franchises By '.url_to_title($_GET['section']);
    $breadcrumbsTitle = 'Industry';
    $bgImg = 'style="--bg-tablet: url(\'/images/hero-images/ffs/search-industrymed.jpg\'); --bg-desktop: url(\'/images/hero-images/ffs/search-industrylrg.jpg\');"';
    // $bgImg = '/images/industries-selectlrg.jpg';
    $bWidth = 'sm-w-66';
    $bdesc = '<p class="white-text headerParagraph">FranchiseForSale.com offers a diverse selection of franchises in a wide variety of different franchise industry categories. Whether you are interested in owning an automotive franchise, food franchise, or senior care opportunity - FranchiseForSale.com has something for everyone. Pick a category below and start your search now for your dream business to own!</p>';
    $meta_title_override = 'Search Franchises for Sale By Industry | FranchiseForSale.com';
    $description_override = 'Search franchises and business opportunities for sale by industry category. Find an awesome selection of franchises and business opportunities for sale on FranchiseForSale.com. Great selection all price points - search now!';
    $keywords_override = 'franchise for sale,category search,industry search,business opportunities,search by franchise industry';
    if(($_GET['landing_url'] == 'great-franchises-a') || ($_GET['landing_url'] == 'great-franchises-b')){
        $bdesc = '<p class="headerParagraph white-text">Often when you are researching various franchise opportunities, you may find that you are interested in a particular industry. With that said, we give you our industry page, which allows you to search through a comprehensive listing of franchises found within a diverse set of industry categories. Owning your own franchise can be a rewarding experience, therefore, we invite you to select from these franchise industries and become the self-employed and independent business owner you have always dreamed of!</p>';
    }
}elseif($_GET['section'] == 'near-me'){
    $bTitle = 'Find Your Next Franchise Opportunity in America’s Top Cities';
    // $bgImg = 'style="--bg-tablet: url(\'/images/industries-selectmed.jpg\'); --bg-desktop: url(\'/images/industries-selectlrg.jpg\');"';
    // $bgImg = '/images/industries-selectlrg.jpg';
    $bCls = 'banner-bg mx-height';
    $bWidth = 'w-100';
    $bdesc = '<p class="white-text headerParagraph">Welcome to the ultimate resource for finding franchise opportunities in America’s top cities. Our extensive city index is tailored for entrepreneurs like you, looking to make informed decisions about where to expand or start a new franchise. Each city in our database offers a unique landscape of possibilities, backed by up-to-date demographic data and growth trends. From bustling metropolises to emerging markets, our insights help you pinpoint the perfect location for your franchise. Dive into our city guides and take the first step towards your successful franchise venture.</p>';
    $meta_title_override = 'Explore Lucrative Franchise Opportunities in Top Cities Across the USA - FranchiseOpportunities.com';
    $description_override = 'Discover the ideal city for your next franchise venture with our detailed city guides. Understand local markets, demographics, and growth trends to find the perfect location for your franchise business. Start exploring now!';
    $keywords_override = 'franchise,category search,business opportunities,business for sale,restaurant for sale,search by industry';
}elseif($_GET['section'] == 'alphabetical-company-search'){
    $bTitle = 'Alphabetical &amp; Company Search';
    $bCls = 'banner-bg mx-height';
    $bWidth = 'w-100';
    $bdesc = '<p class="headerParagraph white-text">Use our A to Z directory to find the franchise company that you are seeking. Alternatively, you can search by typing in the first few letters of a franchise company\'s name by using the Company Search box.</p><p class="headerParagraph white-text">We wish you all the best in finding the right franchise for you!</p>';
    $meta_title_override = 'Alphabetical and Company Search | FranchiseForSale.com';
    $description_override = 'Search alphabetically for a particular franchise company or do a specific company search on FranchiseForSale.com. Search now and find your dream business to buy!';
    $keywords_override = 'franchises for sale';
}elseif($_GET['section'] == 'franchises-for-sale-near-me'){
    $bTitle = 'Find '.url_to_title($_GET['section']);
    $breadcrumbsTitle = $bTitle;
    $bgImg = '/images/zip-codelrg.jpg';
    $bCls = 'mx-height';
    $bWidth = 'w-100';
    $hideFilter = 'hide';
    $bdesc = '<p class="white-text headerParagraph">Are you interested in starting a franchise but don\'t want to venture out too far away from home? Find affordable franchises in your area below. A great franchise that fits your budget could be close by, right in your city! So type in your zip code in the search box below and browse through a wide selection of franchises that could be right around the corner from your house. <span>You’ll be able to filter your results by your favorite <a href="/industry">industry categories</a>, <a href="/state">state</a>, or <a href="/investment-level">investment level</a>. Once you find a franchise that’s right for you, you can request more information and get started down the path to franchise ownership.</span> Happy searching!</p> 
        <form method="post" action="" id="zipcode_form" novalidate="novalidate">
                <div class="nearme-row">
                    <div class="hide">
                        <a class="valsum_anchor"></a>
                        <div class="validation-summary-valid formError nearMe"><span>Please Correct The Following Errors:</span>
                            <ul><li style="display:none"></li></ul>
                        </div>
                    </div>
                    <div class="search-alpha-row">
                        <div class="columns medium-6 large-4">
                            <label class="required-ind white-text">Enter your zip code and search</label>
                            <div class="ff PostalZip"><span></span> 
                                <input type="text" class="form-controls" name="ZipCode" data-val="true" data-val-regex="Invalid Zip Code" data-val-regex-pattern="^[0-9]{5}$|^[0-9]{5}\-[0-9]{4}$" data-val-required="Zip Code Is Required" id="ZipCode">
                            </div>
                        </div>
                    </div>
                    <br>
                    <input type="button" onclick="findStateZipCode();" class="postal btn-orange" value="Search Now">
                </div>
                <input name="__RequestVerificationToken" type="hidden" value="">
            </form>';
    $meta_title_override = 'Franchises For Sale Near Me | FranchiseOpportunities.com';
    $description_override = 'Find Franchises for sale near me by zip code. Explore franchises for sale that are in your area.';
    $keywords_override = 'franchises by zip code,area,franchise opportunities';
}


$banner = '<section id="banner" class="landing-dir-banner '.$bCls.'" '.$bgImg.'>
            <div class="container">
                <div class="introBack banner-intro-bg '.$bWidth.'">
                    <h1 class="white-text">'.$bTitle.'</h1>
                    <div class="paragraphWrapper">
                        '.$bdesc.'
                    </div>
                    <p class="hidden-mmd white-text readMoreHideSmall">You can further <a id="toggleReviseSearch" href="javascript:void(0);" onclick="toggleHidden(\'dirFilter\', \'hidden-ssm\');"> Revise Your Search</a>  here for a more specific search.</p> 
                </div>
            </div>
        </section>';

$breadcrumbs['2']['title'] = $breadcrumbsTitle;
$breadcrumbs['2']['url'] = $_GET['section'];

?>
<!doctype html>
<html lang="en-US" translate="no">
<?php include_once('includes/head.php'); ?>
    <body class="directory-body">
        <?php include_once('includes/header.php'); ?>
        <main id="main" class="home-main directory-main">
            <!-- <section id="heading" class="container">
                <h1 class="page-title"><?php echo $long_title;?></h1>
            </section>             -->

            <!-- Banner -->
            <?php echo $banner; ?>
            <?php if($_GET['section'] != 'franchises-for-sale-near-me'){ ?>
                <div id="dirFilter" class="results-filter landing-dir-filter hidden-ssm">
                    <div class="container">
                        <!-- Filter -->
                        <?php include_once('includes/filter.php'); ?>
                    </div>
                </div>
            <?php } ?>

            <div id="copy" class="container">
                <?php include_once('pages-directory/'.$_GET['section'].'.php');?>
            </div>

        </main>
        <?php include_once('includes/footer.php'); ?>
    </body>
</html>