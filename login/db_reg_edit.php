<?php

//executes the code if account is 100% successfully validated

if (isset($_SESSION["full_validUser"])) {

    /*fetches the user_ID of the user whose username is in the 
    last row of the table and assigns it to a variable*/

    $id_query = $connected->query("SELECT user_ID FROM user_accounts ORDER BY account_no DESC LIMIT 1;");
    $ID = $id_query->fetch_array(MYSQLI_ASSOC);

    //This if else statement generates a unique ID for each user upon registration
    //Currently this if else statement can generate unique IDs for 1.9 billion accounts
    //Can be modified to generate till 24.9 billion unique IDs
 

    if (!isset($ID["user_ID"])) {
        /*if user_ID data isn't present(if no entry has been entered into the tables yet),
        assigns this value to the variable*/

        $first_ID = "A000000001";
     
    } else {
        /*In simple, if user_ID data already exists(table has entries), these statements 
        increments the user_ID by one or assigns a new ID starting with a different letter
        if all the digits in the unique ID is "9" */

        $tmpID =  $ID["user_ID"];
        $trimNo = substr($tmpID, 1);
        $tmp_trimInt = (int)$trimNo;
     
     
        $digit_length = strlen((string)$tmp_trimInt);
     
    
        $chngAlphabet = 1;

        if ($digit_length == 1) {
            $tmp_trimInt += 1;

            if ($tmp_trimInt == 10) {
                $trimInt_string = (string)$tmp_trimInt;
                $fixed_string = "0000000" . $trimInt_string;
            } else {
                $trimInt_string = (string)$tmp_trimInt;
                $fixed_string = "00000000" . $trimInt_string;
            }
         
        } elseif ($digit_length == 2) {
            $tmp_trimInt += 1;

            if ($tmp_trimInt == 100) {
                $trimInt2_string = (string)$tmp_trimInt;
                $fixed_string = "000000" . $trimInt2_string;
            } else {
                $trimInt2_string = (string)$tmp_trimInt;
                $fixed_string = "0000000" . $trimInt2_string;
            }

        } elseif ($digit_length == 3) {
            $tmp_trimInt += 1;

            if ($tmp_trimInt == 1000) {
                $trimint3_string = (string)$tmp_trimInt;
                $fixed_string = "00000" . $trimint3_string;
            } else {
                $trimint3_string = (string)$tmp_trimInt;
                $fixed_string = "000000" . $trimint3_string;
            }

        } elseif ($digit_length == 4) {
            $tmp_trimInt += 1;

            if ($tmp_trimInt == 10000) {
                $trimint4_string = (string)$tmp_trimInt;
                $fixed_string = "0000" . $trimint4_string;
            } else {
                $trimint4_string = (string)$tmp_trimInt;
                $fixed_string = "00000" . $trimint4_string;
            }

        } elseif ($digit_length == 5) {
            $tmp_trimInt += 1;

            if ($tmp_trimInt == 100000) {
                $trimint5_string = (string)$tmp_trimInt;
                $fixed_string = "000" . $trimint5_string;
            } else {
                $trimint5_string = (string)$tmp_trimInt;
                $fixed_string = "0000" . $trimint5_string;
            }

        } elseif ($digit_length == 6) {
            $tmp_trimInt += 1;

            if ($tmp_trimInt == 1000000) {
                $trimint6_string = (string)$tmp_trimInt;
                $fixed_string = "00" . $trimint6_string;
            } else {
                $trimint6_string = (string)$tmp_trimInt;
                $fixed_string = "000" . $trimint6_string;
            }

        } elseif ($digit_length == 7) {
            $tmp_trimInt += 1;

            if ($tmp_trimInt == 10000000) {
                $trimint7_string = (string)$tmp_trimInt;
                $fixed_string = "0" . $trimint7_string;
            } else {
                $trimint7_string = (string)$tmp_trimInt;
                $fixed_string = "00" . $trimint7_string;
            }
        } elseif ($digit_length == 8) {
            $tmp_trimInt += 1;
        
            if ($tmp_trimInt == 100000000) {
                $trimint8_string = (string)$tmp_trimInt;
                $fixed_string = $trimint8_string;
            } else {
                $trimint8_string = (string)$tmp_trimInt;
                $fixed_string = "0" . $trimint8_string;
            }

        } elseif ($digit_length == 9) {
        
            if ($tmp_trimInt == 999999999) {
                $chngAlphabet = 9;
                $fixed_string = "000000001";
            } else {
                $chngAlphabet = 1;
                $tmp_trimInt += 1;
                $trimint9_string = (string)$tmp_trimInt;
                $fixed_string = $trimint9_string;
            }
        }
 
    
        $first_char = substr($tmpID, 0, 1);
        /*<FOR POLKATU MEMBERS> 
        If needed you can create more cases to increase the unique_ID creation limit
        (Pls use only capital alphabets)
        */
        switch ($first_char) {
            case "A":
                if ($chngAlphabet == 9) {
                    $first_ID = "B" . $fixed_string;
                } else {
                    $first_ID = "A" . $fixed_string;
                }
            break;

            case "B":
                if ($chngAlphabet == 9) {
                    $first_ID = "C" . $fixed_string;
                } else {
                    $first_ID = "B" . $fixed_string;
                }
            break;
        }
   
    
    }
    
    //Enters the necessary data into the database
    
    $regInfo_query = $connected->prepare("INSERT INTO registration_info(user_email_r, date_r, ip_address_r, 
    user_agent_r, reg_browser_r, browser_ver_r, OS_r, OS_arch_64_l, mobile_r, robot_r) 
    VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"); 

    $regInfo_query->bind_param('sssssssiii', $reg_email, $date_registered, $register_ip_address, $req_userAgent, 
    $req_userBrowser, $req_browserVer, $req_platformName, $platform64_bool, $mobile_bool, $robot_bool);

    $regInfo_query->execute();

    

    $userInfo_query = $connected->prepare("INSERT INTO user_accounts(user_ID, username_display, username_verify, 
    user_activation_code, user_email) VALUES(?, ?, ?, ?, ?)");

    $userInfo_query->bind_param('sssss', $first_ID, $reg_username, $reg_simple_username, $user_activation_code, 
    $reg_email);

    $userInfo_query->execute();

    $passInfo_query = $connected->prepare("INSERT INTO user_passwords(user_ID_p, user_email_p, passwords) 
    VALUES(?, ?, ?)");

    $passInfo_query->bind_param('sss', $first_ID, $reg_email, $hashed_reg_password);
    
    $passInfo_query->execute();

} else {
    //displays page not found if user tries to manually access this page by entering filename in the URL
    echo "<h1>OBJECT NOT FOUND!</h1>";

    echo "<br>";
    echo "<h1>404</h1>";
    echo "<br>";
    echo "<p>The requested URL was not found on this server.</p>"; 
    echo "If you entered the URL manually please check your spelling and try again. </p>";


    echo "<br>";
    echo "<br>";
}
?>