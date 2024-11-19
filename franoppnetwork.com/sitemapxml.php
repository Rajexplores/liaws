<?php
include_once('includes/global.php');
$protocol = 'https://';
$pages = '';
$domain_url = $protocol.$_SERVER['SERVER_NAME'];

$pages .= "\t<url>\r\n\t\t<loc>".$domain_url.".php</loc>\r\n\t<lastmod>".date(DATE_ATOM)."</lastmod>\r\n\t<priority>1.00</priority>\r\n\t</url>\r\n";
foreach (['profiles'=>'0.80','testimonials'=>'0.80','media'=>'0.80','contact-us'=>'0.80','newclient'=>'0.80','leadprocess'=>'0.80','media-kit'=>'0.64'] as $key => $value) {
    $priorities = "<lastmod>".date(DATE_ATOM)."</lastmod>\r\n\t<priority>".$value."</priority>\r\n\t";
    $pages .= "\t<url>\r\n\t\t<loc>".$domain_url."/".$key."</loc>\r\n\t".$priorities."</url>\r\n";
}

// Send the headers
header("Content-type: text/xml");
echo "<?xml version='1.0' encoding='UTF-8'?>\r\n";
?>
<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php echo $pages; ?>
</urlset>