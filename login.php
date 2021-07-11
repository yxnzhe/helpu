<html>
<head>
  <?php
    require_once "navbar.php";
  ?>
</head>
<body>
    <?php
        require_once "function.php";
        
        if(!$_SESSION["isLogin"]) { //if user is logged in
    ?>
    <div class="container p-4 mt-5" style="width: 400px; border: 1px solid black;">
        <div class="row justify-content-center">
            <p class="text-center font-weight-bold" style="font-size:25px">Keyb</p>
            <form class="col-12" method="POST"> <!--Form of the login-->
                <div class="form-group">
                    <label>Email address</label>
                    <input type="email" name="email" class="form-control" placeholder="Email..." required/> <!--Where user input their email-->
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Password..." required/> <!--Where user input their password-->
                </div>
                <input type="hidden" name="isCheckout" value="<?php echo $_SESSION["isCheckout"]?>" /> <!--To get the isCheckout to check if user wants to go to checkout based on what they click at the navbar-->
                <input type="submit" name="login" class="btn btn-primary" value="Login" /> <!--Login Button-->
            </form>
            <?php
                // $errorMsg = "";
                // if(isset($_POST["login"])) { //if user clicks login button
                //     if(!isset($_POST["email"]) || !isset($_POST["password"])) { //if email or password is empty
                //         $errorMsg = "Invalid Form Submission"; //error message will be prompted
                //     }
                //     else { //if email and password is present
                //         $email = $_POST["email"]; //to set $_POST["email"]
                //         $password = $_POST["password"]; //to set $_POST["password"]
                //         $isCheckout = $_POST["isCheckout"]; //to set $_POST["isCheckout"]
                        
                //         $errorMsg = loginUser($email, $password, $isCheckout); //to login the user based on the email, password and ischeckout, errorMsg will not be empty if error occurs at the function
                //     }
                //     if(!empty($errorMsg)) { //if there is error msg
                //         echo "<span style='color: red;'>".$errorMsg."</span>"; //to prompt error msg
                //     }
                // }
            ?>
        </div>
    </div>
    <?php
        }
        else { // if user is not logged in
            header("Location: index.php");
        }
    ?>
</body>>

</html>