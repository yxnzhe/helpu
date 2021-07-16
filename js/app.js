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
