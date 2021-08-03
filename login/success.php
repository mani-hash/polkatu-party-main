<?php
session_start();
ob_start();

//creates cookies with a time limit or none depending on whether the user checked "remember me" option

if (isset($_SESSION["rem_pass"])) {
    setcookie("per_username", $_SESSION["polkatu_username"], time() + (86400 * 30), "/"); 
    setcookie("per_ver", $_SESSION["cce_hid"], time() + (86400 * 30), "/");
} elseif (isset($_SESSION["non_rem_pass"])) {
    setcookie("tem_username", $_SESSION["polkatu_username"], "", "/");
    setcookie("tem_ver", $_SESSION["cce_hid"], "", "/");
}

?>

<html>
    <head>
        
        <meta name="viewport" content="width=device-width initial-scale=1">
        <script src="../sweetalert2@10.js"></script>
        <script> 
            function alert2() {
                Swal.fire({
                    icon: 'success',
                    title: 'Account created successfully!',
                    text: 'Login to your account and start partying!',
                    showConfirmButton: 'true',
                    confirmButtonText: 'Click here to login',
                    confirmButtonColor: '#00e600'
                }).then((result) => {
                    if (result) {
                        localStorage.setItem("rememberButtonChoice", 'loginChoice');
                        location.href = "login.php";
                    } 
                }); 
            }

            function alert9() {
                Swal.fire({
                    icon: 'success',
                    title: 'Password changed successfully!',
                    text: 'Login using your new password to continue using your account',
                    showConfirmButton: 'true',
                    confirmButtonText: 'Click here to login',
                    confirmButtonColor: '#00e600'
                }).then((result) => {
                    if (result) {
                        localStorage.setItem("rememberButtonChoice", 'loginChoice');
                        location.href = "login.php";
                    } 
                }); 
            }
        </script>
    </head>
        
    
        <?php 
            if (isset($_SESSION["full_validUser"])) {
                echo "<body onload='alert2()'></body>"; // displays sweet alert pop up message on successfull registration

            } elseif (isset($_SESSION["availableUser"])) {
                header("location: ..");    // redirects user to homepage on successfull login
            
            } elseif (isset($_SESSION["is_fullValidReset"])) {
                echo "<body onload='alert9()'></body>";
            } else {
                /*redirects user to login page if somehow user tries to access this site 
                by entering the file name in URL*/
                header("location: .");
            }
        ?>
</html>