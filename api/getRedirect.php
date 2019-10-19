<?php
if (session_status() == PHP_SESSION_NONE) {session_start();} //check if session is started, if not, start

require_once('../assets/misc_functions.php');


logErrors();
verifyLegitUser();
logUserVisit();
rateLimit();

if (isset($_GET['a']) && strlen($_GET['a'])==10){
    $input = $_GET['a'];
    echo getRedirect($input);
} else {
    if (!isset($_GET['a'])){
        echo returnAPI("Error", "URL not supplied.");
        exit;
    }
    if (strlen($_GET['a'])!=10){
        echo returnAPI("Error", "Incorrect URL provided. (0)");
        exit;
    }
}