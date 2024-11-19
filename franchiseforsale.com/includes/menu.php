<nav id="desktop-nav">
    <ul itemscope>
        <li class="menu-text show-for-large siteLogo">
            <div id="logo_desktop" class="logo"><a href="<?php echo $landingLogoUrl; ?>" title="<?php echo $brand_array['brand_name']; ?> - Home"><img src="/images/logo.png" alt="<?php echo $brand_array['brand_name']; ?>"></a></div>
        </li>
        <li class="has-submenu menu-industry">
            <a href="/industry/">By Industry</a>
            <ul class="submenu is-dropdown-submenu" id="industry-menu">
                <li class="is-submenu-item is-dropdown-submenu-item"><a href="/industry">View All Industries</a></li>
                <?php menu_industry(); ?>
            </ul>
        </li>
        <li class="has-submenu">
            <a href="/investment-level/">By Investment Level</a>
            <ul class="submenu is-dropdown-submenu dropdown1" id="investment-menu">
                <li class="is-submenu-item is-dropdown-submenu-item"><a href="/investment-level">View All Levels</a></li>
                <li class="is-submenu-item is-dropdown-submenu-item"><a href="/investment-level/franchises-under-10000">Under $10,000</a></li>
                <li class="is-submenu-item is-dropdown-submenu-item"><a href="/investment-level/franchises-under-20000">Under $20,000</a></li>
                <li class="is-submenu-item is-dropdown-submenu-item"><a href="/investment-level/franchises-under-50000">Under $50,000</a></li>
                <li class="is-submenu-item is-dropdown-submenu-item"><a href="/investment-level/franchises-under-100000">Under $100,000</a></li>
                <li class="is-submenu-item is-dropdown-submenu-item"><a href="/investment-level/franchises-under-200000">Under $200,000</a></li>
            </ul>
        </li>
        <li><a href="/state/">By State</a></li>
        <li><a href="/finance-center">Finance Center</a></li>
         
    </ul>
</nav>
<button id="cart" onClick="continue_form('<?php echo $prepend_url; ?>','header');" class="zero"><span class="basketText">Request Info</span><span class="basketText counter"><span id="total" class="cart_count"></span></span></button>
</div>

<div id="mobile-menu" class="hide">
    <nav>
        <ul>
            <li class="mobileHome"><a href="<?php echo $landingLogoUrl; ?>"><span></span> Home Page</a></li>
            <li class="has-submenu mobileIndustry" onclick="toggleSubMenu('mobileIndustry');">
                <a href="javascript:void(0);"><span></span> By Industry</a>
                <ul class="first-sub submenu">
                    <li class="is-submenu-item is-dropdown-submenu-item"><a href="/industry">View All Industries</a></li>
                    <?php menu_industry(); ?>
                </ul>
            </li>
            <li class="has-submenu mobileInvestment" onclick="toggleSubMenu('mobileInvestment');">
                <a href="javascript:void(0);"><span></span> By Investment Level</a>
                <ul class="first-sub submenu">
                    <li class="is-submenu-item is-accordion-submenu-item"><a href="/investment-level">View All Levels</a></li>
                    <li class="is-submenu-item is-accordion-submenu-item"><a href="/investment-level/franchises-under-10000">Under $10,000</a></li>
                    <li class="is-submenu-item is-accordion-submenu-item"><a href="/investment-level/franchises-under-20000">Under $20,000</a></li>
                    <li class="is-submenu-item is-accordion-submenu-item"><a href="/investment-level/franchises-under-50000">Under $50,000</a></li>
                    <li class="is-submenu-item is-accordion-submenu-item"><a href="/investment-level/franchises-under-100000">Under $100,000</a></li>
                    <li class="is-submenu-item is-accordion-submenu-item"><a href="/investment-level/franchises-under-200000">Under $200,000</a></li>
                </ul>
            </li>
            <li class="mobileState" onclick="toggleSubMenu('mobileState');">
                <a href="/state"><span></span> By State</a>
                <?php /* <ul class="first-sub submenu">
                    <li class="is-submenu-item is-accordion-submenu-item"><a href="/state">View All States</a></li>
                    <li class="is-submenu-item is-accordion-submenu-item"><a href="/franchises-for-sale-near-me">Find Franchises Near Me</a></li>
                </ul> */ ?>
            </li>
            <li class="mobileFinance"><a href="/finance-center"><span></span> Finance Center</a></li>
        </ul>
    </nav>