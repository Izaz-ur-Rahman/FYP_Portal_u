<?php
session_start(); // Start the session if it's not already started
error_reporting(E_ALL);
ini_set('display_errors', 1);

$login = false;
$showError = false;

// Check if the teacher is logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    // Teacher's CNIC from the session
    $teacherCnic = $_SESSION['teacher_cnic'];

    // You can use the $teacherCnic to query the submitted table
    include 'connect_db.php'; // Include your database connection code

    // First query to fetch the name based on CNIC
    $nameQuery = "SELECT name FROM teacher WHERE cnic = '$teacherCnic'";
    $nameResult = mysqli_query($conn, $nameQuery);

    if ($nameResult) {
        $row = mysqli_fetch_assoc($nameResult);
        $teacherName = $row['name'];

        // Second query to fetch records from the submitted table based on teacher's name
        $sql2 = "SELECT * FROM submitted WHERE name = '$teacherName'";
        $result2 = mysqli_query($conn, $sql2);

        // ... Rest of your HTML code for displaying the submitted records ...

        // Close the database connection when done
        mysqli_close($conn);
    } else {
        // Handle the case where the name query fails
        echo "Error fetching teacher's name: " . mysqli_error($conn);
    }
} else {
    // Redirect to the login page if the teacher is not logged in
    header("location: login-professor.php");
    exit; // Make sure to exit after redirection
}
?>

<!-- Rest of your HTML code for displaying the records... -->

<!-- Rest of your HTML code for displaying the records... -->

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
      if ($sql2) {
          // Check if there are any rows returned
          if (mysqli_num_rows($result2) > 0) {
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
          mysqli_free_result($result2);
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