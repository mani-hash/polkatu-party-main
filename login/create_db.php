<?php
session_start();
?>
<html>
    <head>
        <title>Create Database</title>
    </head>

    <body>
        <h3>FOR DEVELOPER USE ONLY!</h3>
        <p>NOTE: This PHP script is only meant for developer use. This script automatically creates the necessary
        databases and tables required for the functioning of the website. This script is not necessary for the 
        regular functioning of the website. Use this only to create the database or verify if the database exists.
        This script should be removed after hosting the website and creating the necessary databases and tables.</p>
        <p>Enter MYSQL server login info:</p>
        <p>NOTE: The default info is already filled in! Click on &quot;create database&quot;
        to automatically create the required tables. 
        <br>
        <b>Enter different info only if there is any problem 
        connecting to mysql server or if you encounter any errors in creating database!</b></p>
        <br>

        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
           
            <span>Enter host name: </span><input name="server_name" type="text" required value="localhost" placeholder="default value is localhost">
            <br>
            <br>
            <span>Enter user name: </span><input name="user_name" type="text" required value="root" placeholder="default value is root">
            <br>
            <br>
            <span>Enter password: </span><input name="password" type="text" placeholder="default value is nothing">
            <br>
            <br>
            <span>Enter port number: </span><input name="port" type="text" placeholder="default value is nothing">
            <br>
            <br>
            <input type="submit" name="create" value="create database">

        </form>
    </body>
</html>


<?php

function create_db($con) {
    $db_create = "CREATE DATABASE polkatu_party";
    $con->query($db_create);
    if ($con->select_db("polkatu_party")) {
       echo "Database has been successfully created";
       
    } else {
        throw new Exception("An unexpected error occured when creating the database");
    }
}

function create_user_accounts($con1) {
    $table1_create = "CREATE TABLE polkatu_party.user_accounts (
        user_ID CHAR(10),
        account_no INT(10) AUTO_INCREMENT UNIQUE,
        username_display VARCHAR(15) NOT NULL,
        username_verify VARCHAR(15) NOT NULL,
        user_activation_code CHAR(40) NOT NULL,
        user_email VARCHAR(100) NOT NULL,
        PRIMARY KEY(User_ID)  
    );";

    if ($con1->query($table1_create) == true) {
        echo "user_accounts table has been successfully created!";
        $_SESSION["s1"] = "exists";
        
    } else {
        throw new Exception("An unexpected error occured when creating user_accounts table");
    }
}

function create_registration_info($con2) {
    $table2_create = "CREATE TABLE polkatu_party.registration_info (
        reg_no INT(10) AUTO_INCREMENT UNIQUE,
        user_email_r VARCHAR(100), 
        date_r DATETIME,
        ip_address_r VARCHAR(60),
        user_agent_r VARCHAR(200),
        reg_browser_r VARCHAR(20),
        browser_ver_r VARCHAR(20),
        OS_r VARCHAR(30),
        OS_arch_64_l BOOLEAN,
        mobile_r BOOLEAN,
        robot_r BOOLEAN,
        PRIMARY KEY(user_email_r)
    );";

    if ($con2->query($table2_create) == true) {
        echo "registration_info table has been successfully created!";
        $_SESSION["s2"] = "exists";
    } else {
        throw new Exception("An unexpected error occured when creating registration_info table");
    }
}

function create_user_passwords($con3) {
    $table3_create = "CREATE TABLE polkatu_party.user_passwords (
        user_ID_p CHAR(10),
        user_email_p VARCHAR(100),
        passwords VARCHAR(100) NOT NULL,
        PRIMARY KEY(user_ID_p, user_email_p),
        FOREIGN KEY(user_ID_p) REFERENCES polkatu_party.user_accounts(user_ID),
        FOREIGN KEY(user_email_p) REFERENCES polkatu_party.registration_info(user_email_r)
    );";

    if ($con3->query($table3_create) == true) {
        echo "user_passwords table has been successfully created";
        $_SESSION["s3"] = "exists";
    } else {
        throw new Exception("An unexpected error occured when creating user_passwords table");
    }
}

function create_login_logs($con4) {
    $table4_create = "CREATE TABLE polkatu_party.login_logs (
        log_no INT(20) AUTO_INCREMENT,
        user_ID_l CHAR(10) NOT NULL,
        date_l DATETIME,
        ip_address_l VARCHAR(60),
        session_verifier VARCHAR(64),
        user_agent_l VARCHAR(200),
        reg_browser_l VARCHAR(20),
        browser_ver_l VARCHAR(20),
        OS_l VARCHAR(30),
        OS_arch_64_l BOOLEAN,
        mobile_l BOOLEAN,
        robot_l BOOLEAN,
        PRIMARY KEY(log_no),
        FOREIGN KEY(user_ID_l) REFERENCES polkatu_party.user_accounts(user_ID)
    );";

    if ($con4->query($table4_create) == true) {
        echo "login_logs table has been successfully created";
        $_SESSION["s4"] = "exists";
    } else {
        throw new Exception("An unexpected error occured when creating login_logs table");
    }
}

