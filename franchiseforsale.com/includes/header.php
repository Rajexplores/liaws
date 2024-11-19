<?php
    $landingHeader = @$_GET['landing'] ? 'landingHeader' : '';

    if (@$_GET['landing']) {
        $landingLogoUrl = '/'.$_GET['landing_url'];
    }else{
        $landingLogoUrl = '/';
    }
?>

<header id="header" class="<?php echo $landingHeader; ?>">
    <div class="container">
        <button id="toggle" onClick="toggleMenu();" title="Menu Toggle" aria-label="Menu Toggle">
            Menu
            <span id="menu-icon" class="menu-icon"></span>
        </button>
        <div id="logo_mobile" class="logo logo-mobile">
            <?php
                if (@$_GET['landing']) {
                    ?>
                        <img src="https://d1zaul414tw0cr.cloudfront.net/cdn_ffs/images/logo.png" alt="<?php echo $brand_array['brand_name']; ?>">
                    <?php
                }else{
                    ?>
                        <a href="/" title="<?php echo $brand_array['brand_name']; ?> - Home">
                            <img src="https://d1zaul414tw0cr.cloudfront.net/cdn_ffs/images/logo.png" loading="lazy" alt="<?php echo $brand_array['brand_name']; ?>">
                        </a>
                    <?php
                }  
            ?>
        </div>
        <?php isset($_GET['landing']) ? include_once('includes/menu_landing.php') : include_once('includes/menu.php'); ?>
    </div>
    <?php if ($noBreadcrumbs != 'hide' && !isset($_GET['city'])) { ?>
        <div id="breadcrumbs_container" class="<?php echo $noBreadcrumbs; ?>">
            <div class="container">
                <ol id="breadcrumbs" itemscope itemtype="http://schema.org/BreadcrumbList"><?php breadcrumbs($breadcrumbs); ?></ol>   
            </div>
        </div>
    <?php } ?>
</header> 