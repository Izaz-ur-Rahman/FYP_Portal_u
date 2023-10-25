<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
<<<<<<< HEAD
session_start();
=======

>>>>>>> c31ce7ba3f969ba68344d18f0e3286603692987e
$login = false;
$showError = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'connect_db.php';
<<<<<<< HEAD
    $cnic = $_POST["cnic"];
    $password = $_POST["password"];
    $name = $_POST["name"];
=======

    $cnic = $_POST["cnic"];
    $password = $_POST["password"];
>>>>>>> c31ce7ba3f969ba68344d18f0e3286603692987e

    // Verify reCAPTCHA response
    $recaptchaSecretKey = "6LdElo0oAAAAAMIgSbku7V9sBCmeiKjZ5avk4Q9b";
    $recaptchaResponse = $_POST['g-recaptcha-response'];
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = array(
        'secret' => $recaptchaSecretKey,
        'response' => $recaptchaResponse
    );
    $options = array(
        'http' => array(
            'method' => 'POST',
<<<<<<< HEAD
            'header' => 'Content-type: application/x-www-form-urlencoded', // Specify Content-type
=======
>>>>>>> c31ce7ba3f969ba68344d18f0e3286603692987e
            'content' => http_build_query($data)
        )
    );
    $context = stream_context_create($options);
    $verify = file_get_contents($url, false, $context);
    $captchaSuccess = json_decode($verify)->success;

    // Query to retrieve teacher based on CNIC and password
<<<<<<< HEAD
    $sql = "SELECT * FROM teacher WHERE cnic = '$cnic' AND name = '$name'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);

    if ($row) {

      $storedPassword = $row['password'];

      // Verify the provided password against the stored hashed password
      if (password_verify($password, $storedPassword)) {
          $login = true;

          $_SESSION['loggedin'] = true;
          $_SESSION["teacher_name"] = $name;

          echo "Redirecting..."; // Add this line for debugging
          header("location: teacher.php");
          exit;
      } else {
          $showError = "Sorry! Invalid password";
      }
  } else {
      $showError = "Sorry!user not found";
  }

  if (!$captchaSuccess) {
      $showError = "Please complete the CAPTCHA.";
  }
}
?>

=======
    $sql = "SELECT * FROM teacher WHERE cnic = '$cnic' AND password = '$password'";
    $result = mysqli_query($conn, $sql);
    $num = mysqli_num_rows($result);

    if ($num == 1) {
        $login = true;
        session_start();
        $_SESSION['loggedin'] = true;

        // Store the teacher's CNIC in the session
        $_SESSION['teacher_cnic'] = $cnic;

        echo "Redirecting..."; // Add this line for debugging
        header("location: teacher.php");
        exit; // Make sure to exit after redirection
    } else {
        $showError = "Sorry! Invalid Credentials";
    }

    if (!$captchaSuccess) {
        $showError = "Please complete the CAPTCHA.";
    }
}
?>


>>>>>>> c31ce7ba3f969ba68344d18f0e3286603692987e
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
    integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
  <link rel="stylesheet" href="style_1.css" />
<<<<<<< HEAD
  <title>Login</title>
=======
  <title>Attractive Login Page</title>
>>>>>>> c31ce7ba3f969ba68344d18f0e3286603692987e
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>

<body>
  <?php
    if ($login) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Congratulations!</strong> You are Logged In.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>';
    }
    if ($showError) {
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>Sorry!</strong> ' . $showError . '
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>';
    }
    ?>
  <div class="login-container">
    <h1>Login as Professor</h1>
    <form action="login-professor.php" method="post">
<<<<<<< HEAD
      <div class="form-group">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" required />
      </div>
=======

>>>>>>> c31ce7ba3f969ba68344d18f0e3286603692987e
      <div class="form-group">
        <label for="cnic">CNIC</label>
        <input type="text" id="username" name="cnic" required />
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required />
      </div>
      <div class="form-group">
        <!-- Add the reCAPTCHA widget here -->
        <div class="g-recaptcha" data-sitekey="6LdElo0oAAAAAJBRIpdRcJbzNnPIuTrjRA1x_v7u"></div>
      </div>
      <div class="form-group">
        <center>
          <button type="submit">Login</button>
        </center>
      </div>
    </form>
    <p>Not Registered? <a href="signup-professor.php">Sign up</a></p>
<<<<<<< HEAD
    <a href="home.php">
      <button>Back to Home</button>
=======
    <a href="login-student.php">
      <button>Login as Student</button>
>>>>>>> c31ce7ba3f969ba68344d18f0e3286603692987e
    </a>
  </div>
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
    integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
    integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
    integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
    crossorigin="anonymous"></script>
</body>

</html>