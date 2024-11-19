<?php 
include_once('includes/global.php');

$tips_and_tools = true;
$filter_exclude = true;
$email_path = '/contact-us';
$footer_name = 'FranchiseForSale.com';
$tip_url = 'https://admin.franchiseventures.com/globals/tips_and_tools.php?email_path='.$email_path.'&footer_name='.$footer_name;
$_REQUEST['dev-live'] = explode(".",$_SERVER['SERVER_NAME']);
if (strpos($_SERVER['SERVER_NAME'], "franchiseportals") !== false){
    $tip_url = 'http://steven.admin.franchiseventures.com/globals/tips_and_tools.php?email_path='.$email_path.'&footer_name='.$footer_name; 
}
$tip_content = file_get_contents($tip_url) or die("Error: Cannot create object");

?>
<!doctype html>
<html lang="en-US" translate="no">
<?php include_once('includes/head.php'); ?> 
    <body>
        <?php include_once('includes/header.php'); ?>
            <?php echo $tip_content; ?>
        <?php include_once('includes/footer.php'); ?>
        <script>
            var footer = document.getElementById('footer');
            footer.remove();
        </script>
    </body>
</html>