<html>
<head>
  <?php
    require_once "navbar.php";

    if(isset($_SESSION["register"])){ //if user was redirect to register from navbar
        if($_SESSION["register"]){ //is redirect to register form from navbar
            echo "<script>window.onload = function() {showRegister();};</script>"; //call js showRegister() function 
        }
        else{ //is not redirect to register form from navbar
            echo "<script>window.onload = function() {showLogin();};</script>"; //call js showLogin() function
        }
    }
  ?>
</head>
<body>
    <div id="loginForm">
        <?php
            if(!isset($_SESSION["userId"])) { //if user is not logged in
        ?>
        <div class="container p-4 mt-5" style="width: 400px; border: 1px solid black; margin-bottom: 4rem!important;">
            <div class="row justify-content-center">
                <p class="text-center font-weight-bold" style="font-size:25px">HELPU</p>
                <form class="col-12" method="POST"> <!--Form of the login-->
                    <div class="form-group">
                        <label>Email address</label>
                        <input type="email" id="loginEmail" name="email" class="form-control" placeholder="Email..." required/> <!--Where user input their email-->
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" id="loginPass" name="password" class="form-control" placeholder="Password..." required/> <!--Where user input their password-->
                    </div>
                    <input type="button" onclick="loginFunction()" name="login" class="btn btn-primary" value="Login" /> <!--Login Button-->
                </form>
                <span id="loginMsg" class="errorMsg"></span>
            </div>
            <div class="row justify-content-center">
                <div class="col-2"></div>
                <div class="col-8 text-center">
                    <span style="font-size:17px;">If you don't have an account</span>
                    <a href="javascript:void(0)" onclick="showRegister()">
                        <span style="font-size:17px">Create an Account</span>
                    </a>
                </div>
                <div class="col-2"></div>
            </div>
        </div>
        <?php
            }
            else { // if user is logged in
                header("Location: index.php");
            }
        ?>
    </div>
    <div id="registerForm" style="display:none">
        <?php 
            if(!isset($_SESSION["userId"])) { //if user is not logged in
        ?>
        <div class="container p-4 mt-5 register" style="width: 400px; border: 1px solid black; margin-bottom: 3rem!important;">
            <div class="row justify-content-center">
                <p class="text-center font-weight-bold" style="font-size:25px">HELPU</p>
                <form class="col-12" method="POST"> <!--Form of the login-->
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" id="regName" name="name" class="form-control" placeholder="Name..." required/> <!--Where user input their name-->
                    </div>
                    <div class="form-group">
                        <label>Email address</label>
                        <input type="email" id="regEmail" name="email" class="form-control" placeholder="Email..." required/> <!--Where user input their email-->
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" id="regPass" name="password" class="form-control" placeholder="Password..." onclick="emailValidation()" id="password" required/> <!--Where user input their password-->
                    </div>
                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input type="password" id="regConfirmPass" name="conPassword" class="form-control" placeholder="Confirm Password..." onclick="emailValidation()" id="confirmPassword" required/> <!--Where user input their password-->
                    </div>
                    <input type="button" onclick="registerFunction()" name="register" class="btn btn-primary" value="Register" /> <!--Login Button-->
                </form>
                <!-- to return javascript error or success messages -->
                <span id="regMsg" class="errorMsg"></span>
            </div>
            <div class="row justify-content-center">
                <div class="col-2"></div>
                <div class="col-8 text-center">
                    <span style="font-size:17px;">Already have an account?</span>
                    <a href="javascript:void(0)" onclick="showLogin()">
                        <span style="font-size:17px">Login</span>
                    </a>
                </div>
                <div class="col-2"></div>
            </div>
        </div>
        <?php 
            }
            else { //if user is not login
                header("Location: index.php");  
            }
        ?>
    </div>
</body>
<?php require_once "footer.php"?>
</html>