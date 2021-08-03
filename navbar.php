<?php
  if(!isset($_COOKIE["per_username"]) && !isset($_COOKIE["tem_username"])) {

    //sets a number as guest user name temprarily for 30 days

    if (!isset($_COOKIE["user"])) {
      $cookiename="user";
      $name=rand(10000,999999);
      setcookie($cookiename, $name, time() + (86400 * 30), "/");
    } 
 
  } else {
    //else validates the currently logged in session

    require_once("db_check_cache.php");
  }
 

?>

<link rel="stylesheet" type="text/css" href="CSS/styleagain.css">
<link rel = "icon" href ="Images/coconut.png" type = "image/x-icon"> 
<div id="navbar">

  
  <?php
  
    //displays log out button if there is a user currently logged in

    if (isset($_COOKIE["per_username"]) || isset($_COOKIE["tem_username"])) {
      echo "<div class='login'> 
      <button class='btn pos3' onclick='alert1()'>Log out</button>
      </div>";
    
    } else {

      //displays sign in or sign up button if there is no user currently logged in

      echo "<div class='login'> <a href='login/login.php'><button class='btn pos1' onclick='setRegButton()'>Sign up</button></a>
      <a href='login/login.php'><button class='btn pos2' onclick='setLogButton()'>Log in</button></a>
      </div>";
    }

    function logout() {  //function to automatically logout user (used by db_check_cache.php)
      setcookie("tem_username", "", time() - 3000, "/");
      setcookie("per_username", "", time() - 3000, "/"); 
      setcookie("per_ver", "", time() - 3000, "/");
      setcookie("tem_ver", "", time() - 3000, "/");
    }
  ?>

  <div class="username">
    <p id="username"> Welcome 
	
      <?php 

        //displays currently logged in username of the user
        
        if (isset($_COOKIE["per_username"])) {

          echo " " . $_COOKIE["per_username"];

        } elseif (isset($_COOKIE["tem_username"])) {

          echo " " . $_COOKIE["tem_username"];

        } elseif (isset($_COOKIE["user"])) {

          echo " " . $_COOKIE["user"];
        } else {
          echo " " . "New User!";
        }
          
		
	    ?> 
		
	  </p>
  </div>
</div>

<script src="sweetalert2@10.js"></script>
<script>
  //Toggle button function to switch to either login or register form
  function setRegButton() {
    localStorage.setItem("rememberButtonChoice", 'registerChoice');
  }

  function setLogButton() {
    localStorage.setItem("rememberButtonChoice", 'loginChoice');
  }

  function alert1() {
    Swal.fire({
    title: 'Logout',
    text: 'Do you want to log out?',
    showConfirmButton: 'true',
    confirmButtonText: 'Yes',
    showCancelButton: 'true',
    cancelButtonText: 'No',
    confirmButtonColor: '#00e600',
    cancelButtonColor: '#ff0000',
    imageUrl: 'Images/coconut.png'
    }).then((res) => {
      if (res.value) {
        document.cookie = "per_username=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        document.cookie = "tem_username=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        document.cookie = "per_ver=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        document.cookie = "tem_ver=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        location.reload();
      } else if(res.dismiss == 'cancel') {
      
      }
    }); 
  }

</script>
