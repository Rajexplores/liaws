<?php 
include_once('includes/global.php');
$subsection_array = ['funding','help','guide'];
if(in_array($_GET['section'],$subsection_array)){
    $title = $directory_array[$_GET['section']][$_GET['subsection']]['title'];
    $long_title = $directory_array[$_GET['section']][$_GET['subsection']]['long_title'];
    $page_url = $directory_array[$_GET['section']][$_GET['subsection']]['page_url'];  
}else{
    $title = $directory_array[$_GET['section']]['title'];
    $long_title = $directory_array[$_GET['section']]['long_title'];  
    $page_url = $directory_array[$_GET['section']]['page_url'];  
}

$breadcrumbs['2']['url'] = $_GET['section']; 

$container = 'container';
$hidePage = false;
if($_GET['section'] == 'industry-profiles'){
    $bgImg = '';
    $bCls = 'banner-bg mx-height';
    $bWidth = 'w-100';
    $bTitle = 'Industry Profiles & Overviews';
    $bdesc = '<p class="white-text headerParagraph larger">As you embark on looking for a franchise or business opportunity to buy, it is very helpful to learn about each of the industries you are interested in. Whether you are looking at buying a franchise in the automotive industry or the food industry, broaden your knowledge by reading expert analysis on our comprehensive Industry Profiles pages. Knowledge is power! Knowing more about the various industries will help you become a more informed buyer and will lead you toward making a better buying decision!</p>';
    $meta_title_override = 'Industry Profiles | Franchise Opportunities.com';
    $description_override = 'Before investing in a franchise, research the facts, figures, and trends of the most popular industries on our informative Industry Profile pages.';
    $keywords_override = 'Industry Profile,Overview,Information,Facts,Data,franchising,category';
}elseif($_GET['section'] == 'guide-pros-cons-owning-franchise'){
    $bCls = 'banner-bg mx-height';
    $bWidth = 'w-100';
    $bTitle = 'Your Guide to the Pros & Cons of Owning a Franchise';
    $hide = 'hide';
    $meta_title_override = 'Guide to Owning a Franchise | Pros & Cons | Franchise Opportunities';
    $description_override = 'Learn the in\'s and out\'s of how to buy a franchise, the pros & cons of franchise ownership, the costs behind buying & opening a franchise, & more.';
    $keywords_override = 'franchise ownership guide,what it takes,comparison to other businesses';
}elseif($_GET['section'] == 'college-scholarship-contest'){
    $bCls = 'banner-bg mx-height scholarship scholarship2017';
    $bgImg = 'style="--bg-mobile: url(\'/images/hero-images/fo/college-scholarship2017.jpg\'); --bg-tablet: url(\'/images/hero-images/fo/college-scholarship2017med.jpg\'); --bg-desktop: url(\'/images/hero-images/fo/college-scholarship2017lrg.jpg\');"';
    $bWidth = '';
    $bTitle = '2017 College Scholarship Contest';
    $hide = 'hide'; 
    $meta_title_override = 'Win a College Scholarship | FranchiseForSale.com';
    $description_override = 'Win a College Scholarship - Enter FranchiseForSale.com College Scholarship Contest and win money to help pay for your education!';
    $keywords_override = 'college,scholarship,entrepreneurs,contest,education';
}elseif($_GET['section'] == '2018-college-scholarship-future-entrepreneur'){
    $bCls = 'banner-bg mx-height scholarship';
    $bgImg = 'style="--bg-mobile: url(\'/images/hero-images/fo/college-scholarship2018.jpg\'); --bg-tablet: url(\'/images/hero-images/fo/college-scholarship2018med.jpg\'); --bg-desktop: url(\'/images/hero-images/fo/college-scholarship2018lrg.jpg\');"';
    $bWidth = '';
    $bTitle = '2018 College Scholarship Contest';
    $hide = 'hide'; 
    $meta_title_override = '2018 Entrepreneur Scholarship | FranchiseForSale.com';
    $description_override = 'Enter to win $2,000 from FranchiseForSale.com\'s 2018 College Scholarship for the Future Entrepreneur. Learn about our entrepreneur scholarship here.';
    $keywords_override = 'college,scholarship,entrepreneurs,contest,education';
}elseif($_GET['section'] == '2019-college-scholarship-future-entrepreneur'){
    $bCls = 'banner-bg mx-height scholarship scholarship2019';
    $bgImg = 'style="--bg-mobile: url(\'/images/hero-images/fo/scholarship2019.jpg\'); --bg-tablet: url(\'/images/hero-images/fo/scholarship2019med.jpg\'); --bg-desktop: url(\'/images/hero-images/fo/scholarship2019lrg.jpg\');"';
    $bWidth = '';
    $bTitle = '2019 College Scholarship Contest';
    $hide = 'hide'; 
    $meta_title_override = '2019 Entrepreneur Scholarship | FranchiseForSale.com';
    $description_override = 'Enter to win $2,000 from FranchiseForSale.com\'s 2019 College Scholarship for the Future Entrepreneur. Learn about our entrepreneur scholarship here.';
    $keywords_override = 'college,scholarship,entrepreneurs,contest,education';
}elseif($_GET['section'] == 'how-buy-franchise'){
    $bCls = 'hide';
    $container = '';
    $meta_title_override = 'How To Buy A Franchise - The Ultimate Guide | Franchise Opportunities';
    $description_override = 'Here\'s a comprehensive guide on how to buy a franchise, including how to find and vet franchise opportunities, what you need to get started, and more.';
    $keywords_override = 'franchise guide,how to buy a franchise';
}elseif($_GET['section'] == 'net-worth-calculator'){
    $bCls = 'banner-bg mx-height';
    $bWidth = 'w-100';
    $bTitle = 'Net Worth Calculator';
    $bdesc = '<p class="headerParagraph white-text">Our Net Worth Calculator will easily help you discover your net worth. Knowing your net worth is an important step in knowing how much you can afford to spend on the purchase of a franchise or business opportunity.</p><div class="headerParagraph white-text">Just input all of your assets and liabilities in the form below and the total will be calculated automatically.</div>';
    $meta_title_override = 'Net Worth Calculator | FranchiseForSale.com';
    $description_override = 'Calculate your net worth using FranchiseForSale.com\'s Net Worth Calculator. By knowing your net worth, you will be better able to determine how much you can spend on the purchase of a food franchise or business opportunity. It is free, simple and easy to use - check it out.';
    $keywords_override = 'FranchiseForSale.com,net worth calculator,net worth,assets,money,investment';
}elseif($_GET['section'] == 'resources'){
    $bCls = 'banner-bg mx-height';
    $bWidth = 'w-100';
    $bTitle = 'Resources For Buying And Selling A Franchise';
    $bdesc = '<p class="headerParagraph white-text">Looking to buy or sell a franchise or business? Find great buyer and seller resources and tools to help you with the process. We wish you all the best in the buying and selling process!</p>';
    //Meta Data
    $meta_title_override = 'Resources and Tools | FranchiseForSale.com';
    $description_override = 'Looking to buy or sell a franchise or business? Find great buyer and seller resources and tools to help you with the process. We wish you all the best in the buying and selling process!';
    $keywords_override = 'resources,tools,calculators,information,guide,how-to,buying,selling,buy,sell,franchise,business for sale';
}elseif($_GET['section'] == 'privacy-website-usage-policy'){
    $bCls = 'banner-bg mx-height';
    $bWidth = 'w-100';
    $bTitle = 'Privacy & Website Usage Policy';
    $bdesc = '<p class="headerParagraph white-text">Our goal at FranchiseForSale.com is to provide you the best inventory and information available on franchise opportunities. We also strive to provide the best user experience possible and protect our users\' privacy.</p><p class="text-justify headerParagraph white-text">Our privacy policy covers data used in our websites, mobile apps, email and text-messaging services. The short summary below highlights the key points for your quick reference. For additional information and more detail, please read the full policy. Thank you.</p>';
    $meta_title_override = 'Privacy & Website Usage Policy | FranchiseForSale.com';
    $description_override = 'FranchiseForSale.com\'s Privacy & Website Usage Policy. Read about FranchiseForSale.com\'s Privacy Policy and Website Use Policy.';
    $keywords_override = 'FranchiseForSale.com,Privacy Policy,Website Usage Policy,privacy,confidentiality,legal';
}elseif($_GET['section'] == 'legal-services'){
    $bCls = 'banner-bg mx-height';
    $bWidth = 'w-100';
    $bTitle = 'Franchise Legal Advice - Do I Need It?';
    $bdesc = '<p class="headerParagraph white-text">Buying a franchise or business opportunity is a big decision. Consulting with an attorney who is experienced in franchising and business acquisition is often advised. Read more below for guidance on involving legal counsel.</p>';
    $meta_title_override = 'Franchise Legal Advice - Do I Need It - Legal Resouces Guide | FranchiseForSale.com';
    $description_override = 'Buying a franchise and wondering whether seeking franchise legal advice is a good idea? Read more and find help with locating a franchise attorney at FranchiseForSale.com.';
    $keywords_override = 'franchise legal advice,find a franchise attorney,franchise disclosure document,franchise agreement';
}elseif($_GET['section'] == 'franchise-business-glossary'){
    $bCls = 'banner-bg mx-height';
    $bWidth = 'w-100';
    $bTitle = 'Resources For Buying And Selling A Franchise';
    $bdesc = '<p class="headerParagraph white-text">Looking to buy or sell a franchise or business? Find great buyer and seller resources and tools to help you with the process. We wish you all the best in the buying and selling process!</p>';
    $meta_title_override = 'Franchise Terms Glossary | FranchiseForSale.com';
    $description_override = 'Visit Glossary of Franchise Terms for quick access to the most popular franchise terms and phrases along with their definitions.';
    $keywords_override = 'glossary of business terms,franchise glossary page,business terms,definitions of common franchise terms';
}elseif($_GET['section'] == 'franchise-fee'){
    $bCls = 'banner-bg mx-height';
    $bWidth = 'w-100';
    $bTitle = 'Franchise Fees';
    $bdesc = '<p class="headerParagraph white-text">No matter what franchise industry you choose, you\'ll find that all franchise agreements require the payment of franchise fees. A franchise fee refers to one of several types of one-time or ongoing payments that a franchisee agrees to make to the franchisor organization. These financial obligations establish and maintain the relationships that exist between the franchisor and its franchisees.</p><p class="headerParagraph white-text">While specific amounts and fees vary, you should have access to an organization\'s franchise fees before you sign your franchise agreement. <b class="primary-blue-text">The Federal Trade Commission (FTC) requires that franchisors reveal all fees and costs in their Uniform Franchise Offering Circular (UFOC), which they must provide to prospective franchisees before selling a franchise.</b> The franchisor is legally required to ensure this information is as accurate and complete as possible so prospective franchisees can make informed decisions.</p>';
    $meta_title_override = 'Guide to Franchise Fees - Franchise Opportunities';
    $description_override = 'A franchise fee is one of several types of one-time or ongoing payments a franchisee agrees to make to the franchisor organization. Learn more about franchise fees.';
    $keywords_override = 'franchise fee';
}elseif($_GET['section'] == 'frequently-asked-franchising-questions'){
    $bCls = 'banner-bg mx-height';
    $bWidth = 'w-100';
    $bIgnore = true;
    $bTitle = 'Questions & Answers On Franchising';
    $bdesc = '<p class="headerParagraph white-text">At FranchiseForSale.com we strive to provide our users with the best information possible to help them evaluate all of their options in terms of franchise ownership. Over the years, we have collected some of the more common questions our users have and provided answers to these frequently asked questions. If you have additional questions or need additional help, please use our CONTACT US page to ask your question or ask for help. Thank you!</p>';
    $meta_title_override = 'Frequently Asked Questions About Franchising | FranchiseForSale.com';
    $description_override = 'Frequently Asked Questions About Franchising - See questions and answers on FranchiseForSale.com. A great source of information on franchising and also a fantastic inventory of franchises for sale.';
    $keywords_override = 'frequently asked questions,franchising,franchises,questions,answers,FAQs';
    $breadcrumbs['2']['title'] = 'Resources';
    $breadcrumbs['2']['url'] = 'resources';
    $breadcrumbs['3']['title'] = 'Frequently Asked Franchising Questions';
    $breadcrumbs['3']['url'] = '';
}elseif($_GET['section'] == 'franchise-agreement'){
    $bCls = 'banner-bg mx-height';
    $bWidth = 'w-100';
    $bTitle = 'Franchise Agreements';
    $bdesc = '<p class="headerParagraph white-text">After you\'ve chosen a franchise, received approval, secured financing, and found the right location, you\'re likely eager to get started with your new venture. Before you do, you\'ll have to sign a franchise agreement. This key document defines the terms of your relationship with your franchisor. To protect yourself and your investment, take the time to understand the terms of this contract and how it impacts your business.</p>';
    $meta_title_override = 'Guide to Franchise Agreements - Franchise Opportunities';
    $description_override = 'Franchise agreements are the legal, binding contracts between franchisors & franchisees.  Learn what they cover, their typical conditions, & how to terminate one.';
    $keywords_override = 'franchise agreement';
}elseif($_GET['section'] == 'finance-center'){
    $bCls = 'banner-bg mx-height';
    $bWidth = 'w-100';
    $bTitle = 'Finance Center';
    $bdesc = '<p class="headerParagraph white-text">Welcome to the FranchiseForSale.com Finance and Lending Center. Here you will find companies that can help supply financing options to help you purchase your dream business.</p>';
    $meta_title_override = 'Finance & Lending Center | FranchiseForSale.com';
    $description_override = 'FranchiseForSale.com Finance & Lending Center -- find resources to help you finance the purchase of a franchise or business opportunity.';
    $keywords_override = 'lending,financing,purchase a franchise,buy a franchise,business loans,buying a franchise';
}elseif($_GET['section'] == 'self-survey'){
    $bCls = 'banner-bg mx-height';
    $bWidth = 'w-100';
    $bTitle = 'Is Franchising Right for Me? <br> A Self-Survery to Help You Find Out.';
    $bdesc = '<p class="headerParagraph white-text">The intent of this self-evaluation is to determine your potential success as a franchisee. There are a number of standard traits that practically all franchisors look for in prospective franchisees. As you peruse the following list, give yourself an honest assessment on each characteristic.</p><div class="survey-row"><div class="large-4"><ul class="white-text"><li>Honest</li><li>Service-Oriented</li><li>Customer Focused</li><li>Personable</li><li>Quick Learner</li></ul></div><div class="large-4"><ul class="white-text"><li>Motivated</li><li>Managerial Skills</li><li>High Confidence Level</li><li>Follows Basic Instructions Well</li><li>Organized</li></ul></div></div>';
    $meta_title_override = 'Is Franchising Right for Me? A Free Assessment Tool.';
    $description_override = 'Use this free self-assessment to determine if franchising is right for you.   Take the survey now to see if franchise entrepreneurship is a good choice for you.';
    $keywords_override = 'franchise self-assessment,survey,test,evaluation';
}elseif($_GET['section'] == 'error'){
    $bCls = 'banner-bg mx-height pd-error';
    $bWidth = 'w-66';
    $refId = $_GET['section'] == 'error' ? '<p class="text-right white-text">Request ID: 0HMARA740E9OR:00000007</p>' : '';
    $bTitle = $_GET['section'] == '404' ? '404 - Page Not Found' : 'Server Error';
    $bdesc = '<p class="headerParagraph white-text">We\'re sorry but the page you requested could not be found.</p><p class="headerParagraph white-text">We sincerely apologize for the inconvenience. Below are some suggested links that will get you back on track!</p><ul class="white-text">
            <li>Go to <a href="/">FranchiseForSale.com Home Page</a></li>
            <li><a href="/alphabetical-company-search">Search Franchises for Sale</a></li>
            <li><a href="/contact-us">Contact Us --- Need Help? Find it Here</a></li>
            <li><a href="/advertise-with-us">Advertise with Us</a></li>
            <li><a href="/about-us">About Us</a></li>
        </ul>'.$refId;
    $textCenter = 'text-center';
    $textLeft = 'text-left';
    $meta_title_override = 'No such page';
    $description_override = '';
    $keywords_override = '';
    $hidePage = true;
}elseif($_GET['section'] == 'about-us'){
    $bCls = 'banner-bg mx-height';
    $bWidth = 'w-100';
    $bTitle = 'About Us';
    $bdesc = '<p class="headerParagraph white-text">FranchiseForSale.com is a leading website for franchises for sale. Our mission is to provide entrepreneurs great information on available franchises and business opportunities. Ultimately, we are in the business of connecting prospective buyers with the franchises they are interested in, so they can learn more about the opportunity and gather solid information.</p><p class="headerParagraph white-text">FranchiseForSale.com was founded in 1999 and is based in Sandy Springs, GA -- just north of Atlanta. Owning a franchise can be highly rewarding - we wish you all the best in finding the perfect franchise or business opportunity to own and operate!</p><p class="headerParagraph white-text">FranchiseForSale.com is part of <a target="_blank" class="white-text underline" rel="noreferrer" href="https://www.franoppnetwork.com">Franchise Opportunities Network</a> which includes FranchiseForSale.com, FoodFranchise.com, and BusinessBroker.net.</p>';
    $meta_title_override = 'About Us - Company Information | FranchiseForSale.com';
    $description_override = 'Learn about the mission and history of FranchiseForSale.com. FranchiseForSale strives to provide a great selection of available franchises and business opportunities for those looking to own their own business. ';
    $keywords_override = 'Franchise For Sale,about us,information on FranchiseForSale.com,company information,history';
}elseif($_GET['section'] == 'advertise-with-us'){
    $bCls = 'banner-bg mx-height';
    $bWidth = 'w-100';
    $bTitle = 'Advertise With Us - Let Us Help You Promote Your Franchise';
    $bdesc = '<p class="headerParagraph white-text">Advertise your franchise on FranchiseForSale.com and reach the best audience of qualified prospects. We have many different advertising packages to choose from, contact us today to speak with one of our experienced Sales Associates. The <a target="_blank" class="white-text underline" rel="noreferrer" href="https://www.franoppnetwork.com">Franchise Opportunities Network</a> also includes these top-tier franchise lead generation websites:</p><ul class="white-text" style="padding-left: 1rem;"><li>FoodFranchise.com</li><li>FranchiseForSale.com</li><li>BusinessBroker.net</li></ul>';
    $meta_title_override = 'Advertise With Us | FranchiseForSale.com';
    $description_override = 'Promote your franchise or business opportunity on FranchiseForSale.com. Advertise your concepts on FranchiseForSale.com to capture quality prospective buyers. Contact us today to learn more.';
    $keywords_override = 'advertise,promote,marketing,market,promotion';
}elseif($_GET['section'] == 'contact-us'){
    $bCls = 'banner-bg mx-height';
    $bWidth = 'w-100';
    $bTitle = 'Contact Us';
    $bdesc = '<p class="headerParagraph white-text">Please use the contact form below to contact us with any questions or requests for information you may have. We look forward to helping you! Thank you.</p>';
    $meta_title_override = 'Contact Us - Get Help, Request Information | FranchiseForSale.com';
    $description_override = 'FranchiseForSale.com is a leading website for franchises for sale. Contact us with questions, request information about advertising, and any other questions you may have. We look forward to helping you.';
    $keywords_override = 'contact us page,questions,request information,get help,franchise for sale,phone number,company address';
}elseif($_GET['section'] == 'sitemap' || $_GET['section'] == 'site-map'){
    $_GET['section'] = 'sitemap';
    $bCls = 'banner-bg mx-height';
    $bWidth = 'w-100';
    $bTitle = 'Site Map';
    $bdesc = '<p class="headerParagraph white-text">This Site Map page is designed to provide an overview of all the key pages on FranchiseForSale.com.</p>';
    $meta_title_override = 'Site Map Directory Page | FranchiseForSale.com';
    $description_override = 'Site Map Directory Page for FranchiseForSale.com.   The Site Map page provides an overview of all the key pages on the FranchiseForSale.com site.   We can help you find a great franchise to buy - search now!';
    $keywords_override = 'Site Map,FranchiseForSale.com,Directory';
}elseif($_GET['section'] == 'photography-credits'){
    $bCls = 'banner-bg mx-height';
    $bWidth = 'w-100';
    $bTitle = 'Photography Credits';
    $bdesc = '<p class="headerParagraph white-text">We are thankful to all of the beautiful photos we were able to source from a variety of talented photographers from the photo-sharing website FLICKR. Appropriate recognition and credit is provided to these photographers below. Thankyou!</p><button onclick="goBack()" class="btn-orange">« Back</button>';
    $meta_title_override = 'Photography Credits | FranchiseForSale.com';
    $description_override = 'We are thankful to a variety of talented photographers for all of the beautiful photos we were able to source from the photo-sharing website FLICKR.';
    $keywords_override = 'franchise for sale,franchise opportunity,business opportunity,franchises,business for sale,search,find,locate,discover';
}elseif($_GET['section'] == 'press-release'){
    $getPress = post_json('/api/get_press_release?ext_link=/press-release/'.$_GET['press']);
    if (is_array($getPress['data']) && !empty(is_array($getPress['data']))) {
        $global_robots = 'noindex, nofollow';
        $press_array = $getPress['data'][0];
        $temp_profile = get_json('/api/get_profile/'.$site_id.'/'.$press_array['concepts_id']);
        $temp_profile = json_decode($temp_profile,true);
        $profile = $temp_profile['data']['0'];
        // debug($profile);die;
        $temp_url = $profile['brochure'][0]['url'];
        $bCls = 'banner-bg mx-height';
        $bWidth = 'w-100';
        $bTitle = 'Press Release';
        $bIgnore = true;
        $bdesc = '<p class="headerParagraph white-text">Read about the latest newsworthy developments with the franchise or business opportunity you have selected. Use the link below to navigate back to the brochure for your selection.</p>';
        $meta_title_override = $press_array['title_tag'];
        // $meta_title_override = $press_array['title'];
        $description_override = 'Press release for '.$profile['name'].' - '.date("l, M d, Y", strtotime($press_array['release_date'])).' - FranchiseForSale.com';
        $keywords_override = 'franchise for sale,franchise opportunity,business opportunity,franchises,business for sale,search,find,locate,discover';
        $breadcrumbs['2']['title'] = $profile['name'];
        $breadcrumbs['2']['url'] = 'franchise/'.$press_name;
        $breadcrumbs['3']['title'] = 'Press Release';
        $breadcrumbs['3']['url'] = ltrim($match_url, '/');
    }else{
        header('HTTP/1.0 404 Not Found');
        $bCls = 'banner-bg mx-height pd-error';
        $bWidth = 'w-66';
        $bTitle = '404 - Page Not Found';
        $bdesc = '<p class="headerParagraph white-text">We\'re sorry but the page you requested could not be found.</p><p class="headerParagraph white-text">We sincerely apologize for the inconvenience. Below are some suggested links that will get you back on track!</p><ul class="white-text">
                <li>Go to <a href="/">FranchiseForSale.com Home Page</a></li>
                <li><a href="/alphabetical-company-search">Search Franchises for Sale</a></li>
                <li><a href="/contact-us">Contact Us --- Need Help? Find it Here</a></li>
                <li><a href="/advertise-with-us">Advertise with Us</a></li>
                <li><a href="/about-us">About Us</a></li>
            </ul>';
        $textCenter = 'text-center';
        $textLeft = 'text-left';
        $meta_title_override = 'No such page';
        $description_override = '';
        $keywords_override = '';
        $_GET['section'] =  '404';
        $hidePage = true;
    }
}

