<?php
include "login/db_config.php";

//validates if the currently logged in user has a valid session_verifer
//Prevents users from spoofing other user accounts 

if (isset($_COOKIE["per_username"])) { //for users logged in with remember me option

   //verifies if the session_verifier exists in the database

   $get_cookie1_query = $connected->prepare("SELECT username_display, session_verifier FROM user_accounts, 
   login_logs WHERE user_accounts.User_ID = login_logs.user_ID_l AND session_verifier = ?");

   $get_cookie1_query->bind_param('s', $_COOKIE["per_ver"]);
   $get_cookie1_query->execute();
   $get_cookie1_result = $get_cookie1_query->get_result();

   $get_cookie1 = $get_cookie1_result->fetch_array(MYSQLI_ASSOC);

   if (!isset($get_cookie1["session_verifier"]) || $get_cookie1["username_display"] != $_COOKIE["per_username"]) {

      //Logs the user out if either session_verifier or username doesn't match

      logout();
      header("location: .");
   }

} elseif (isset($_COOKIE["tem_username"])) {  //for users logged in temporarily

   //The procedure is similar as mentioned in the above block of code
   
   $get_cookie2_query = $connected->prepare("SELECT username_display, session_verifier FROM user_accounts,
   login_logs WHERE user_accounts.User_ID = login_logs.user_ID_l AND session_verifier = ?");

   $get_cookie2_query->bind_param('s', $_COOKIE["tem_ver"]);
   $get_cookie2_query->execute();
   $get_cookie2_result = $get_cookie2_query->get_result();

   $get_cookie2 = $get_cookie2_result->fetch_array(MYSQLI_ASSOC);

   if (!isset($get_cookie2["session_verifier"]) || $get_cookie2["username_display"] != $_COOKIE["tem_username"]) {
      logout();
      header("location: .");
   }
}

include "login/db_close.php";
?>