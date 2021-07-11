<html>
<head>
<?php require_once "navbar.php"?>
</head>
<body>
<div class="container p-4 mt-5" style="width: 400px; border: 1px solid black;">
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
            <input type="submit" name="register" class="btn btn-primary" value="Register" /> <!--Login Button-->
        </form>
        <?php
            if(isset($_POST["register"])){
                $name = $_POST["name"];
                $email = $_POST["email"];
                $password = $_POST["password"];
                register($name, $email, $password);
            }
        ?>
    </div>
</div>
</body>
<?php require_once "footer.php"?>
</html>