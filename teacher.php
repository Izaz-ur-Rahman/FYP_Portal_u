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
    $sql = "SELECT * FROM teacher WHERE cnic = '$cnic' AND password = '$password'";
    $result = mysqli_query($conn, $sql);
    $num = mysqli_num_rows($result);

    if ($num == 1) {
        // Teacher's credentials are valid
        $login = true;
        session_start();
        $_SESSION['loggedin'] = true;

        // Store the teacher's CNIC in the session
        $_SESSION['teacher_cnic'] = $cnic;

        // Fetch the teacher's name from the database based on CNIC
        $row = mysqli_fetch_assoc($result);
        $_SESSION['teacher_name'] = $row['name']; // Adjust the column name as needed

        // Query to retrieve entries from the submitted table
        $teacherName = $_SESSION['teacher_name'];
        $sessionName = $_SESSION['name']; // Replace with the appropriate session name
        $submittedQuery = "SELECT * FROM submitted WHERE teacher_name = '$teacherName' AND session_name = '$sessionName'";
        $submittedResult = mysqli_query($conn, $submittedQuery);

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

  <title>Registration</title>
</head>

<body>
  <?php include 'header.php'; ?>
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
            // Check if the query was successful
            if ( $submittedResult) {
                // Check if there are any rows returned
                if (mysqli_num_rows( $submittedResult) > 0) {
                    // Loop through the rows of data and display them in a table
                    while ($row = mysqli_fetch_assoc( $submittedResult)) {
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
                mysqli_free_result( $submittedResult);
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
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="assignMarksModalLabel">Assign Marks</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- Add your form for assigning marks here -->
          <form>
            <div class="form-group">
              <label for="marks">Name:</label>
              <input type="number" class="form-control" id="marks" name="marks" required />
            </div>
            <div class="form-group">
              <label for="marks">Registration NO:</label>
              <input type="number" class="form-control" id="marks" name="marks" required />
            </div>
            <div class="form-group">
              <label for="marks">Title</label>
              <input type="number" class="form-control" id="marks" name="marks" required />
            </div>
            <div class="form-group">
              <label for="marks">Assign Marks</label>
              <input type="number" class="form-control" id="marks" name="marks" required />
            </div>
            <!-- Add any other form fields you need -->
            <button type="submit" class="btn btn-primary">Submit</button>
          </form>
        </div>
      </div>
    </div>
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