<?php
session_start();

if (isset($_SESSION["otp_usr_email"]) && isset($_SESSION["otp_low_usrname"]) && isset($_SESSION["otp_usrname"]) &&
isset($_SESSION["half_validUser"])) {

    $no_problem = "yes"; 
   

    
} else {
    /*redirects user to login page if user tries to manually access this file
    by entering the filename in the URL*/

    header("location: ."); 
}

?>

<html>
    <head>
        <meta name="viewport" content="width=device-width initial-scale=1">
        <title>Verify your email</title>
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
            <div class="otp_form_box">

                <img id="otp_img" src="../Images/otp_coconut.png">

                <form name="email_verify" action="otp_verification.php" method="POST" class="otp-input">
                    <?php 
                        if (isset($_SESSION["otp_error"])) {
                            echo $_SESSION["otp_error"]; 
                        } else {
                            echo "";
                        } 
                    ?>

                    <input type="text" name="otp" id="otp_id" class="otp_input_field"  placeholder="Enter OTP pin.." 
                    maxlength="8" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter OTP pin..'">

                    <br>
                    <br>
                    <br>
                    <?php
                        //changes position of verify my email button
                        
                        if (isset($_SESSION["disable_resend"])) {
                            echo "";
                        } else {
                            echo "<input type='submit' name='otp_submit' value='Verify my e-mail' class='otp-btn'>";
                        }
                    ?>
                    <br>
                    <br>

                    <?php 
                        //this if else statement hides the resend email button if user has already clicked it

                        if (isset($_SESSION["disable_resend"])) {
                            echo "<input type='submit' name='otp_submit' value='Verify my e-mail' class='otp-btn'>";
                        } else {
                            echo "<input type='submit' name='resend' value='Resend E-mail' class='otp-btn'>";
                        }
                    ?>
                </form>
            </div>
        </div>
    </body>

   <?php 
        //unsets the error message so it doesn't appear on refresh!
        unset($_SESSION["otp_error"]); 

        //this session variable needs to be unset orelse user can spam emails by going to previous page!
        unset($_SESSION["otp_token"]); 

    ?>


</html>