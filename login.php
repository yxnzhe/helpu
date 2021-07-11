<?php
require_once 'function.php'; 
$conn = connectDb(); 

if (isset($_POST['submit'])) { 
  $email = stripslashes($_REQUEST['email']);
  //the stripslahes will remove all the quotation mark to prevent error with the database, since the data are input using html.
  $email = mysqli_real_escape_string($conn, $email);
  // the mysqli_real_escape_string is to prevent an error where special characters string could be an error using in as a SQL statement  
  $password = stripslashes($_REQUEST['password']);
  $password = mysqli_real_escape_string($conn, $password);

  $query = "SELECT * FROM user WHERE email='$email' AND password ='$password'"; 
  $result = mysqli_query($conn, $query) or die(mysqli_error($conn)); 
  $rows = mysqli_num_rows($result); 

  if ($rows == 1) { 
    $_SESSION['login'] = $email;
    header("Location: index.php"); 
  } else { 
    echo "<br/><div style='background-color:#e6505a;'><h5><center>Incorrect Username/ Password</center></h5></div><br/>";
  }
}
?>
<html>

<body>

  <div class="container-fluid">
    <h3>Login Form</h3>
    <!-- this section is the log in form  -->
    <form method="POST">
      <div class="mb-3">
        <label for="exampleFormControlInput1" class="form-label">Email</label>
        <input type="text" class="form-control" name="email" placeholder="Email.." required>
      </div>

      <div class="mb-3">
        <label for="exampleFormControlInput1" class="form-label">Password</label>
        <input type="password" class="form-control" name="password" placeholder="password.." required>
      </div>

      <div class="mb-3">
        <button type="submit" name="submit" value="Login" class="btn btn-warning">Login</button>
      </div>
    </form>

  </div>
</body>

</html>