<?php
$showAlert = false;
$showError = false;
if($_SERVER["REQUEST_METHOD"] == "POST")
{
    include 'connect_db.php';
    $name = $_POST["name"];
    $email = $_POST["email"];
    $regnumber = $_POST["regnumber"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm-password"];
    if($password == $confirm_password)
    {   
        $sql =" INSERT INTO `student`(name, registration_no, email, password)
        VALUES ('$name', '$regnumber', '$email', '$password')";
        $result = mysqli_query($conn,$sql);

        if($result)
        {
            $showAlert = true;
        }
    }
    else
    {
        $showError = "Password do not match";
    }
}




?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <link rel="stylesheet" href="style2.css" />
    <title>Attractive Signup Page</title>

   
   
  </head>
  <body>
    <?php
    if($showAlert)
    {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Congratution!</strong> Your account in now created and you can login
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>';
    }
    if($showError)
    {
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>Sorry!</strong> '.$showError.'
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>';
    }
 
    ?>
   
    <div class="login-container">
      <h1>Sign Up as Student</h1>
      <form
        action="signup-student.php"
        method="post"
      >
        <div class="form-group">
          <label for="name">Name</label>
          <input type="text" id="name" name="name" required />
        </div>
        <div class="form-group">
          <label for="email">Email</label>
          <input type="text" id="email" name="email" required />
        </div>
        <div class="form-group">
          <label for="regnumber">Registration Number</label>
          <input type="text" id="regnumber" name="regnumber" required />
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" required />
        </div>
        <div class="form-group">
          <label for="confirm-password">Confirm Password</label>
          <input
            type="password"
            id="confirm-password"
            name="confirm-password"
            required
          />
        </div>
        <div class="form-group">
          <center>
            <br /><br /><br /><br />
            <button type="submit">Register</button>
          </center>
        </div>
      </form>
      <p>Alread Registered?<a href="login-student.php">Login</a></p>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  </body>
</html>
