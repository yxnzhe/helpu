<html>
<head>
  <?php
    require_once "navbar.php";
  ?>
</head>
<body>
    <?php
        require_once "function.php";
        
        if(!$_SESSION["isLogin"]) { //if user is not logged in
    ?>
    <div class="container p-4 mt-5" style="width: 400px; border: 1px solid black; margin-bottom: 4rem!important;">
        <div class="row justify-content-center">
            <p class="text-center font-weight-bold" style="font-size:25px">HELPU</p>
            <form class="col-12" method="POST"> <!--Form of the login-->
                <div class="form-group">
                    <label>Email address</label>
                    <input type="email" name="email" class="form-control" placeholder="Email..." required/> <!--Where user input their email-->
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Password..." required/> <!--Where user input their password-->
                </div>
                <input type="submit" name="login" class="btn btn-primary" value="Login" /> <!--Login Button-->
            </form>
            <?php
                if(isset($_POST["login"])) { //if user clicks login button
                    if(!isset($_POST["email"]) || !isset($_POST["password"])) { //if email or password is empty
					  echo "<span style='color: red; font-size: 20px;'>Invalid Form Submission</span>";//error message will be prompted
                    }
                    else { //if email and password is present                        
						login($_POST["email"], $_POST["password"]); //to login the user based on the email, password and ischeckout, errorMsg will not be empty if error occurs at the function
                    }
                }
            ?>
        </div>
        <div class="row justify-content-center">
			<div class="col-2"></div>
			<div class="col-8 text-center">
				<span style="font-size:17px;">If you don't have an account</span>
				<a class="" href="register.php">
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
</body>>
<?php require_once "footer.php"?>
</html>