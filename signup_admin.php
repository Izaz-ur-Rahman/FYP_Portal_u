<?php
$showAlert = false;
$showError = false;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  include 'connect_db.php';
  $name = $_POST["name"];
  $email = $_POST["email"];
  $cnic = $_POST["cnic"];
  $password = $_POST["password"];
  $confirm_password = $_POST["confirm-password"];

  // Check if the email already exists
  $checkEmailQuery = "SELECT * FROM admin WHERE email='$email'";
  $result = mysqli_query($conn, $checkEmailQuery);

  if (mysqli_num_rows($result) > 0) {
      $showError = "Email already exists. Please use a different email.";
  } elseif (strlen($password) < 8 || !preg_match("/[A-Z]/", $password) || !preg_match("/[a-z]/", $password) || !preg_match("/[0-9]/", $password) || !preg_match("/[!@#\$%^&*()_+]/", $password)) {
      $showError = "Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character (!@#\$%^&*()_+)";
  } elseif ($password != $confirm_password) {
      $showError = "Passwords do not match";
  } else {
      // Verify reCAPTCHA
      $recaptchaSecretKey = "6LcwaJkoAAAAAD0_z1N_GmB_jC-ld_KmXUo2upiy"; // Secret key
      $recaptchaResponse = $_POST['g-recaptcha-response'];
      $recaptchaVerify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptchaSecretKey}&response={$recaptchaResponse}");
      $recaptchaData = json_decode($recaptchaVerify);

      if ($recaptchaData->success) {
          // reCAPTCHA verification successful, continue with other form validation and processing
          $hashed_password = password_hash($password, PASSWORD_DEFAULT);

          $sql = "INSERT INTO `admin` (name, email, cnic, password) VALUES ('$name', '$email', '$cnic', '$hashed_password')";
          $result = mysqli_query($conn, $sql);

          if ($result) {
              $showAlert = true;
              header("Location: login_admin.php");
              exit();
          }
      } else {
          $showError = "reCAPTCHA verification failed. Please complete the CAPTCHA.";
      }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
    integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  <link rel="stylesheet" href="style2.css" />
  <title>Signup</title>
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
    <h1>Sign Up as Admin</h1>
    <form action="signup_admin.php" method="post">
      <div class="form-group">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" required />
      </div>
      <div class="form-group">
        <label for="email">Email</label>
        <input type="text" id="email" name="email" required />
      </div>
      <div class="form-group">
        <label for="cnic">CNIC</label>
        <input type="text" id="cnic" name="cnic" required />
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required />
      </div>
      <div class="form-group">
        <label for="confirm-password">Confirm Password</label>
        <input type="password" id="confirm-password" name="confirm-password" required />
      </div>
      <div class="g-recaptcha" data-sitekey="6LcwaJkoAAAAAO_wqLWyw8q54rW4VTotDPK_WSXd"></div>
      <div class="form-group">
        <center class="reg_professor">
          <button type="submit">Register</button>
        </center>
      </div>
    </form>
    <p class="already-login">Alread Registered?<a href="login_admin.php">Login</a></p>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>