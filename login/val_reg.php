<?php
session_start();

date_default_timezone_set("Asia/Colombo");

include "db_config.php";

//most of the registration validation part has been moved into OOP

class registration {

  //public properties/methods can be accessed from anywhere
  //protected properties/methods can be accessed within the class and by classes derived from that class

  public $username;
  public $lusername;
  public $email;
  protected $passwords;
  protected $re_passwords;

  

  //The __construct function will be called when a new object is created
  //this function assigns the user entered input to the respective variables
  
  public function __construct($username, $lusername, $email, $passwords, $re_passwords) {
    $this->username = $username;
    $this->lusername =$lusername;
    $this->email = $email;
    $this->passwords = $passwords;
    $this->re_passwords = $re_passwords;
     

  }

  //this function(method) validates if the username contains any unwanted characters

  /*The validation accepts only usernames that start with simple/capital letters but
  it can contain numbers, fullstops and underscores too. Spaces are not accepted 
  and rest of the characters (such as @ , ; / ! # and so on) are not accepted
  either
  */

  protected function validate_username() {
      
    if (preg_match("/^[a-zA-Z0-9_.]*$/", $this->lusername) == true &&
    preg_match("/^[0-9]*$/", $this->lusername) != true && 
    preg_match("/^[0-9]/", $this->lusername) != true &&
    preg_match("/^[_]*$/", $this->lusername) != true && 
    preg_match("/^[.]*$/", $this->lusername) != true && 
    preg_match("/^[_]/", $this->lusername) != true && 
    preg_match("/^[.]/", $this->lusername) != true) {

      return 9;

    } else  {
      return 1;
    } 
  }

  //this function(method) checks if the passwords are valid
  //Passwords must not contain any spaces
  //The passwords entered in enter password input field and confirm password input field must be equal

  protected function validate_password() {
    if (preg_match("/[ ]/", $this->passwords) == true) {
      return 8;
    } elseif ($this->passwords == $this->re_passwords) {
     return 9;
    } else {
      return 1;
    }
  }

  //validates if the email entered by user is a valid e-mail

  protected function validate_email() {
    if (filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
      return 9;
    } else {
      return 1;
    }
  }

  //hashes the password for added security using password hash function
  //the result is 60 characters long

  protected function hash_pass() {
    $hashed = password_hash($this->passwords, PASSWORD_DEFAULT);
    return $hashed;
  }

  //gets the user ip and validates it

  protected function get_ip() {
    $ip = $_SERVER["REMOTE_ADDR"];
    if (filter_var($ip, FILTER_VALIDATE_IP) == true) {
      return $ip;
    } else {
      return null;
    }
  }

  //gets the current date and time(24 hr format)

  protected function get_date() {
    $date = date("Y-m-d H:i:s");
    return $date;
  }

  /*creates a user activation code using hash_hmac function
  and sha1 plus a key.
  Currently this user activation code is used as a part of another
  key to validate the logged in sessions of users
  */

  protected function user_activation_code() {
    $key = "polkatu_party";
    $user_code = hash_hmac('sha1', $this->lusername, $key);
    return $user_code;
  }


}


//This is a child class of the parent class "registration"

class validation extends registration {

  //This function(method) retrieves the protected property password and returns the value 

  public function get_pass() {
    $retrieved1 = $this->passwords;
    return $retrieved1;
  }

  /*This function(method) retrieves protected property 
  password(the one entered in the confirm password field) 
  and returns the value*/

  public function get_confirmPass() {
    $retrieved2 = $this->re_passwords;
    return $retrieved2;  
  }
  
  /*This function(method) returns the error message
  or a null value after validating the username*/
  
  public function user_errors() {
    $invalid_username = $this->validate_username();
    if ($invalid_username == 9) {
      return "";
    } else {
      $_SESSION["errUser"] = "<p class='error'><img class='img_error' src='images/error.svg'>
      Invalid username! Username must start with a letter and it can consist of fullstops,
      underscores and letters</p>";

      return $_SESSION["errUser"];
    }
  }

  /*This function(method) returns the error message
  or a null value after validating email  */

  public function email_errors() {
    $invalid_email = $this->validate_email();

    if ($invalid_email == 9) {
      return "";
    } else {
      $_SESSION["errEmail"] = "<p class='error'><img class='img_error' src='images/error.svg'>Invalid email address</p>";
      return $_SESSION["errEmail"];
    }
  }

  /*This function(method) returns the error message
  or a null value after validating the password  */

