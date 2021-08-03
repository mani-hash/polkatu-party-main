
/* JS login, register toggle-btn */

var x = document.getElementById("login");
var y = document.getElementById("register");
var z = document.getElementById("btn");

console.log(x);

function register(){
    x.style.left = "-400px";
    y.style.left = "50px";
    z.style.left = "110px";
    document.getElementById("main-form").className = "form-box";
}
function login(){
    x.style.left = "50px";
    y.style.left = "450px";
    z.style.left = "0";
    document.getElementById("main-form").className = "short-box";
}

/* JS store toggle-btn position */

if (localStorage){
    if (localStorage.getItem('rememberButtonChoice')){
        let getChoice = localStorage.getItem('rememberButtonChoice');
        if (getChoice == "loginChoice"){
        login();
    } else {
        register();
      }
    }
}

let loginButton = document.getElementById('loginButton');
let registerButton = document.getElementById('registerButton');

loginButton.addEventListener('click', function(){
    if(localStorage){
        localStorage.setItem("rememberButtonChoice", 'loginChoice');
    }
});


registerButton.addEventListener('click', function(){
    if(localStorage){
       localStorage.setItem("rememberButtonChoice", 'registerChoice');
    }
});

/* JS required field error message for login page */

function InvalidLogUser(log_user_err) {
    if (log_user_err.value == "") {
        log_user_err.setCustomValidity("Enter your username");
    } else {
        log_user_err.setCustomValidity("");
    }
}

function InvalidLogPass(log_pass_err) {
    if (log_pass_err.value == "") {
        log_pass_err.setCustomValidity("Enter your password");
    } else {
        log_pass_err.setCustomValidity("");
    }
}

/* JS required field error message for register page */

function InvalidEmail(email_err) {
    if (email_err.value == "") {
        email_err.setCustomValidity("Enter an email address");
    } else if (email_err.validity.typeMismatch) {
        email_err.setCustomValidity("Enter a valid email address");
    } else {
        email_err.setCustomValidity("");
    }
}

function InvalidRegUser(reg_user_err) {
    if (reg_user_err.value == "") {
        reg_user_err.setCustomValidity("Enter your username");
    } else {
        reg_user_err.setCustomValidity("");
    }
}

function InvalidRegPass(pass_err) {
    if (pass_err.value == "") {
        pass_err.setCustomValidity("Enter a password for your new account");
    } else {
        pass_err.setCustomValidity("");
    }
}

function InvalidConfirm(confirm_pass_err) {
    if (confirm_pass_err.value == "") {
        confirm_pass_err.setCustomValidity("Confirm your password");
    } else {
        confirm_pass_err.setCustomValidity("");
    }
}

//show/hide password functions

function visiblepass() {
    var xm = document.getElementById("log_pass");

    if (xm.type === "password") {
        xm.type = "text";
    } else {
        xm.type = "password";
    }
}

function visiblepass2() {
    var ym = document.getElementById("reg_pass");
    var zm = document.getElementById("reg_con_pass");

    if (ym.type === "password" && zm.type === "password") {
        ym.type = "text";
        zm.type = "text";
    } else {
        ym.type = "password";
        zm.type = "password";
    }
}



