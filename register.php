<html>
<head>
    <?php 
    require_once "navbar.php";
    if(isset($_POST["register"])){ //if the register button is clicked
        if(empty($_POST["name"]) || empty($_POST["email"]) || empty($_POST["password"]) || empty($_POST["conPassword"])) { //if name, email, password or confirm password is empty
            $registerMsg = "<span class='errorMsg'>Invalid Form Submission</span>";//error message will be prompted
        }   
        else if (!checkEmail($_POST["email"])) {} //check whether the email exist in database o not
        else { //if email doesn't exist and all field is not empty
            $name = strip_tags($_POST["name"]); //strip_tags is a php function to remove html tags from input for example <b></b>
            $email = strip_tags($_POST["email"]); //strip_tags is a php function to remove html tags from input for example <b></b>
            $password = $_POST["password"];
            $confirmPass = $_POST["conPassword"];
            $registerMsg = register($name, $email, $password, $confirmPass);
        }
    }
    ?>
</head>
<body>
    <?php 
        if(!$_SESSION["isLogin"]) { //if user is not logged in
    ?>
    <div class="container p-4 mt-5 register" style="width: 400px; border: 1px solid black; margin-bottom: 3rem!important;">
        <div class="row justify-content-center">
            <p class="text-center font-weight-bold" style="font-size:25px">HELPU</p>
            <form class="col-12" method="POST"> <!--Form of the login-->
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" placeholder="Name..." required/> <!--Where user input their name-->
                </div>
                <div class="form-group">
                    <label>Email address</label>
                    <input type="email" name="email" class="form-control" placeholder="Email..." required/> <!--Where user input their email-->
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Password..." required/> <!--Where user input their password-->
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="conPassword" class="form-control" placeholder="Confirm Password..." required/> <!--Where user input their password-->
                </div>
                <input type="submit" name="register" class="btn btn-primary" value="Register" /> <!--Login Button-->
            </form>
            <?php
                if(isset($registerMsg)){ //if there's value return for register error message
                    echo $registerMsg; //print the register error message
                }
            ?>
        </div>
        <div class="row justify-content-center">
			<div class="col-2"></div>
			<div class="col-8 text-center">
				<span style="font-size:17px;">Already have an account?</span>
				<a class="" href="login.php">
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
</body>
<?php require_once "footer.php"?>
</html>