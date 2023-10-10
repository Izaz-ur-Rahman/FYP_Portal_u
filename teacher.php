<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$login = false;
$showError = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'connect_db.php';
    $cnic = $_POST["cnic"];
    $password = $_POST["password"];

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
            'content' => http_build_query($data)
        )
    );
    $context = stream_context_create($options);
    $verify = file_get_contents($url, false, $context);
    $captchaSuccess = json_decode($verify)->success;

    // Query to retrieve teacher based on CNIC and password
    $sql = "SELECT * FROM teachers WHERE cnic = '$cnic' AND password = '$password'";
    $result = mysqli_query($conn, $sql);
    $teacher = mysqli_fetch_assoc($result);

    if ($teacher) {
        // Teacher's credentials are valid
        $login = true;
        session_start();
        $_SESSION['loggedin'] = true;

        // Store teacher information in the session
        $_SESSION['teacher_id'] = $teacher['id'];
        $_SESSION['teacher_name'] = $teacher['name'];
        $_SESSION['teacher_cnic'] = $teacher['cnic']; // Store CNIC in the session

        // Query to retrieve submitted projects based on teacher's CNIC
        $teacherCnic = $teacher['cnic'];
        $submittedQuery = "SELECT * FROM submitted WHERE cnic = '$teacherCnic'";
        $submittedResult = mysqli_query($conn, $submittedQuery);

        // Fetch submitted projects and store them in the session
        if ($submittedResult) {
            $_SESSION['submitted_projects'] = mysqli_fetch_all($submittedResult, MYSQLI_ASSOC);
        }

        // Redirect to teacher.php or display other content as needed
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
<!-- Rest of your HTML code remains the same -->

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
    integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous" />
  <link rel="stylesheet" href="style.css" />

  <title>Teacher Page</title>
</head>

<body>
  <?php include 'header_teacher.php'; ?>
  <?php include 'connect_db.php'; ?>
  <table class="table">
    <thead>
      <tr>
        <th scope="col">Sno</th>
        <th scope="col">Name</th>
        <th scope="col">Registration No</th>
        <th scope="col">Title</th>
        <th scope="col">Submitted Date</th>
        <th scope="col">Assign Marks</th>
        <th scope="col">Download</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $sno = 0; // Initialize sno
      // Check if the query was successful
      if ($submittedResult) {
          // Check if there are any rows returned
          if (mysqli_num_rows($submittedResult) > 0) {
              // Loop through the rows of data and display them in a table
              while ($row = mysqli_fetch_assoc($submittedResult)) {
                  echo '<tr>';
                  echo '<td>' . ++$sno . '</td>';
                  echo '<td>' . $row['name'] . '</td>';
                  echo '<td>' . $row['registration_no'] . '</td>';
                  echo '<td>' . $row['title'] . '</td>';
                  echo '<td>' . $row['submitted_date'] . '</td>';
                  echo '<td><button class="btn btn-primary assign-marks-btn" data-student-id="' . $row['id'] . '">Assign Marks</button></td>';
                  echo '<td><a href="download.php?file=' . urlencode($row['file_reference']) . '" class="btn btn-success">Download</a></td>';
                  echo '</tr>';
              }
              echo '</table>';
          } else {
              // No records found
              echo 'No records found.';
          }
          // Free the result set
          mysqli_free_result($submittedResult);
      } else {
          // Error handling if the query fails
          echo 'Error: ' . mysqli_error($conn);
      }
      ?>
    </tbody>
  </table>

  <!-- Modal for Assigning Marks -->
  <div class="modal fade" id="assignMarksModal" tabindex="-1" role="dialog" aria-labelledby="assignMarksModalLabel"
    aria-hidden="true">
    <!-- ... Rest of your modal code ... -->
  </div>

  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
    integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
    integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
    integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
    crossorigin="anonymous"></script>

  <!-- JavaScript for Opening Modal -->
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      // Handle the click event for the "Assign Marks" button
      const assignMarksButtons =
        document.querySelectorAll(".assign-marks-btn");
      assignMarksButtons.forEach(function (button) {
        button.addEventListener("click", function () {
          // Show the modal
          $("#assignMarksModal").modal("show");
        });
      });
    });
  </script>
</body>

</html>