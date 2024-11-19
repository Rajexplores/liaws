<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0,maximum-scale=5.0"/>
    <title><?php echo $meta[$section]['title']; ?></title>
    <meta name="description" content="<?php echo $meta[$section]['description']; ?>"/>
    <meta name="keywords" content="<?php echo $meta[$section]['keywords']; ?>">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" media="all" href="/css/style.css?random=<?php echo mt_rand(100000, 999999);?>" />
    <link rel="apple-touch-icon" sizes="180x180" href="/images/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/images/favicons/favicon-16x16.png">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <meta name="robots" content="<?php echo $global_robots; ?>">
    <link rel="canonical" href="/">
    <?php if(isset($splide)){ ?>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@latest/dist/css/splide.min.css">
        <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@latest/dist/js/splide.min.js"></script>
    <?php } ?>
</head>