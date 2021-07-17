<head>
    <title>Helpu Student Website</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="icon" type="image/png" href="imgs/helpu.png" />
    <script src="js/app.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</head>

<body>
<?php
    session_start();
    require_once "function.php";
    require_once "users.php";

    if(!isset($_SESSION["isLogin"])) { //if isLogin is not initiated before
        $_SESSION["isLogin"] = false; //to initiate isLogin as false
    }
    if(isset($_POST["logout"])) { //if user clicks logout button
        session_destroy(); //session will be destroyed
        header("Refresh:0"); //website will be refreshed
        header("Location: index.php"); //user will be redirected to index.php
    }
    if(isset($_POST["register"])){
        $_SESSION["register"] = true;
        header("Location: login.php"); //user will be redirected to login.php
    }
    else if(isset($_POST["login"])){
        $_SESSION["register"] = false;
        header("Location: login.php"); //user will be redirected to login.php
    }
?>  
    <nav class="navbar sticky-top navbar-expand-lg navbar-light px-3 py-2" style="background-color: #a2c3fa;">
        <div class="container-fluid">
            <div class="col-2 col-lg-3 p-0">
                <a class="navbar-brand align-center" href="index.php">
                    <img src="imgs/helpu.png" alt="HelpU Student Website Logo" width="85px" height="80px">
                </a>
            </div>

            <div class="col-8 col-lg-6 text-center p-0">
                <a class="navbar-brand" href="index.php">
                    <h1 class="d-none d-lg-block">HelpU Student Website</h1>
                    <span class="d-lg-none font-weight-bold" style="font-size:30px">HelpU Student Website</span>
                </a>
            </div>
            <div class="col-2 col-lg-3 p-0">
                <button class="navbar-toggler ml-5" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation"> <!--Navbar's toggler-->
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse ml-5 ml-lg-0" id="navbarNav">
                    <ul class="navbar-nav ml-auto">
                            <?php
                                if(!$_SESSION["isLogin"]) { //if user is not logged in
                            ?>
                                    <li class="nav-item mx-1 pt-1">
                                        <a class="nav-link" class="text-right">
                                            <form method="POST">
                                                <img src="imgs/login_icon.png" alt="Login" width="23px" height="23px">
                                                <input type="submit" name="login" style="background-color: #a2c3fa; border-width: 0px; font-size:18px;" value="Login" /> <!--Logout at navbar to logout the user-->
                                            </form>
                                        </a>
                                    </li>

                                    <li class="nav-item mx-1 pt-1">
                                        <a class="nav-link" class="text-right">
                                            <form method="POST">
                                                <img src="imgs/signup_icon.png" alt="SignUp" width="23px" height="23px">
                                                <input type="submit" name="register" style="background-color: #a2c3fa; border-width: 0px; font-size:18px;" value="Register" /> <!--Logout at navbar to logout the user-->
                                            </form>
                                        </a>
                                    </li>
                                
                            <?php
                                }
                                else { //if user is logged in
                            ?>
                                <form method="POST">
                                    <li class="nav-item pt-1">
                                        <a class="nav-link">
                                        <img src="imgs/logout_icon.png" alt="Login" width="23px" height="23px">
                                            <input type="submit" name="logout" style="background-color: #a2c3fa; border-width: 0px; font-size:21px" value="Logout" /> <!--Logout at navbar to logout the user-->
                                        </a>
                                    </li>
                                </form>
                            <?php
                                }
                            ?>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</body>