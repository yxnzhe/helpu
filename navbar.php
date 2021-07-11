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
    ?>
    <div class="container-fluid">
    <nav class="navbar navbar-light" style="background-color: #a2c3fa;">
        <img src="imgs/helpu.png" alt="Helpu Student Website Logo" width="88" height="88" style="float:left;">
        <h1 style="text-align: center; padding: 25px">HELPU Student Website</h1>
   
       <a class="navbar-brand" class="text-right" href="index.php?p=1">Log in</a>
        </nav>
    </div>
  
</body>