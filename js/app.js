function showRegister(){
    var loginForm = document.getElementById("loginForm");
    var registerForm = document.getElementById("registerForm");
    loginForm.style.display = "none";
    registerForm.style.display = "block";
}

function showLogin(){
    var loginForm = document.getElementById("loginForm");
    var registerForm = document.getElementById("registerForm");
    registerForm.style.display = "none";
    loginForm.style.display = "block";
}

function registerFunction(){
    var name = document.getElementById("regName").value;
    var email = document.getElementById("regEmail").value;
    var password = document.getElementById("regPass").value;
    var confirmPassword = document.getElementById("regConfirmPass").value;

    if(name == "" || email == "" || password == "" || confirmPassword == ""){
        regMsg.innerHTML = "All field is Mandatory!";
    }
    else{
        var xmlhttp = new XMLHttpRequest();

        xmlhttp.onreadystatechange = function(){
            if(this.readyState == 4 && this.status == 200) {
                // regMsg.innerHTML = this.response;
                // alert(this.response);
                if(!this.response){
                    regMsg.innerHTML = "<span class='successMsg'>Register Successfully!</span>";
                    // location.href = "index.php"
                }
                else{
                    regMsg.innerHTML = this.response;
                } 
            }
        }

        var data = "username="+name+"&email="+email+"&password="+password+"&confirmPassword="+confirmPassword+"&type=register";

        xmlhttp.open("POST", "users.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send(data);
    }
}
