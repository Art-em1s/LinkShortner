<?php
    $dbLocation = '/home/bot/database/linkShortner.db';
    
    function verifyLegitUser(){
        if (!isset($_COOKIE['PHPSESSID'])||!isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])){
            http_response_code(403); //phpsessid isn't set for direct api requests, block these users accessing the page
        }
    }
    
    function logUserVisit(){
        global $dbLocation;
        $requestTime = $_SERVER['REQUEST_TIME']; //time request is made
        $remoteAddr = $_SERVER['HTTP_CF_CONNECTING_IP']; //connecting IP, can't use remote_addr as that's proxied cf addr
        $forwardedFor = $_SERVER['HTTP_X_FORWARDED_FOR']; //connecting IP, can't use remote_addr as that's proxied cf addr
        $session = $_COOKIE['PHPSESSID']; //not really important, sessions expire after a month
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        $userLang = $_SERVER['HTTP_ACCEPT_LANGUAGE']; //no real reason to log, just curious
        $requestURL = $_SERVER['REQUEST_URI']; //requested url, ie '/short/var.php'
        $referrer = (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : Null; //log where they're coming from, if possible
        
        $db = new SQLite3($dbLocation);
        $db->exec("INSERT INTO visitors (time, remote_addr, x_forwarded_for, session, user_agent, lang, url, referrer) VALUES ('$requestTime','$remoteAddr','$forwardedFor','$session','$userAgent','$userLang','$requestURL','$referrer');");
        $db->close();
        $db = NULL;
    }
    
    function returnAPI($key, $value){
        return json_encode(array($key=>$value));
    }
    
    function getRedirect($input){
        global $dbLocation;
        $db = new SQLite3($dbLocation);
        $stmt = $db->prepare("select redirect from url where short_url = ?");
        $stmt->bindValue(1, $input, SQLITE3_TEXT);
        $result = $stmt->execute();
        $row = $result->fetchArray();
        $db->close();
        $db = NULL;
        if (empty($row)){
            return returnAPI("Error", "Incorrect URL.");
        } else {
            return returnAPI("URL", $row['redirect']);
        }
    }
    
    function putRedirect($input){
        global $dbLocation;
        
        //to-do check for url already existing, return existing short if possible, reduce entries into db
        
        //needs url verification here JIC
        $redirectURL = substr(str_shuffle(MD5(microtime())), 0, 10);
        $db = new SQLite3($dbLocation);
        $stmt = $db->prepare("INSERT INTO url (time, short_url, redirect) VALUES (?, ?, ?)");
        $stmt->bindValue(1, time(), SQLITE3_TEXT);
        $stmt->bindValue(2, $redirectURL, SQLITE3_TEXT);
        $stmt->bindValue(3, $input, SQLITE3_TEXT);
        $stmt->execute();
        $db->close();
        $db = NULL;
        
        return returnAPI("URL", $redirectURL);
        //to-do: error logging here to check if the insert fails
    }
    
    function rateLimit(){ //to-do: currently not working, fix to prevent spam
        if (isset($_SESSION['LAST_CALL'])) {
            $last = strtotime($_SESSION['LAST_CALL']);
            $delta = abs($last - time());
            if ($delta <= 1) {
                echo returnAPI("Error", "Rate Limit Exceeded.");
                exit;
            }
        }
        $_SESSION['LAST_CALL'] = time();
    }
    
    function logErrors(){
        error_reporting(E_ALL);
        ini_set('ignore_repeated_errors', TRUE);
        ini_set('display_errors', FALSE); // don't display errors to end use
        ini_set('log_errors', TRUE);
        ini_set('error_log', '/var/log/w1z0.xyz/errors.log');
        ini_set('log_errors_max_len', 1024);
    }
    
    function verifyURL($input){
        if (strlen($input)<3){
            echo returnAPI("Error", "Invalid URL. Please double check the URL. The URL provided is too short.");
            exit;
        }
        if ($input == "%20"){
            echo returnAPI("Error", "Invalid URL. Please double check the URL. A space isn't a URL.");
            exit;
        }
        
        if (!filter_var($input, FILTER_VALIDATE_URL)) {
            echo returnAPI("Error", "Invalid URL. Please double check the URL. Be sure to include 'https://' or 'http://' at the start.");
            exit;
        }
    }
?>