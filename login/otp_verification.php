<?php
session_start();
ob_start(); 

include "db_config.php";

?>

<html>
    <head>
        <title>OTP verify</title>
        <script src='../sweetalert2@10.js'></script>
        <script>
            function alert3() {
                Swal.fire({
                    icon: 'warning',
                    title: 'OTP verification timeout!',
                    text: 'For security reasons, we do not allow users to take more than 10 minutes to verify their e-mail address. Please go to register page and try again',
                    showConfirmButton: 'true',
                    confirmButtonText: 'Go to register page',
                    confirmButtonColor: '#00e600'
                }).then((result) => {
                    if (result) {
                        localStorage.setItem('rememberButtonChoice', 'registerChoice');
                        location.href = 'login.php';
                    } 
                }); 
            }
        </script>
        <meta name="viewport" content="width=device-width initial-scale=1">

    <head>
    
    <body>

        <?php

            if (!isset($_SESSION["half_validUser"])) {

                /*redirects user to login page if user tries to manually access this file
                by entering the filename in the URL*/

                header("location: .");   

            }

            if ($_SESSION["otp_time"] + 600 <= time()) {
                /*displays sweet alert pop up when user takes more than 10 minutes 
                to verify their e-mail*/

                echo "<script>alert3()</script>"; 
    
    
            } elseif (isset($_POST["resend"])) {
                //redirects user to sendmail.php if user clicks on resend email button
                $_SESSION["resend_done"] = "msg_sent_twice"; 
                $_SESSION["otp_token"] = "exists";
                header("location: sendmail.php");
    
            } else {

                //verifies OTP pin entered by user
    
                if ($_POST["otp"] == $_SESSION["rand_gen"]) {

                    include "browser.php";  //browser class to detect user browser, os info
                    $reg_username = $_SESSION["otp_usrname"];
                    $reg_simple_username = $_SESSION["otp_low_usrname"];
                    $reg_email = $_SESSION["otp_usr_email"];
    
                    $hashed_reg_password = $_SESSION["hashed_pass"];
                    $register_ip_address = $_SESSION["ip_addr"];
                    $user_activation_code = $_SESSION["activ_usr_code"];
                    $date_registered = $_SESSION["date_now"];

                    $_SESSION["full_validUser"] = "exists"; 

                    header("location: success.php"); //redirects user to success page upon successfull verification
                } else {
                    //goes back to otp page upon unsuccessfull verification
                    
                    $_SESSION["otp_error"] = "<p class='error'><img class='img_error' src='images/error.svg'>OTP pin is incorrect!</p>";
                    header("location: otp_main.php");
            
                }

                include "db_reg_edit.php";
                include "db_close.php";
    
            }
        ?>

    </body>
</html>