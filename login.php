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