$banner = '<section id="banner" class="landing-dir-banner '.$bCls.'">
            <div class="container '.$textCenter.'">
                <div class="introBack banner-intro-bg '.$bWidth.' '.$textLeft.'">
                    <h1 class="white-text">'.$bTitle.'</h1>
                    <div class="paragraphWrapper '.$hide.'">
                        '.$bdesc.'
                    </div>
                </div>
            </div>
        </section>';

if($_GET['section'] == '2018-college-scholarship-future-entrepreneur'){
    $banner = '<section id="banner" class="landing-dir-banner '.$bCls.'" '.$bgImg.'>
        <div class="container">
            <div class="columns large-6 end">
                <div class="row">
                    <div class="columns">
                        <p>&nbsp;</p>
                    </div> 
                    <div class="columns">
                        <img class="money" alt="$2000 College Scholarship for the Future Entrepreneur" width="372" height="208" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAXQAAADQCAMAAAAgR2otAAAAkFBMVEUAAAD////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////hYBDxtY/ldTD99O/76t/jayD538/31b/toHDpilDrlWDvqoD1yq/zv5/ngECw+pmDAAAAIHRSTlMA8CDQgEB3MRGqzGREiffcmbtU4wjrGcGiSyWxb5FbOWXaNscAABBbSURBVHja7JzrcpswEEYXsAuxY4FB3Iwv+/5P2em0k1ZZsqxY6HQ6e/6S+ZI5EdJKQgKW1/E3ORh/hQR/k4Exh0n/HzDpy5j0/wGTvsy/KL0YD2+n1iFiUvbDPYct6erLlP0Id0l5rc757iH6jP2l+/PVYUhbHbcyfsjwE80j3yFEnyFnrJogqKzeI5UfWpzjNIKe2+Bwjv592xB9hpxiVlh7KEDM2OBXXFPQ4S8Ov2LqNgzRZ8i5JzhPcgchF2RIRtBwy5ChHfcO0WdQup5J6juQMCCLe8J68nYh/LxziD6DRjXI0eTxzimuXu88wSXOe4boMyi3FnmSZevfcBGXwzq6RhB+3DZEn6GOajrgyR0uU3pYxRUFtMW2IfoMddQEPCeUcIA1nFHEsFeIPoPyRAnPDTLQdRCPb1BGvk+IPoPiW5TQeGDIUMYF4rmjkGnbEH2GPoor148oJPEQTYNS0m1D9BnqqJLJqFBKDbG8o5jDtiH6DH3Ui1smC3HV2HmfPicS8gax0H/o9Lx5342VY9uFPkSfIY8qH8+xPg+JvD/OMeSUfvxLP6e06t6lPcIvUlIypfuF6DNoFJ2tFwOGZNKJUVkwvX0OcdyYGVZRCmaD2hB9Bo1i5lMPDCmEKwDv5Jngz5EWowduAK92CNFn0CimD/ElBoyCgpG8EbmyaLxggCu4Odlp2xB9higKOzIDExSNjhu6G1LDRvGGAT0EHAQjhi5En0GZuKgCAx4wj2dfiIm0gShO7IsyYojfIUSfIY+irXSSjQs3tg00iuKFDgkphtx2CNFnyKNoK+1lFWPHrhIlEEfCTq4KQW2kC9FnUBwbNYhqxiP7ZtUY4CAORyqjAMEETh2iz+B/9sjNnEqZdOB7O6BE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">
                    </div>
                    <div class="columns">
                        <br>
                        <br class="show-for-medium"> 
                        <img alt="White FO Logo" width="300" height="40" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAASwAAAAoCAMAAABzRTflAAAAM1BMVEUAAAD///////////////////////////////////////////////////////////////+3leKCAAAAEHRSTlMAgL9AYCAQz5/vMHBQ34+vNEY/VgAAB51JREFUaN7sl+l2GyEMhUHs+33/p22F8OBxvB23TX+09yQZhEDSfAGM1TfKklX/kkgtZVpS70tDbyNG9VC+E0DFqz8gQ+Zs/Iqq1ro+LBNHGqRfgmWAh3XaBNK6IX3yJrnkp34CyahlfJLBOn56nTAV3YEuP4C10vz+lWWQ8mQG5A/WPsyrlSWjPlxZOQBA99IQJcOOxk3yvxPWa6ULowL6/bD2qI/kE6aGSjjUyCu3bHoOy2plRlcqa6JuplM7V2hYJXJlUCyeYfkyVrfW06WJiptz4ppgEdVSQ57BvB3rBNvGikykncSzypI1uiFyaCvdTlvx1UjdSQSj1igxeFSnS0jTaVivHqpjyUQsRVsi5cOsT2ERKgOtSBSAWRxKaoT11gUI1BJ393Z0g0PlBApzSkejNmOOna5D86waEjXZm9tYh1sgSalAHdAaU3ufgaYvYkfQao0Sg8Nw5c1xExzdqYcKWNJFnlSIH347XsBqw2Tl63z75LkGFMULs878jet2V93mAivw0nGOgTLByJmuaq0YPCsNz76mzsY63LKkQYM27qB0hoXgGH0QWIdbjIzglbI80qP9bD7boTiYGHn0BT49g7VdhLCDRa5cc+JFYB9B525A/ohSOx7St/8jGs3L69sbg1AFaZihOhv3YYmR4O7BGtwtj9cnMWGpaP5rlRVKeTvuwWqaZaRwUa6aoKUGqVRWh+jULUwGhhwkx5u6O7C6WozPhkdapOE5nnsCa7u+wkJY1VkOpb16JoslF5jV8fGot+PFNjQSp4F1hsXtx7A448jsuchcb0OLLrN2tG3M310AoD6DZbDEDpOQon9jadk6WbF8BJBcXA71DiyNUL3S78MSHgGovEG1yKmx80XY74DVtIi7vG5ITq6YRj5MrdPZ7eumTkCrOV1xsQACzwQZ9Q4sDz7av8Kyz7bhgjyX0M5M+07jN5+CfjLO2/BzWA5BXcuPWUzUJudgCimKtfXavNpy+voklwtpsp4d/iWs3RFvYXkgP4ElLSekRQ1WGlK1Rltp6o0RYDfdG1gRRVK+PrOatM8npQschJM4Mkp+RA6hAcA4EQ5YCu/AcvNzvOAWluoI038PVr28awc5zunZL6esGwiyVte1Itwa9XJ1MFewoiSxczPZ9ACWjBKjohklFXojvmtY+QzLKrC4tpPiu/css+621FL5AktFuZTegeUACkj5MiigrEWdiIDh5YVGSpQQ3MkQIggByaorWIZjclUztn0AS0YtwwKNCGPdSZnDcKRNNsGUoU6wDAxYKT+82/svsJzex5t28hxUnNN1fYvYY0wn6vbcLV93aicq7hgUV1vZTkPnY/N6TaOcDZFb8yWeyAyKqxzt1M4lhUoRa9SuiCSft1G+7VSv7D7g1w+roQ8AqI9vFWXD+l4Jn7PxN2WBxlD6/Uqnxn9YogHABtB9bwSL/sMSJQDJ/GjXapfUhmHgSrIlxZ+8/9P2bMENaYHpdMqvY3/cxZKRzUbrODJdH3ubfci6Q6ROfuysl/qbDH82LhvjSYX3YkuI9WeTlaVk5HgYbqRnNdS1+Of3kJUb/hXpr+RYXw1Ogr9FhcPvt57pSVnQ+TLwv8nKzAezn75xKwRkb5sL5vpy9l7uWm78cB0R3nH93nbrqPb0DEsFsFO8lqnteZXLFePxntTIFP8/swoDLneGuHluy6Ydr2F6br1Mv5P3QKCNF6eBgJ4tSVq67R0C/luPq1ky3kBWIsDqNKBMIwBkzhno8cKjOzHMkRngLHPmsrKNeFeID86DTUDuNwLMmzr3lG3YAAazDpo9g6UYFzS2BijDx5e1dKt5roRkInHvdfSGFd6/XCQco0ew8xvzwbyWp1PulWWZRBN4B1kjARcBC0x32DpYFLCQWS8opvDUxhqVGNl0U0wEyMRMyB1MAKQTZTlUWTBEjhVvVAC8ex+YsoS/VSl+tXJRK0i+JpFM5VBheEVbLlSK0SPYia2EIrvQecip6DBzISrvIYvLnrxpM+oJKJ5m3jTgmlujAVSpLsV62+zChi/OKqntjNoZmFxE00ClFY8Iatqvsx1NDc0Kxhy9rIBhxbGFKDPb9agMMgqHa/2N0XcwnLCKfwftmgzdceXKe9F6iwyPUOIBIomitE9ZNHyTyQL0/GWkAVasJnqsCaMJL5Jg0QJAbfUoO41kLncouIhjmbntu+5StlUNRFi5M1ZzkRjU9KJb+N+jy3xUivG8hMd6sx1q4ehvWOBz3MTCEJOUgVLH4sGjtivVkFic4JR6XdRWFgFXSfFI66lZlondikx1b4NhurWXJLcRNyO3yiCXrUwNXYsj922vq9Oi2CWZuEGs4BKjR7Dfdp6BNKJSc+VvpJujvoGsqH0UAYQSAMmxZ9B4Hq2GkGziJGvaRoFWWswQoLXq+ni0gLpMCWm1Wkb+cotA01cjU93Lney+YRWJWtHqi7Q/vmZUdV03TTF6BHtY45sS/ynkJ/PmYPzsHfyjc0OWUF0K+Ql/yPoD85RZncZxr8oF/5D1xyFrEqqN+jdFcruSD1m/69AADaYCjnG7+JD1DfXby879z9nGtfRnCR+y7pFFMgI1kssLvvEh6yVz8swn+Mn4BfGpdZS1sR9LAAAAAElFTkSuQmCC">
                    </div>
                </div>
            </div>
        </div>
    </section>';
}elseif ($_GET['section'] == '2019-college-scholarship-future-entrepreneur') {
    $banner = '<section id="banner" class="landing-dir-banner '.$bCls.'" '.$bgImg.'>
        <div class="container">
            <div class="columns large-6 headingAnchor">
                <p class="white-text bold h1">$2,000 College Scholarship</p>
                <p class="white-text bold h2">for the Future Entrepreneur <b>2019</b></p>
            </div>
        </div>
    </section>';
}elseif ($_GET['section'] == 'college-scholarship-contest') {
    $banner = '<section id="banner" class="landing-dir-banner '.$bCls.'" '.$bgImg.'>
        <div class="container">

        </div>
    </section>';
}


if(!$bIgnore){
    $breadcrumbs['2']['title'] = $bTitle;
}



?>
<!doctype html>
<html lang="en-US" translate="no">
<?php include_once('includes/head.php'); ?>
    <body>
        <?php include_once('includes/header.php'); ?>
        <main id="main" class="pages-main">
            <?php echo $banner; ?>
            <?php if(!$hidePage){ ?>
            <div id="copy" class="<?php echo $container; ?>">
                <?php include_once('pages/'.$_GET['section'].'.php');?>
            </div>
            <?php } ?>
        </main>
        <?php include_once('includes/footer.php'); ?>
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