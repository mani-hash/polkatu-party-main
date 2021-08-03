<?php
session_start();
ob_start();

?>

<html>
    <head>
      <title>Sending verification..</title>
      <script src='../sweetalert2@10.js'></script>
        <script>
            function alert4() {
                Swal.fire({
                    icon: 'warning',
                    title: 'An unknown error occured',
                    text: 'We are unable to send the email due to some unknown error. Please check your internet connection or try again',
                    showConfirmButton: 'true',
                    confirmButtonText: 'Try again',
                    confirmButtonColor: '#FF0000'
                }).then((result) => {
                    if (result) {
                        localStorage.setItem('rememberButtonChoice', 'registerChoice');
                        location.href = 'login.php';
                    } 
                }); 
            }

            function alert5() {
                Swal.fire({
                    icon: 'warning',
                    title: 'An unknown error occured',
                    text: 'We are unable to re-send the email due to some unknown error. Please check your internet connection!',
                    showConfirmButton: 'true',
                    confirmButtonText: 'Go back',
                    confirmButtonColor: '#FF0000'
                }).then((result) => {
                    if (result) {
                        location.href = 'otp_main.php';
                    } 
                }); 
            }

            function alert6() {
                Swal.fire({
                    icon: 'warning',
                    title: 'An unknown error occured',
                    text: 'We are unable to send the email due to some unknown error. Please check your internet connection!',
                    showConfirmButton: 'true',
                    confirmButtonText: 'Go back',
                    confirmButtonColor: '#FF0000'
                }).then((result) => {
                    if (result) {
                        location.href = 'forgot_password.php';
                    } 
                }); 
            }

            function alert8() {
                Swal.fire({
                    icon: 'warning',
                    title: 'An unknown error occured',
                    text: 'We are unable to re-send the email due to some unknown error. Please check your internet connection!',
                    showConfirmButton: 'true',
                    confirmButtonText: 'Go back',
                    confirmButtonColor: '#FF0000'
                }).then((result) => {
                    if (result) {
                        location.href = 'forgot_password.php';
                    } 
                }); 
            }
            
        </script>
    </head>

<?php

//uses PHPmailer classes to send emails

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//the following files *must* exist in their respective directories for the code to be executed!
    
require "class/PHPMailer/src/Exception.php";
require "class/PHPMailer/src/PHPMailer.php";
require "class/PHPMailer/src/SMTP.php";

$email = new PHPMailer(true);  //creates a new object

$email->setFrom('manimehalan395@gmail.com', 'Polkatu Party');   //sets the email and name to be displayed

/*sets the e-mail body to accept html attributes. if set to false the html attributes will 
be displayed in the e-mail too*/

$email->isHTML(true);

$email->isSMTP();  //sets e-mail to be sent through an SMTP server

$email->Host = "";  //sets the smtp host

$email->SMTPAuth = TRUE;  

$email->SMTPSecure = 'tls';  //sets encryption

//username and password are required to send email through smtp server

$email->Username = '';  

$email->Password = '';

$email->Port = 587;  //sets the smtp port

//The following if else statement is only executed if the necessary session variables are set
//if not the user is redirected back to the login page

