<?php
session_start();
ob_start();

include "db_config.php";

function val_email($email) {
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return 9;
    } else {
        return 1;
    }

}
?>

<html>
    <head>
        <script src="../sweetalert2@10.js"></script>
        <script>
            function alert7() {
                Swal.fire({
                    icon: 'warning',
                    title: 'OTP verification timeout!',
                    text: 'For security reasons, we do not allow users to take more than 10 minutes to verify their e-mail address during password reset process. Please go to login page and try again',
                    showConfirmButton: 'true',
                    confirmButtonText: 'Go to login page',
                    confirmButtonColor: '#00e600'
                }).then((result) => {
                    if (result) {
                        localStorage.setItem('rememberButtonChoice', 'loginChoice');
                        location.href = 'login.php';
                    } 
                }); 
            }
        </script>
    </head>

    <body>
        <?php

            if (isset($_POST["send_pin"])) {  //executes code when user clicks on request password reset
                $req_email = $_POST["request_email"];

                $isValid = val_email($req_email);

                if ($isValid == 9) { 
                    
                    //executes when email is valid

                    //searches if such an email exists in the database

                    $prep_email = $connected->prepare("SELECT user_ID, user_email, username_display FROM user_accounts WHERE user_email = ?");
                    $prep_email->bind_param('s', $req_email);
                    $prep_email->execute();
                    $get_email = $prep_email->get_result();

                    if ($get_email->num_rows != 0) { 

                        //executes when email exists in the database

                        $email_val1 = $get_email->fetch_array(MYSQLI_ASSOC);
                        $SESSION["ID_req_verified"] = $email_val1["user_ID"];
                        $_SESSION["email_req_verified"] = $email_val1["user_email"];
                        $_SESSION["user_req_verified"] = $email_val1["username_display"];
                        $main_err = 9; //value of this variable will decide the final code execution
                    } else {  

                        //assigns error messages to session variables if email isn't on the databse

                        $main_err = 1;
                        $_SESSION["email_inv_req"] = "<p class='error'><img class='img_error' src='images/error.svg'>Invalid email</p>";
                    }
        

        
                } else {
                    //assigns error messages to session variables if entered email is invalid
                    $_SESSION["email_inv_req"] = "<p class='error'><img class='img_error' src='images/error.svg'>Invalid email</p>";
                    $main_err = 1;

                }

    
            } elseif (isset($_POST["otp_submit2"])) { //executes code when user clicks on Verify My E-mail button
    
                if ($_SESSION["req_time"] + 600 <= time()) {
                    /*displays sweet alert pop up when user takes more than 10 minutes 
                    to verify their e-mail*/
                    $main_err = 6;
                    echo "<script>alert7()</script>"; 
                } else {
                    //code to be executed within the time limit

                    if ($_POST["otp2"] == $_SESSION["rand_gen2"]) { //verifies if the OTP pin matches
                        unset($_SESSION["email_exists_in_db"]);
                        $_SESSION["reset_pass_full_permit"] = "exists";
                        $main_err = 3;
                    } else {   //assigns error messages to session variables if OTP pin doesn't match
                        $_SESSION["otp_inv_req"] = "<p class='error'><img class='img_error' src='images/error.svg'>Invalid OTP pin</p>";
                        $main_err = 5;
                    }
                }

            } elseif (isset($_POST["resend2"])) { 

                //executes code when user clicks on Resend E-mail button

                $main_err = 7; 

            } elseif (isset($_POST["pass_reset"])) {

                //executes code when user clicks on reset password button

                $new_pass = $_POST["new_password"];
                $confirm_new_pass = $_POST["confirm_new_password"];

                if (preg_match("/[ ]/", $new_pass) == true) { 
                    
                    //checks if password contains any space and assigns error messages to session variables if true

                    $main_err = 1;
                    $_SESSION["wrong_new_pass"] = "<p class='error'><img class='img_error' src='images/error.svg'>Passwords cannot contain any spaces</p>";
                } elseif ($new_pass != $confirm_new_pass) {

                    //checks if the passwords in both the field matches

                    $main_err = 1;
                    $_SESSION["wrong_new_confirm_pass"] = "<p class='error'><img class='img_error' src='images/error.svg'>Passwords doesn't match</p>";
                } else {

                    //executes this code if the validation process of password is successfull

                    $hashed_new_pass = password_hash($new_pass, PASSWORD_DEFAULT); //hashes the new password

                    //updates the new password to the database. The old password is automatically removed/overwritten!
                    $reset_prep = $connected->prepare("UPDATE user_passwords SET passwords = ? WHERE user_email_p = ?");
                    $reset_prep->bind_param('ss', $hashed_new_pass, $_SESSION["email_req_verified"]);
                    $reset_prep->execute();
                    $main_err = 4;
                }
            } else { 
                
                //executes code if user tries to access this file manually by entering the filename in the URL

                $main_err = 2;

            }

            //executes the final code depending on the value of the variable

            if ($main_err == 9) {

                $_SESSION["reset_pass"] = "exists";
                $_SESSION["first_req"] = "exists";
                header("location: sendmail.php");

            } elseif ($main_err == 1 || $main_err == 5 || $main_err == 3) {

                header("location: forgot_password.php");

            } elseif ($main_err == 2) {

                header("location: .");

            } elseif ($main_err == 7) {

                $_SESSION["reset_pass"] = "exists";
                $_SESSION["second_req"] = "exists";
                header("location: sendmail.php");

            } elseif ($main_err == 4) {

                $_SESSION["is_fullValidReset"] = "exists";
                header("location: success.php");

            } elseif ($main_err == 6) {

                echo "<script>alert7()</script>"; 

            } 


            include "db_close.php";


        ?>
    </body>
</html>