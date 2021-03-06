function showRegister() { //to show register form and hide login form
    var loginForm = document.getElementById("loginForm");
    var registerForm = document.getElementById("registerForm");
    loginForm.style.display = "none";
    registerForm.style.display = "block";
}

function showLogin() { //to show login form and hide register form
    var loginForm = document.getElementById("loginForm");
    var registerForm = document.getElementById("registerForm");
    registerForm.style.display = "none";
    loginForm.style.display = "block";
}

function registerFunction() { //ajax function to register
    // get register name, email, password and confirm password value
    var name = document.getElementById("regName").value; 
    var email = document.getElementById("regEmail").value;
    var password = document.getElementById("regPass").value;
    var confirmPassword = document.getElementById("regConfirmPass").value;

    if(name == "" || email == "" || password == "" || confirmPassword == ""){ //if any one of the input field is empty
        regMsg.innerHTML = "All field is Mandatory!"; //print error message at id regMsg
    }
    else{ //if every field is not empty
        var xmlhttp = new XMLHttpRequest();

        xmlhttp.onreadystatechange = function() {
            if(this.readyState == 4 && this.status == 200) { //js return success status
                if(!this.response){ //if register successfull will show the register successfully message and redirect them to index.php after 1 second
                    regMsg.innerHTML = "<span class='successMsg'>Register Successfully!</span>"; //print the success message in frontend at id regMsg

                    setTimeout(function(){ //redirect them to index.php after 1 second
                        document.location.href = "index.php"; //redirect to index.php
                    },1000);
                }
                else{ //if register not successfull will show the error message for exmaple invalid email format
                    regMsg.innerHTML = this.response; //return the error message in frontend at id regMsg
                } 
            }
        }

        var data = "username="+name+"&email="+email+"&password="+password+"&confirmPassword="+confirmPassword+"&type=register";

        xmlhttp.open("POST", "authentication.php", true); // if the registration is a success, we will be redirected to the authethication page without refreshing the whole page
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");//set request header
        xmlhttp.send(data); //send data
    }
}

function loginFunction() { //ajax function to login
    // get login email and password value
    var email = document.getElementById("loginEmail").value;
    var password = document.getElementById("loginPass").value;

    if(email == "" || password == "") { //if email or password field is empty
        loginMsg.innerHTML = "All field is Mandatory!"; //print error message at id loginMsg
    }
    else { //if both of the field is not empty
        var xmlhttp = new XMLHttpRequest();

        xmlhttp.onreadystatechange = function() {
            if(this.readyState == 4 && this.status == 200) { //js return success status
                if(!this.response){ //if login successfull will show the login successfully message and redirect them to index.php after 1 second
                    loginMsg.innerHTML = "<span class='successMsg'>Login Successfully!</span>"; //print the success message in frontend at id loginMsg

                    setTimeout(function(){ //redirect them to index.php after 1 second
                        document.location.href = "index.php"; //redirect to index.php
                    },1000);
                }
                else{ //if login not successfull will show the error message for exmaple invalid email format
                    loginMsg.innerHTML = this.response; //return the error message in frontend at id loginMsg
                } 
            }
        }

        var data = "email="+email+"&password="+password+"&type=login";

        xmlhttp.open("POST", "authentication.php", true); // if the registration is a success, we will be redirected to the authethication page without refreshing the whole page
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded"); //set request header
        xmlhttp.send(data); //send data
    }
}
