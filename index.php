<html>

<head>
    <title>Helpu Student Website</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="icon" type="image/png" href="imgs/helpu.png" />
</head>

<body style="background-color: #f3f3f3">

    <div class="container-fluid">
        <img src="imgs/helpu.png" alt="Helpu Student Website Logo" width="88" height="88" style="float:left;">
        <h1 style="background-color: #a2c3fa; text-align: center; padding: 25px">HELPU Student Website</h1>

        <nav class="navbar navbar-light" style="background-color: #a2c3fa;">
        
                <a class="navbar-brand" href="index.php">Index</a>
                <?php
                session_start();
                if (!isset($_SESSION['login']) || empty($_SESSION['login'])) {
                    echo "<a class='navbar-brand'href='index.php?p=login'>Log in </a>";
                } else {
                    echo "<a class='navbar-brand'href='logout.php'>Log Out</a>";
                }
                ?>
        </nav>
    </div>

    <?php

    if (isset($_GET["p"])) {
        switch ($_GET["p"]) {
            case "login":
                include_once 'login.php';
                break;
            default:
                include 'page_not_found.php';
                break;
        }
    }
    ?>
    <br>

    <?php
    include 'footer.php';
    ?>

</body>

</html>