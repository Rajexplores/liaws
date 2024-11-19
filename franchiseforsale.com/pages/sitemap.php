<?php
    function return_cat_state(){
        global $filterstate;
        $categories = [
            'advertising-franchises' => 'Advertising Franchises',
            'auto-franchises' => 'Auto Franchises',
            'beauty-franchises' => 'Beauty Franchises',
            'business-opportunities' => 'Business Opportunities',
            'business-services-franchises' => 'Business Services Franchises',
            'child-related-franchises' => 'Child Related Franchises',
            'cleaning-franchises' => 'Cleaning Franchises',
            'computer-internet-franchises' => 'Computer &amp; Internet Franchises',
            'education-training-franchises' => 'Education Franchises',
            'entertainment-franchises' => 'Entertainment Franchises',
            'financial-franchises' => 'Financial Franchises',
            'food-franchises' => 'Food Franchises',
            'green-eco-friendly-franchises' => 'Green Franchises',
            'health-fitness-franchises' => 'Health &amp; Fitness Franchises',
            'home-based-franchises' => 'Home Based Franchises',
            'home-services-repair-franchises' => 'Home Services &amp; Repair Franchises',
            'low-cost-franchises' => 'Low Cost Franchises',
            'manufacturing' => 'Manufacturing Franchises',
            'mobile-franchises' => 'Mobile Franchises',
            'pet-franchises' => 'Pet Franchises',
            'photography-video' => 'Photography &amp; Video Franchises',
            'printing-copy-shipping-signs' => 'Printing, Copying, Shipping, Signs Franchises',
            'restoration-disaster-recovery-franchises' => 'Restoration, Disaster Recovery Franchises',
            'retail-franchises' => 'Retail Franchises',
            'seasonal-franchises' => 'Seasonal Franchises',
            'security-alarm-franchises' => 'Security &amp; Alarm Franchises',
            'senior-care-franchises' => 'Senior Care Franchises',
            'recreation-sports-franchises' => 'Sports &amp; Recreation Franchises',
            'travel-franchises' => 'Travel Franchises',
            'vending-franchises' => 'Vending Franchises',
            'veteran-franchises-for-sale' => 'Veteran&apos;s Franchises',
            'wholesale-distribution-franchises' => 'Wholesale - Distribution Franchises'
        ];
        $list = '';
            foreach ($categories as $key => $value) {
                $list .= '<li class="cat_state" id="'.$key.'">
                            <div>
                                <a href="/industry/'.$key.'">'.$value.'</a>
                                <span class="toggle" data-type="'.$key.'" onclick="toggleSitemap(this.getAttribute(\'data-type\'));"></span>
                            </div>';
                $sublist = '';
                foreach ($filterstate as $subkey => $subvalue) {
                    if(in_array($subkey,['CAN','INT'])){
                        continue;
                    }
                    $sublist .= '<li>
                                    <a href="/'.$key.'_in_'.$subvalue.'">'.ucwords(str_replace("-", " ", $subvalue)).'</a>
                                </li>';
                }
                $list .= '<ul>'.$sublist.'</ul></li>';
            }
        return '<ul class="categories">'.$list.'</ul>';
    }
?>
<div id="pg-directory">
    <div id="dirStates" class="sitemap">
        <div class="states-block gray-border xy-padded">
            <div class="catTitle"><p class="h3 white-text">Index</p></div>
            <div class="gray-border white-bg columns catList bold pd-15">
                <div class="pd-15"><br>
                    <p class="h3">Home</p>
                    <ul>
                        <li><a title="FranchiseForSale.com Home Page" href="/">FranchiseForSale.com Homepage</a></li>
                    </ul>
                    <hr>
                </div>
                <div class="pd-15">
                    <p class="h3">Search Pages</p>
                    <ul>
                        <li><a href="/alphabetical-company-search">Alphabetical Search</a></li>
                        <li><a href="/featured-franchises">Featured Franchises</a></li>
                        <li><a href="/hot-and-trendy-franchises">Hot &amp; Trendy Franchises</a></li>
                        <li><a href="/low-cost-franchises">Low Cost Franchises</a></li>
                        <li><a href="/new-franchises">New To Site Franchises</a></li>
                        <li><a href="/industry">Search by Industry</a></li>
                        <li><a href="/investment-level">Search by Investment Level</a></li>
                        <li><a href="/state">Search by State</a></li>
                    </ul>
                    <hr>
                </div>
                <div class="pd-15">
                    <p class="h3">Franchise Industries</p>
                    <?php echo return_cat_state(); ?><hr>
                </div>
                <div class="pd-15">
                    <p class="h3">About</p>
                    <ul class="square">
                        <li><a href="/about-us">About Us</a></li>
                        <li><a href="/advertise-with-us">Advertise With Us</a></li>
                        <li><a href="/contact-us">Contact Us</a></li>
                        <li><a href="/privacy-website-usage-policy">Privacy &amp; Website Usage Policy</a></li>
                    </ul><hr>
                </div>
                <div class="pd-15">
                    <p class="h3">Cool Stuff</p>
                    <ul class="square">
                        <li><a href="/finance-center">Finance Center</a></li>
                        <li><a href="/newsletter-sign-up">Newsletter Sign-Up</a></li>
                        <li><a href="/resources/net-worth-calculator">Net Worth Calculator</a></li>
                        <li><a href="/photography-credits">Photography Credits</a></li>
                        <li><a href="/resources/frequently-asked-franchising-questions">Frequently Asked Questions</a></li>
                        <li><a href="/resources">Resources</a></li>
                    </ul><br>
                </div>
            </div>
        </div>
    </div>
</div>