function connection_no_port($t1, $t2, $t3) {
    if (!$main_con1 = new mysqli($t1, $t2, $t3)) {
        throw new Exception("Error establishing connection");
    }
    return $main_con1;
}

function connection_yes_port($t4, $t5, $t6, $t7, $t8) {
    if (!$main_con2 = new mysqli($t4, $t5, $t6, $t7, $t8)) {
        throw new Exception("Error establishing connection");
    }
    return $main_con2;
}



if (isset($_POST["create"])) {
    $tmp_ws_server = $_POST["server_name"];
    $tmp_ws_usrname = $_POST["user_name"];
    $tmp_ws_pass = $_POST["password"];
    $tmp_ws_port = $_POST["port"];
    
    if ($_POST["port"] == null) {
        try {
            $est_conn = connection_no_port($tmp_ws_server, $tmp_ws_usrname, $tmp_ws_pass);
        } catch (Exception $r) {
            echo "Error: " . $r->getMessage();
        }
    } else {
        try {
            $est_conn = connection_yes_port($tmp_ws_server, $tmp_ws_usrname, $tmp_ws_pass, "", $tmp_ws_port);
        } catch (Exception $z) {
            echo "Error: " . $z->getMessage();
        }
        
    }
    

    if ($est_conn->select_db("polkatu_party")) {
        echo "Database already exists";
        echo "<br>";
    } else {

        try {
            create_db($est_conn);
                
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            
        }
        echo "<br>";
        try {
            create_user_accounts($est_conn);
                
        } catch (Exception $f) {
            echo "Error: " . $f->getMessage();
        }
        echo "<br>";
        try {
            create_registration_info($est_conn);
                
        } catch (Exception $s) {
            echo "Error: " . $s->getMessage();
        }
        echo "<br>";
        try {
            create_user_passwords($est_conn);
               
        } catch (Exception $c) {
            echo "Error: " . $c->getMessage();
        }
        echo "<br>";
        try {
            create_login_logs($est_conn);
                
        } catch (Exception $l) {
            echo "Error: " . $l->getMessage();
        }
        echo "<br>";
        echo "<br>";

        if (isset($_SESSION["s1"]) && isset($_SESSION["s2"]) && isset($_SESSION["s3"]) && isset($_SESSION["s4"])) {
            if ($tmp_ws_server != "localhost") {
                echo "The host name that you entered doesn't match with the hostname in db_config.php 
                hence the website will not function properly";
                echo "<br>";
                echo "<b>To fix that please edit db_config.php and change the value of variable servername to
                $tmp_ws_server </b>";
                echo "<br>";
                echo "<br>";
                $chnges1 = 1;
            } else {
                $chnges1 = 0;
            }

            if ($tmp_ws_usrname != "root") {
                echo "The username that you entered doesn't match with the username in db_config.php 
                hence the website will not function properly";
                echo "<br>";
                echo "<b>To fix that please edit db_config.php and change the value of variable username to
                $tmp_ws_usrname </b>";
                echo "<br>";
                echo "<br>";
                $chnges2 = 1;
            } else {
                $chnges2 = 0;
            } 

            if ($tmp_ws_pass != "") {
                echo "The password that you entered doesn't match with the password in db_config.php 
                hence the website will not function properly";
                echo "<br>";
                echo "<b>To fix that please edit db_config.php and change the value of variable password to
                $tmp_ws_pass </b>";
                echo "<br>";
                echo "<br>";
                $chnges3 = 1;
            } else {
                $chnges3 = 0;
            }
            
            if ($tmp_ws_port != "") {
                echo "Variable for port number hasn't been defined in db_config.php 
                hence the website will not function properly";
                echo "<br>";
                echo "<b>To fix that please edit db_config.php and create a new variable with the value
                $tmp_ws_port and add that variable as property to mysqli object &quot;connection&quot;</b>";
                echo "<br>";
                echo "<br>";
                $chnges4 = 1;
            } else {
                $chnges4 = 0;
            }

            if ($chnges1 == 0 && $chnges2 == 0 && $chnges3 == 0 && $chnges4 == 0) {
                echo "The login info for your MYSQL server <b>doesn't conflict</b> with the db_config.php. <br>
                Hence it is not needed to modify db_config.php, the website will function normally as expected &hearts;";
            }
    
        }
    }

}


session_unset();

?>
