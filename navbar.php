<head>
    <title>Helpu Student Website</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="..\helpu\css\style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="icon" type="image/png" href="imgs/helpu.png" />
</head>

<body>
<?php
    session_start();
    require_once "function.php";

    if(!isset($_SESSION["isLogin"])) { //if isLogin is not initiated before
        $_SESSION["isLogin"] = false; //to initiate isLogin as false
    }

    if(isset($_POST["logout"])) { //if user clicks logout button
        session_destroy(); //session will be destroyed
        header("Refresh:0"); //website will be refreshed
        header("Location: index.php"); //user will be redirected to index.php
    }
?>
    <div class="container-fluid" style="padding: 0px">
        <nav class="navbar navbar-light" style="background-color: #a2c3fa;">
            <img src="imgs/helpu.png" alt="Helpu Student Website Logo" width="88" height="88" style="float:left;">
            <h1 style="text-align: center; padding: 25px">HELPU Student Website</h1>
            <?php
                if(!$_SESSION["isLogin"]) { //if user is not logged in
            ?>
                <a class="navbar-brand" class="text-right" href="login.php">Login</a>
            <?php
                }
                else { //if user is logged in
            ?>
                <form method="POST">
                    <a class="nav-link">
                        <input type="submit" name="logout" class="bg-navbar" style="border-width: 0px; font-size:21px" value="Logout" /> <!--Logout at navbar to logout the user-->
                    </a>
                </form>
            <?php
                }
            ?>
        </nav>
    </div>
  
</body>