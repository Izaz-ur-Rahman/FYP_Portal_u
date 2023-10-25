<?php
$login = false;
$showError = false;
session_start();

// Your reCAPTCHA secret key
$recaptchaSecretKey = '6Ldpj40oAAAAAKGkhhDcy7dyeSsfrdkVGLiJgYaT';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'connect_db.php';
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $recaptchaResponse = $_POST["g-recaptcha-response"];

    // Verify reCAPTCHA response
    $recaptchaUrl = 'https://www.google.com/recaptcha/api/siteverify';
    $recaptchaData = [
        'secret' => $recaptchaSecretKey,
        'response' => $recaptchaResponse,
        'remoteip' => $_SERVER['REMOTE_ADDR'],
    ];

    $recaptchaOptions = [
        CURLOPT_URL => $recaptchaUrl,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query($recaptchaData),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false, // This is added for local development
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/x-www-form-urlencoded',
        ],
    ];

    $recaptchaCurl = curl_init();
    curl_setopt_array($recaptchaCurl, $recaptchaOptions);
    $recaptchaResult = curl_exec($recaptchaCurl);
    curl_close($recaptchaCurl);

    $recaptchaResult = json_decode($recaptchaResult);

    if ($recaptchaResult->success) {
        // CAPTCHA verification passed, continue with user authentication
        $sql = "SELECT * FROM student WHERE email = '$email' AND password = '$password' AND name = '$name'";
        $result = mysqli_query($conn, $sql);
        $num = mysqli_num_rows($result);

        if ($num == 0 ) {
            $login = true;
            $_SESSION['loggedin'] = true;
            $_SESSION['name'] = $_POST["name"];
            header("location: submitted_student.php");
            exit();
        } else {
            $showError = "Sorry! Invalid Credentials";
        }
    } else {
        $showError = "reCAPTCHA verification failed. Please check the CAPTCHA box.";
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
  <link rel="stylesheet" href="style_1.css" />
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>

  <title>Login</title>
</head>

<body>
  <?php
    if($login)
    {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Congratution!</strong> You are Logged In.
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
    <h1>Login as Student</h1>
    <form action="login-student.php" method="post">
      <div class="form-group">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" required />
      </div>
      <div class="form-group">
        <label for="email">Email</label>
        <input type="text" id="username" name="email" required />
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required />
      </div>
      <div class="g-recaptcha cap" data-sitekey="6Ldpj40oAAAAABDxO5u_upi6km1HrjmiNFl04ECR"></div>

      <div class="form-group">
        <center>

          <br><button type="submit" class="btn">Login</button><br />
        </center>
      </div>
    </form>

    <p>Not Registered? <a href="signup-student.php">Sign up</a></p>

    <a href="home.php">
      <button class="btn btn-primary">Back to Home</button><br /><br />
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