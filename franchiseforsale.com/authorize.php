<!doctype html>
<html lang="en-US" translate="no">
    <head>
    <title>Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0,maximum-scale=5.0"/>
    <link rel="preconnect" href="//fonts.gstatic.com">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=Raleway&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" media="screen" href="/css/login.css?random=<?php echo mt_rand(100000, 999999);?>"/>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
</head> 
    <body>
        <div id="login">
            <div class="shell">
                <form class="form-signin" role="form" action="/" method="post">
                <i class="fas fa-user-lock"></i>
                    <div>
                        <label>Keycode</label>
                        <input type="text" name="auth" value="" placeholder="Enter Keycode" require>
                    </div>
                    <button class="btn btn-lg btn-primary btn-block" type="submit" name="login">Submit</button>
                </form>
            </div>
        </div>
    </body>
</html>