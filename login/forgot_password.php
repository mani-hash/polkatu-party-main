<?php
session_start();



?>

<html>
    <head>
        <meta name="viewport" content="width=device-width initial-scale=1">
        <title>Forgot Password?</title>
        <link rel="stylesheet" href="CSS/stylesheet.css">
        <style>
            body {
                background-image: url("images/Background.png");
                background-repeat: no-repeat;
                background-attachment: fixed; 
                background-size: 100% 100%;
            }
        </style>
    </head>

    <body>
        <div class="hero">
            <div class="reset_pass_box">
                
                <?php
                   //This is a single page with 3 forms that appear as per changes in session variables
                   //All 3 forms are processed by reset_password.php

                    if (!isset($_SESSION["email_exists_in_db"]) && !isset($_SESSION["reset_pass_full_permit"])) {

                        //Displays this form to get user email info
                        
                        echo "<img class='forgot-pass mod1' src='images/forgot_pass.png'>"; //image for forgot password form >> enter email form

                        echo "<form name='request_reset' action='reset_password.php' method='POST' class='send-input'>";

                            if (isset($_SESSION["email_inv_req"])) { //display error message when email is invalid
                                echo $_SESSION["email_inv_req"];
                            } else {
                                echo "";
                            } 

                            echo "<input class='input-field' type='email' name='request_email' placeholder='Enter your email address' required>";
                            echo "<br>";
                            echo "<br>";
                            echo "<br>";
                            echo "<input class='submit-btn' type='submit' name='send_pin' value='Request password reset'>";

                        echo "</form>";

                    } elseif (isset($_SESSION["email_exists_in_db"]) && $_SESSION["email_exists_in_db"] == "success") {

                        //displays this form to verify user email by asking for OTP pin 

                        echo "<img class='forgot-pass mod2' src='images/verify_email.png'>"; //image for forgot password >> verify user email form

                        echo "<form name='verify_user' action='reset_password.php' method='POST' class='send-input'>";

                            if (isset($_SESSION["otp_inv_req"])) { //display error message when otp pin is invalid
                                echo $_SESSION["otp_inv_req"];
                            } else {
                                echo "";
                            }
                            
                            echo "<input type='text' name='otp2' id='otp_id' class='otp_input_field'  placeholder='Enter OTP pin..' 
                            maxlength='8' onfocus='plce2()' onblur='plce()'>";

                            echo "<br>";
                            echo "<br>";
                            echo "<br>";

                            if (isset($_SESSION["disable_resend2"])) { //Verify my e-mail button switches places with resend e-mail button after sending email for the second time
                                echo "";
                            } else {
                                echo "<input type='submit' name='otp_submit2' value='Verify my e-mail' class='otp-btn'>";
                            }
                            echo "<br>";
                            echo "<br>";
                            if (isset($_SESSION["disable_resend2"])) { //hides Resend e-mail button if user has already resent the e-mail
                                echo "<input type='submit' name='otp_submit2' value='Verify my e-mail' class='otp-btn'>";
                            } else {
                                echo "<input type='submit' name='resend2' value='Resend E-mail' class='otp-btn'>";
                            }

                        echo "</form>";

                    } elseif (isset($_SESSION["reset_pass_full_permit"])) {

                        //displays this form to get the newly entered user password

                        echo "<img class='forgot-pass mod3' src='images/change_password.png'>";

                        echo "<form name='reset_password' class='send-input' action='reset_password.php' method='POST'>";
                            
                            if (isset($_SESSION["wrong_new_pass"])) { //display error message when password is invalid(contains spaces)
                                echo $_SESSION["wrong_new_pass"];
                            } else {
                                echo "";
                            }

                            echo "<input id='reg_pass' name='new_password' type='password' class='input-field' placeholder='Enter Password' 
                            minlength='5' maxlength='60' required oninvalid='InvalidRegPass(this)' oninput='InvalidRegPass(this)'>";

                            echo "<br>";

                            if (isset($_SESSION["wrong_new_confirm_pass"])) { //display error message when password is invalid(passwords doesn't match)
                                echo $_SESSION["wrong_new_confirm_pass"];
                            } else {
                                echo "";
                            }

                            echo "<input id='reg_con_pass' name='confirm_new_password' type='password' class='input-field' placeholder='Re-Enter Password' 
                            maxlength='60' required oninvalid='InvalidConfirm(this)' oninput='InvalidConfirm(this)'>";

                            echo "<br>";

                            echo "<input name='show_pass' type='checkbox' onclick='visiblepass2()' class='check-box'><span class='show_res'> Show Password </span>";
                            echo "<br>";

                            echo "<input class='submit-btn' type='submit' name='pass_reset' value='Reset Password'>";

                        echo "</form>";
                    }

                        
                ?>
                    

                
                
            </div>
        </div>
        
    </body>
   <?php
      unset($_SESSION["email_inv_req"]); //needs to be unset or email error messages will stay on screen
      unset($_SESSION["otp_inv_req"]);   //needs to be unset or otp error messages will stay on screen
      unset($_SESSION["first_req"]);     //needs to be unset or user can spam emails
      unset($_SESSION["reset_pass"]);    //needs to be unset or user can spam emails
      unset($_SESSION["second_req"]);    //needs to be unset or user can re-send emails more than once
      unset($_SESSION["wrong_new_pass"]);    //needs to be unset or password error messages will stay on screen
      unset($_SESSION["wrong_new_confirm_pass"])  //needs to be unset or password error messages will stay on screen
   ?>

   <script src="buttons.js"></script>
   <script>
      //change otp pin placeholder values
       function plce() {
        document.getElementById('otp_id').placeholder = 'Enter OTP pin..';
       }

       function plce2() {
        document.getElementById('otp_id').placeholder = '';
       }
   </script>
  

</html>