if (isset( $_SESSION["half_validUser"]) && $_SESSION["otp_token"] == "exists") {


    if (isset($_SESSION["not_resend"]) && !isset($_SESSION["resend_done"])) {  
        try {
            $_SESSION['rand_gen'] = mt_rand(10000000, 99999999);  //generates a 8 character long OTP pin

               

            $email->Subject   = 'Email verification';   //sets the subject of the e-mail

            //The below code sets the body/message to be displayed in the e-mail
            //if the "$email->isHTML" is set to false, the html attributes will be displayed in the email too!

            $email->addAddress($_SESSION["otp_usr_email"]); //adds the address to which the email should be sent

            $email->Body      = "Hi " . $_SESSION['otp_usrname'] . ", " .  "<br><p> Thank you for
            registering to Polkatu Party. Please verify your email-address by entering this OTP pin in
            the &quot;Enter OTP pin&quot; input field.</p> <br> <b>Your otp pin for verification:</b>  " . $_SESSION['rand_gen'] . 
            "<br><br> Please ignore this e-mail if you haven't registered for Polkatu Party";

            

            if  ($email->Send()) {
                $_SESSION["otp_time"] = time();
                header("location: otp_main.php"); //redirects user to otp_main.php page if email is sent
            } 

        } catch (Exception $e) {
            echo "<body onload='alert4()'></body>";
   
        }

    } elseif (isset($_SESSION["not_resend"]) && isset($_SESSION["resend_done"])) {

        //this block of code is executed if user clicks on resend e-mail in otp_main.php 

        try {

            $random_resend = mt_rand(10000000, 99999999);

            $email->Subject   = 'Email verification - (Resent)';

            $email->Body      = "Hi " . $_SESSION['otp_usrname'] . ", " .  "<br><p> Thank you for
            registering to Polkatu Party. Please verify your email-address by entering this OTP pin in
            the &quot;Enter OTP pin&quot; input field. </p> <br> <b>Your otp pin for verification:</b>  " . $random_resend . 
            "<br>  <p>[NOTE: This email has been re-sent, hence ignore the first e-mail if you recieved it
            late!]</p> <br> Please ignore this e-mail if you haven't registered for Polkatu Party";

            $email->addAddress($_SESSION["otp_usr_email"]);

            if  ($email->Send()) {
                $_SESSION["rand_gen"] = $random_resend;
                $_SESSION["disable_resend"] = "now_set";
                $_SESSION["otp_time"] = time();
                header("location: otp_main.php");
            } 

        } catch (Exception $f) {
            echo "<body onload='alert5()'></body>";
   
        }

    }

} elseif (isset($_SESSION["reset_pass"])) {
    if ($_SESSION["reset_pass"] == "exists" && isset($_SESSION["first_req"])) {
        try {
            $_SESSION["rand_gen2"] = mt_rand(10000000, 99999999);

            $email->Subject   = 'Reset account password!';

            $email->Body = "Hi " . $_SESSION["user_req_verified"] . "," . "<br><br> <p>A request has been made to 
            reset the password of your Polkatu Party account. Please verify if it&rsquo;s you by entering this OTP pin in the
            the &quot;Enter OTP pin&quot; input field.</p> <b>OTP pin for verification: </b>" . $_SESSION["rand_gen2"] . 
            "<br><br>  Please ignore this e-mail if you haven't requested for password reset";

            $email->addAddress($_SESSION["email_req_verified"]);

            if ($email->Send()) {
                $_SESSION["email_exists_in_db"] = "success";
                $_SESSION["req_time"] = time();
                header("location: forgot_password.php");


            }


        } catch (Exception $l) {
            echo "<body onload='alert6()'></body>";
        }

    } elseif ($_SESSION["reset_pass"] == "exists" && isset($_SESSION["second_req"])) {
        try {
            $random_resend2 = mt_rand(10000000, 99999999);

            $email->Subject   = 'Reset account password! - (Resent)';

            $email->Body = "Hi " . $_SESSION["user_req_verified"] . "," . "<br><br> <p>A request has been made to 
            reset the password of your Polkatu Party account. Please verify if it&rsquo;s you by entering this OTP pin in the
            the &quot;Enter OTP pin&quot; input field.</p> <b>OTP pin for verification: </b>" . $random_resend2 . 
            "<br> <p>[NOTE: This email has been re-sent, hence ignore the first e-mail if you recieved it
            late!]</p> <br> Please ignore this e-mail if you haven't requested for password reset";

            $email->addAddress($_SESSION["email_req_verified"]);

            if ($email->Send()) {
                $_SESSION["rand_gen2"] = $random_resend2;
                $_SESSION["req_time"] = time();
                $_SESSION["disable_resend2"] = "exists";
                header("location: forgot_password.php");

            }
        } catch (Exception $m) {
            echo "<body onload='alert8()'></body>";
        }
    }

} else {
    /*redirects user to login page if user tries to manually access this file
    by entering the filename in the URL*/
    header("location: ."); 
}


?>

</html>