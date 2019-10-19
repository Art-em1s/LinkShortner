<?php
if (session_status() == PHP_SESSION_NONE) {session_start();} //check if session is started, if not, start

require_once('assets/misc_functions.php');


logErrors();
logUserVisit();
rateLimit();

echo("
<html lang='en'>
<!-- Feel free to copy this if you wish. It's really fucking simple bootstrap with 10 mins worth of edits and some colorful javascript -->
<head>
    <link rel='icon' type='image/png' href='../favicon.png'>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    
    <meta property='og:title' content='Artemis' Website' />
    <meta property='og:image' content='https://w1z0.xyz/assets/i/pfp.gif'>
    <meta property='og:url' content='https://w1z0.xyz'>
    <title>Artemis' Site</title>
    <link href='../../assets/css/bootstrap.min.css' rel='stylesheet'>
    <link href='../../assets/css/font-awesome.min.css' rel='stylesheet'>
    <link href='../../assets/css/custom.css' rel='stylesheet'>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css'>
</head>

<body class='wrapper'>
    <div class='main'>
        <div class='modal-dialog modal-sm element'>
            <div class='modal-content'>
                <div class='modal-header text-center'>
                    <a href='https://w1z0.xyz'>
                    <img src='../../assets/i/pfp.gif' alt='Artemis' style='width:200px;height:200px;border-radius:50%;' href='https://w1z0.xyz'></a>
                    <span class='modal-title'>
                        <span style='font-size:25px;'>
                            <a href='https://w1z0.xyz'><p class='font'>URL Shortner</p></a>
                        </span>
                    </span>
                </div>
                <div class='modal-body text-center'>
                    <span>
                        <input type='text' id='url' placeholder='Enter URL here'>
                        <input type='submit' value='Submit' onclick='putRedirect()'>
                    </span><br>
                    <span id='redirect-message'></span>
                </div>
                <div class='modal-footer' style='text-align: center;'>
                    <span>
                        <small>Copyright <i class='fa fa-copyright'></i> <script type='text/javascript'>document.write(new Date().getFullYear());</script> - <a href='https://w1z0.xyz' class='font'>https://w1z0.xyz</a></small>
                    </spam>
                </div>
            </div>
        </div>
    </div>
<div class='filler'></div>
    <script src='../../assets/js/jquery.min.js'></script>
    <script src='../../assets/js/bg.js' type='text/javascript'></script>
    <script src='assets/main.js' type='text/javascript'></script>
</body>
</html>");

?>