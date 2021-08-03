<?php
session_start();
date_default_timezone_set("Asia/Colombo");
if (isset($_COOKIE["per_username"]) || isset($_COOKIE["tem_username"])) {
    header("location: ..");
}
?>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width initial-scale=1">
    <meta name="description" content="Polkatu Party is an official streaming platform meant primarily for
    hosting virtual parties">
    <meta name="keywords" content="Polkatu, party, video, social media, login, register, birthday, virtual">
    
    <title>Login and Registeration Form</title>

    <link rel="stylesheet" href="CSS/stylesheet.css">
    
    <style>
        body {
            background-image: url('images/Background.png');
            background-repeat: no-repeat;
            background-attachment: fixed; 
            background-size: 100% 100%;
        }
    </style>
     
</head>

    <body>
        <div class="hero">
            <div id="main-form" class="short-box">
               <div class="button-box">
                    <div id="btn"></div>
                        <button type="button" id="loginButton" class="toggle-btn" onclick="login()">Login</button> 
                        <button type="button" id="registerButton" class="toggle-btn" onclick="register()">Register</button>
                </div>

                <div class="social-icons">
                    <img id="ico1" src="images/fb.png">
                    <img id="ico2" src="images/gp.png">
                    <img id="ico3" src="images/tw.png">
                </div>
            
                <form method="POST" action="val_log.php" id="login" class="input-group">

                    <?php 
                        if (isset($_SESSION["login_error"])) {
                            //displays an error msg if username doesn't exist or if password is wrong
                            echo $_SESSION["login_error"]; 
                        }  else {
                            echo "";
                        }
                    ?>

                    <input name="Lname" type="text" class="input-field" placeholder="Username or E-mail" minlength="4" 
                    required oninvalid="InvalidLogUser(this)" oninput="InvalidLogUser(this)" 
                    value="<?php if (isset($_SESSION["tmpLoginName"])) {echo $_SESSION["tmpLoginName"];} else {echo "";} ?>">

                    <input id="log_pass" name="Lpassword" type="password" class="input-field" placeholder="Enter Password" minlength="5" 
                    maxlength="60" required oninvalid="InvalidLogPass(this)" oninput="InvalidLogPass(this)"> 

                    <input name="Lrempass" type="checkbox" class="check-box"><span> Remember Password</span>
                    <br>

                    <input name="show_pass" type="checkbox" onclick="visiblepass()" class="check-box2"><span class="show_pass_login"> Show Password </span>

                    <br>
                    <br>

                    <input type="submit" name="Lsubmit" class="submit-btn" value="Login">

                    <a href="forgot_password.php"><span class="for-pass">Forgot password?</span></a>

                </form>

                <form method="POST" action="val_reg.php" id="register" class="input-group">

                    <?php 
                        if (isset($_SESSION["errEmail"])) {
                            echo $_SESSION["errEmail"];  //displays an error msg if email is invalid
                        }  else {
                            echo "";
                        }
                    ?>

                    <?php 
                        if (isset($_SESSION["emailTaken"])) {
                            echo $_SESSION["emailTaken"];  //displays an error msg if email is used by another account
                        } else {
                            echo ""; 
                        }  
                    ?>

                    <input name="Rmail" type="email" class="input-field" placeholder="Email ID" required 
                    oninvalid="InvalidEmail(this)" oninput="InvalidEmail(this)"
                    value="<?php if (isset($_SESSION["otp_usr_email"])) {echo $_SESSION["otp_usr_email"];} else {echo "";} ?>">
             
                    <?php 
                        if (isset($_SESSION["errUser"])) {
                            echo $_SESSION["errUser"];   //displays an error message if username is invalid
                        }  else {
                            echo "";
                        }
                    ?>

                    <?php 
                        if (isset($_SESSION["nameTaken"])) {
                            echo $_SESSION["nameTaken"];  //displays an error msg if username is already taken
                        }  else {
                            echo "";
                        }
                    ?>
                
                    <input name="Rname" type="text" class="input-field" placeholder="User ID" minlength="4" 
                    maxlength="15" required oninvalid="InvalidRegUser(this)" oninput="InvalidRegUser(this)" 
                    value="<?php if (isset($_SESSION["otp_usrname"])) {echo $_SESSION["otp_usrname"];} else {echo "";} ?>">

                   <?php 
                        if (isset($_SESSION["errPass"])) {
                            echo $_SESSION["errPass"];   //displays an error message if password contains space
                        } else {
                            echo "";
                        }
                    ?>

                    <input id="reg_pass" name="Rpassword" type="password" class="input-field" placeholder="Enter Password" 
                    minlength="5" maxlength="60" required oninvalid="InvalidRegPass(this)" oninput="InvalidRegPass(this)">

                    <?php 
                        if (isset($_SESSION["errMissPass"])) {
                            echo $_SESSION["errMissPass"];   //displays an error message if passwords doesn't match
                        } else {
                            echo "";
                        }
                    ?>

                    <input id="reg_con_pass" name="REpassword" type="password" class="input-field" placeholder="Re-Enter Password" 
                    maxlength="60" required oninvalid="InvalidConfirm(this)" oninput="InvalidConfirm(this)">

                    <input name="Rterms" value="selected" type="checkbox" class="check-box" required>
                    <span> I agree to the terms & conditions</span >

                    <br>

                    <input name="show_pass" type="checkbox" onclick="visiblepass2()" class="check-box2"><span class="show_pass_login"> Show Password </span>

                    <br>
                    <br>

                    <input type="submit" class="submit-btn" name="Rsubmit" value="Register">

                </form>
            </div>
        </div>
    
        <script src="buttons.js"></script>
    
        <?php
            //unsets all the session variables so the error messages and user entered input are cleared on refresh

            session_unset(); 
    
        ?>
    
    </body>
</html>