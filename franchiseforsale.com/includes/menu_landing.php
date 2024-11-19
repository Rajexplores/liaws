
<nav id="landing-desktop-nav">
    <ul>
        <!-- <li class="menu-text show-for-large siteLogo">
            <div id="logo"><a href="/<?php echo $landingLogoUrl; ?>" title="<?php echo $brand_array['brand_name']; ?> - Home"><img src="/images/logo.png" alt="<?php echo $brand_array['brand_name']; ?>"/></a></div>
        </li> -->
        <li class="hidden-md"><a href="<?php echo $landingLogoUrl; ?>">Home</a></li>
        <li class="has-submenu">
            <a href="/<?php echo $prepend_url; ?>industry/">By Industry</a>
        </li>
        <li class="has-submenu">
            <a href="/<?php echo $prepend_url; ?>investment-level/">By Investment Level</a>
        </li>
        <li class="has-submenu"> 
            <a href="/<?php echo $prepend_url; ?>state/">By State</a> 
        </li>
    </ul>
</nav>
<button id="cart" onClick="continue_form('<?php echo $prepend_url; ?>','header');" class="zero"><span class="basketText">Request Info</span><span class="basketText counter"><span id="total" class="cart_count"></span></span></button>
</div>

<div id="mobile-menu" class="hide">
<nav>
        <ul>
            <li class="mobileHome"><a href="<?php echo $landingLogoUrl; ?>"><span></span>Home Page</a></li>
            <li class="mobileIndustry" onclick="toggleSubMenu('mobileIndustry');">
                <a href="/<?php echo $prepend_url; ?>industry/"><span></span> By Industry</a>
            </li>
            <li class="mobileInvestment" onclick="toggleSubMenu('mobileInvestment');">
                <a href="/<?php echo $prepend_url; ?>investment-level/"><span></span> By Investment Level</a>
            </li>
            <li class="mobileState" onclick="toggleSubMenu('mobileState');">
                <a href="/<?php echo $prepend_url; ?>state/"><span></span> By State</a>
            </li>
        </ul>
    </nav>