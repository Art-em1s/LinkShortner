<?php
//doing this as a seperate page allows me to avoid having to add nginx page rewrites, it's also not much more work

if (session_status() == PHP_SESSION_NONE) {session_start();} //check if session is started, if not, start

require_once('assets/misc_functions.php');

logErrors();
logUserVisit();
rateLimit();

if (isset($_GET['a'])){
    $redirect = "https://w1z0.xyz/short/api/getRedirect.php?a=".$_GET['a'];
    echo("
    <script>
        var data = null;
        var xhr = new XMLHttpRequest();
        xhr.withCredentials = true;
        xhr.addEventListener('readystatechange', function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                data = JSON.parse(xhr.response);
                if (data['URL']){
                    location.href = data['URL'];
                } else { //if there's an error, display the message in an alert, once the popup closes, redirect to the new page
                    alert(data['Error']);
                    window.location.href = 'new.php';
                }
            }
        });
        xhr.open('GET', '".$redirect."');
        xhr.send(data);
        
    </script>");
} else {
    header( "Location: https://w1z0.xyz/short/new.php");
}
?>