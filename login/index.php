<?php

if (isset($_COOKIE["per_username"]) || isset($_COOKIE["tem_username"])) {
    header("location: .."); //User will be redirected to homepage if he/she is already logged in
} else {
    header("location: login.php"); //redirects user to login page
}

?>
