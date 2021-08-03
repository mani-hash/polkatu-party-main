<?php
//establishes connection with the database

$servername = "localhost";
$username = "";
$password = "";
$database = "polkatu_party";
$connected = new mysqli($servername, $username, $password, $database);

//displays a warning if there is any error connecting to the database

if ($connected->connect_error) {
    echo "<script>alert('Error connecting to database!')</script>";
} 




?>