  public function password_errors() {
    $pass_unmatch = $this->validate_password();

    if ($pass_unmatch == 9) {
      return "";
    } elseif ($pass_unmatch == 8) {
      $_SESSION["errPass"] = "<p class='error'><img class='img_error' src='images/error.svg'>Passwords cannot contain any spaces</p>";
      return $_SESSION["errPass"];
    } else {
      $_SESSION["errMissPass"] = "<p class='error'><img class='img_error' src='images/error.svg'>Passwords doesn't match</p>";
      return $_SESSION["errMissPass"];
    }

  }
  
  /*This function(method) checks if the error messages are
  null or not and executes a block of code accordingly*/

  public function final_validation($err1, $err2, $err3) {
    if ($err1 == "" && $err2 == "" && $err3 == "") {
          
      $val1 = array();
      $val1["success"] = "exists";
      $val1["hash_pass"] = $this->hash_pass();
      $val1["cur_ip"] = $this->get_ip();
      $val1["cur_date"] = $this->get_date();
      $val1["activ_code"] = $this->user_activation_code();
          
      return $val1;
           
         
        
          
    } else {
          
      $_SESSION["tmpRegName"] = $this->username;
      $_SESSION["tmpRegEmail"] = $this->email;

      $val2 = array();
      $val2["tmpUser"] =  $_SESSION["tmpRegName"]; 
      $val2["tmpEmail"] = $_SESSION["tmpRegEmail"]; 

      return $val2;
          
    }
  }
  


}

//Creates an object

$users = new validation(htmlspecialchars(rtrim($_POST["Rname"])), strtolower(htmlspecialchars(rtrim($_POST["Rname"]))), 
htmlspecialchars($_POST["Rmail"]), htmlspecialchars($_POST["Rpassword"]), htmlspecialchars($_POST["REpassword"]));
  
//assigns the property values to the respective session variables
   

$_SESSION["otp_usrname"] = $users->username;

$_SESSION["otp_low_usrname"] = $users->lusername;

$_SESSION["otp_usr_email"] = $users->email;

$reg_password = $users->get_pass();

$re_enter_password = $users->get_confirmPass();


//This sql statement selects the user_ID of the username from the database entered by the user

$sel_query = $connected->prepare("SELECT user_ID FROM user_accounts WHERE username_verify = ?"); 

$sel_query->bind_param('s', $_SESSION["otp_low_usrname"]);
$sel_query->execute();
$sel = $sel_query->get_result();

//This sql statement selects the user ID of the email from the database entered by the user

$del_query = $connected->prepare("SELECT user_ID FROM user_accounts WHERE user_email = ?"); 

$del_query->bind_param('s', $_SESSION["otp_usr_email"]);
$del_query->execute();
$del = $del_query->get_result();

/*This if else checks if the username and email entered by the user exist in the database
and redirects user to login page if either of them exist*/

if ($sel->num_rows != 0 || $del->num_rows != 0) {

  header("location: login.php");

  if ($sel->num_rows != 0) {
    //returns error message if username already exists   

    $_SESSION["nameTaken"] = "<p class='error'><img class='img_error' src='images/error.svg'>Name already taken!</p>";
         
    
  } elseif ($del->num_rows != 0) {
    //returns error message if email already exists   

    $_SESSION["emailTaken"] = "<p class='error'><img class='img_error' src='images/error.svg'>Another account is using this email!</p>";
         
  }

} else {
       
  //executes the validation functions and returns null or a error string
       
  $error_User = $users->user_errors();
  $error_Email = $users->email_errors();
  $error_Pass = $users->password_errors();

  /*this function validates the above 3 variables and returns two different arrays
  depending on the validation result*/

  $validate = $users->final_validation($error_User, $error_Email, $error_Pass);

      
    
}

//checks if the associative array "validate" exists

if (isset($validate)) {
  /*This if else statement returns the values of the array
  to the respective variables depending on the validation result*/

  if (isset($validate["success"])) {
    //hashes password, gets ip, date etc upon successfull validation and assigns them to session variables

    $_SESSION["hashed_pass"] = $validate["hash_pass"];

    $_SESSION["ip_addr"] = $validate["cur_ip"];

    $_SESSION["activ_usr_code"] = $validate["activ_code"];

    $_SESSION["date_now"] = $validate["cur_date"];

    $_SESSION["half_validUser"] = "exists";

    $_SESSION["not_resend"] = "msg_sent_once";

    $_SESSION["otp_token"] = "exists";

    header("location: sendmail.php");

  } else {
    //Goes back to the login page upon unsuccessfull validation
    /*user entered info like username and e-mail are stored as session variables and
    displayed in the registration input page*/

    $tmpRegUserName = $validate["tmpUser"];
    $tmpRegEmail = $validate["tmpEmail"];
    header("location: login.php");
          
  }
}
    

 
include "db_close.php"; 

?>