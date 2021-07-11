<html>
<head>
    <?php
        require_once "navbar.php";
    ?>
</head>

<body style="background-color: #f3f3f3">

    <div class="container-fluid">
        <img src="imgs/helpu.png" alt="Helpu Student Website Logo" width="88" height="88" style="float:left;">
        <h1 style="background-color: #a2c3fa; text-align: center; padding: 25px">HELPU Student Website</h1>
                <?php
                    if (!isset($_SESSION['login']) || empty($_SESSION['login'])) {
                        echo "<a class='navbar-brand'href='index.php?p=login'>Log in </a>";
                    } else {
                        echo "<a class='navbar-brand'href='logout.php'>Log Out</a>";
                    }
                ?>
        </nav>
    </div>
    <br>
</body>
<?php include 'footer.php';?>
