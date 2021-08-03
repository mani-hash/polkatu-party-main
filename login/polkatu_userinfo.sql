/* This mysql code creates the required database and tables
for polkatu login and registration pages!
However create_db.php will automatically do this for you.

--Instructions--

>>Open the login/registration page in a local server
>>Type "create_db.php in the last line of the url"

 ex: localhost/Polkatu/login/create_db.php

>>Default mysql web server login info are already filled in
>>If your mysql web server login details are different please change them as per needed
>>Click on create database button
>>If database doesn't exist it will automatically create one

[NOTE: If you enter new mysql web server login info please make sure to make necessary changes
in db_config.php]

If you encounter any problem using create_db.php use this instead

This file is not needed for the functioning of the webpage
Remove it when not needed!
*/

CREATE DATABASE polkatu_party;

CREATE TABLE polkatu_party.user_accounts (
    user_ID CHAR(10),
    account_no INT(10) AUTO_INCREMENT UNIQUE,
    username_display VARCHAR(15) NOT NULL,
    username_verify VARCHAR(15) NOT NULL,
    user_activation_code CHAR(40) NOT NULL,
    user_email VARCHAR(100) NOT NULL,
    PRIMARY KEY(User_ID)  
);

CREATE TABLE polkatu_party.registration_info (
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
);

CREATE TABLE polkatu_party.user_passwords (
    user_ID_p CHAR(10),
    user_email_p VARCHAR(100),
    passwords VARCHAR(100) NOT NULL,
    PRIMARY KEY(user_ID_p, user_email_p),
    FOREIGN KEY(user_ID_p) REFERENCES polkatu_party.user_accounts(user_ID),
    FOREIGN KEY(user_email_p) REFERENCES polkatu_party.registration_info(user_email_r)
);

CREATE TABLE polkatu_party.login_logs (
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
);