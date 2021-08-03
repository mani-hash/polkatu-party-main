<?php
session_start();

date_default_timezone_set("Asia/Colombo");

include "db_config.php";


$logUsername = htmlspecialchars($_POST["Lname"]);
$logPassword = htmlspecialchars($_POST["Lpassword"]);
$loglow_Username = strtolower($logUsername);

//function to validate the ip address

function ip_validate($ip) {
    if (filter_var($ip, FILTER_VALIDATE_IP) == true) {
        return $ip;
    } else {
        return null;
    }
}

//prepares the sql statement and binds the variable without directly querying it
//This method is more sequre 

//This sql statement selects user_ID, username and activation code of the user from the database  
if (filter_var($loglow_Username, FILTER_VALIDATE_EMAIL) == true) {
    $log_query = $connected->prepare("SELECT user_ID, username_display, user_activation_code FROM user_accounts WHERE user_email = ?");
    $log_query->bind_param('s', $loglow_Username);
    $log_query->execute();
    $logchk = $log_query->get_result();
} else {
    $log_query = $connected->prepare("SELECT user_ID, username_display, user_activation_code FROM user_accounts WHERE username_verify = ?");
    $log_query->bind_param('s', $loglow_Username);
    $log_query->execute();
    $logchk = $log_query->get_result();
}


//fetches the values from database and assigns it to a variable

$logchk_value = $logchk->fetch_array(MYSQLI_ASSOC);
$login_ID = $logchk_value["user_ID"];
$login_display = $logchk_value["username_display"];
$cache1 = $logchk_value["user_activation_code"];


//this sql statement selects the hashed passwords of the user from the database

$pass_query = $connected->prepare("SELECT passwords FROM user_passwords WHERE user_ID_p = ?");
$pass_query->bind_param('s', $login_ID);
$pass_query->execute();
$pass_result = $pass_query->get_result();

//fetches the value(hashed password in this case) from the database and assigns it to a variable

$passchk = $pass_result->fetch_array(MYSQLI_ASSOC);
$final_password = $passchk["passwords"];

//verifies the user entered password with the hashed password

if (password_verify($logPassword, $final_password)) {
    $pass_verify = 9;
} else {
    $pass_verify = 1;
}

//this if else statement verifies if the username exists and if the password is correct
//if both conditions are true it queries the values into the respective tables

if ($logchk->num_rows != 0 && $pass_verify == 9) {
    header("location: success.php");

    $date_logged_in = date("Y-m-d H:i:s");
    $login_ip_address = ip_validate($_SERVER["REMOTE_ADDR"]);

    if (isset($_POST["Lrempass"])) {
        $_SESSION["rem_pass"] = "password_remembered";
    } else {
        $_SESSION["non_rem_pass"] = "password_not_remembered";
    }

    $rand_gen = mt_rand(100, 2147483647);
    $_SESSION["cce_hid"] = hash_hmac('sha256', $cache1, $rand_gen);
    $session_verifier = $_SESSION["cce_hid"];
    $_SESSION["polkatu_username"] = $login_display;

    include "browser.php";

    $loginfo_query = $connected->prepare("INSERT INTO login_logs(user_ID_l, date_l,
    ip_address_l, session_verifier, user_agent_l, reg_browser_l, browser_ver_l, OS_l, OS_arch_64_l,
    mobile_l, robot_l) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $loginfo_query->bind_param('ssssssssiii', $login_ID, $date_logged_in, $login_ip_address, $session_verifier,
    $req_userAgent, $req_userBrowser, $req_browserVer, $req_platformName, $platform64_bool, $mobile_bool, $robot_bool);

    $loginfo_query->execute();

    $_SESSION["availableUser"] = "exists";

    
} else {
    /*if either of the information(username or password) are false, it redirects the user
    back to login page and displays the error message*/
    
    header("location: login.php");

    $_SESSION["login_error"] = "<p class='error'>
    <img class='img_error' src='images/error.svg'>Invalid username or password</p>";
    $_SESSION["tmpLoginName"] = $logUsername;
}


include "db_close.php";


?>