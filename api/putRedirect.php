<?php
if (session_status() == PHP_SESSION_NONE) {session_start();} //check if session is started, if not, start

require_once('../assets/misc_functions.php');


logErrors();
verifyLegitUser();
logUserVisit();
rateLimit();

if (isset($_GET['a'])&&!empty($_GET['a'])){
    $input = $_GET['a'];
    
    //url verification here and in function
    verifyURL($input);
    echo putRedirect($input);
} else {
    $input = $_GET['a'];
    if (!isset($input)){
        echo returnAPI("Error", "URL not supplied.");
        exit;
    }
    if (empty($input)){ //checks for spaces/other empty inputs
        echo returnAPI("Error", "You provided a 0 character URL.");
        exit;
    }
}