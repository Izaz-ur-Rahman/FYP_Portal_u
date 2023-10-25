<?php
<<<<<<< HEAD
$showError = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  include 'connect_db.php';
  $name = $_POST["name"];
  $email = $_POST["email"];
  $regno = $_POST["regnumber"];
  $password = $_POST["password"];
  $confirm_password = $_POST["confirm-password"];
  $supervisor = $_POST["supervisor"];

    // Verify reCAPTCHA
    $recaptchaSecretKey = "6LfiHJIoAAAAACqVeEEcAWHhGaaTb4Fkt1Ydwwg6"; // Replace with your secret key
    $recaptchaResponse = $_POST["g-recaptcha-response"];
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = [
        'secret' => $recaptchaSecretKey,
        'response' => $recaptchaResponse
    ];
    $options = [
        'http' => [
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data)
        ]
    ];
    $context = stream_context_create($options);
    $verify = file_get_contents($url, false, $context);
    $captchaSuccess = json_decode($verify)->success;

    if (!$captchaSuccess) {
        $showError = "reCAPTCHA verification failed. Please prove you're not a robot.";
    } elseif (strlen($password) < 8 || !preg_match("/[A-Z]/", $password) || !preg_match("/[a-z]/", $password) || !preg_match("/[0-9]/", $password) || !preg_match("/[!@#\$%^&*()_+]/", $password)) {
        $showError = "Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character (!@#\$%^&*()_+)";
    } elseif ($password != $confirm_password) {
        $showError = "Passwords do not match";
    } else {
        // Check if the email already exists
        $checkEmailQuery = "SELECT * FROM student WHERE email='$email'";
        $result = mysqli_query($conn, $checkEmailQuery);

        if (mysqli_num_rows($result) > 0) {
            $showError = "Email already exists. Please use a different email.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO `student` (name, email, registration_no, password, supervisor) VALUES ('$name', '$email', '$regno', '$hashed_password', '$supervisor')";
            $result = mysqli_query($conn, $sql);

            if ($result) {
              header("Location: login-student.php");
              exit();
            }
        }
    }
}

        // this code for supervisor in dropdown
        include 'connect_db.php';
        $supervisors = array();

        // Query to fetch teacher names where supervisor = 1
        $supervisorQuery = "SELECT name FROM teacher WHERE supervisor = 1";

        // Execute the query
        $supervisorResult = mysqli_query($conn, $supervisorQuery);

        // Check if the query was successful
        if ($supervisorResult) {
            // Fetch and store teacher names in the array
            while ($row = mysqli_fetch_assoc($supervisorResult)) {
                $supervisors[] = $row['name'];
            }
        } else {
            // Handle an error if the query fails
            echo "Error: " . mysqli_error($conn);
        }

        // Close the database connection
        mysqli_close($conn);
 ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <link rel="stylesheet" href="style2.css">
    <title>Signup</title>
</head>

<body>
    <?php
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
        <h1>Sign Up as Student</h1>
        <form action="signup-student.php" method="post">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="regnumber">Registration Number</label>
                <input type="text" id="regnumber" name="regnumber" required>
            </div>
            <div class="form-group">
                <label for="supervisor">Supervisor</label>
                <select id="supervisor" name="supervisor" required class="custom-select w-50" style="height: 40px;">
                    <?php
                    foreach ($supervisors as $supervisorName) {
                        // Populate the dropdown with supervisor options
                        echo '<option value="' . $supervisorName . '">' . $supervisorName . '</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm-password">Confirm Password</label>
                <input type="password" id="confirm-password" name="confirm-password" required>
            </div>
            <div class="g-recaptcha" data-sitekey="6LfiHJIoAAAAAOeTs2ELHSZ0nJX3cnG8ZLY1Yt9-"></div>

            <div class="form-group">
                <center class="reg">
                    <button type="submit">Register</button>
                </center>
            </div>
        </form>

        <p class="already-login">Already Registered? <a href="login-student.php">Login</a></p>

    </div>
    </div>

    <!-- Include jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
=======
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
>>>>>>> c31ce7ba3f969ba68344d18f0e3286603692987e
