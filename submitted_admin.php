<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'connect_db.php';
include 'header_admin.php';


  // Check if the teacher is authenticated (i.e., if the teacher's name is in the session)
  if (!isset($_SESSION["name"])) {
      // Redirect to the login page or display an error message
      echo '<div class="alert alert-danger" role="alert">Access denied. Please log in first.</div>';
      // You can also add a link to the login page for the user to log in.
      echo '<p ><a class="btn btn-primary" style="color:white; text-align:center;" href="login_admin.php">Log in</a></p>';
      // Optionally, you can exit the script to prevent further code execution.
      exit();
  }
$sql = "SELECT * FROM submitted WHERE checked=0";
$result = $conn->query($sql);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate the input
    $studentId = $_POST['student_id'];
    $marks = $_POST['marks'];

    // Update the marks and set checked to 1
    $updateSql = "UPDATE submitted SET marks = $marks, checked = 1 WHERE id = $studentId";
    $stmt = $conn->prepare($updateSql);

    if ($stmt->execute()) {
        // Marks updated successfully
        echo "Marks updated";
        header('Location: submitted_admin.php'); // Redirect to a success page
        exit();
    } else {
        // Error handling if the update fails
        echo 'Error updating marks: ' . $conn->error;
    }

    $stmt->close();
}

$sno=0;
?>


<!-- Rest of your HTML code remains the same -->

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
    integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous" />
  <link rel="stylesheet" href="style.css" />
  <title>Submitted</title>
</head>

<body>
  <table class="table">
    <thead>
      <tr>
        <th scope="col">Sno</th>
        <th scope="col">Name</th>
        <th scope="col">Registration No</th>
        <th scope="col">Title</th>
        <th scope="col">Submitted To</td>
        <th scope="col">Submitted Date</th>
        <th scope="col">Assign Marks</th>
        <th scope="col">Download</th>
      </tr>
    </thead>
    <tbody>
      <?php
        // Check if the query was successful
        if ($result) {
            // Check if there are any rows returned
            if (mysqli_num_rows($result) > 0) {
                // Loop through the rows of data and display them in a table
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<tr>';
                    echo '<td>' . ++$sno . '</td>';
                    echo '<td>' . $row['name'] . '</td>';
                    echo '<td>' . $row['registration_no'] . '</td>';
                    echo '<td>' . $row['title'] . '</td>';
                    echo '<td>' . $row['teacher'] . '</td>';
                    echo '<td>' . $row['submitted_date'] . '</td>';
                    echo '<td><button class="btn btn-primary assign-marks-btn" data-student-id="' . $row['id'] . '" data-student-name="' . $row['name'] . '" data-student-registration="' . $row['registration_no'] . '" data-student-title="' . $row['title'] . '">Assign Marks</button></td>';
                    echo '<td><a href="download.php?file=' . urlencode($row['file_reference']) . '" class="btn btn-success">Download</a></td>';
                    echo '</tr>';
                }
                echo '</table>';
            } else {
                // No records found
                echo '<div class="alert alert-info" role="alert">No records found.</div>';

            }
            // Free the result set
            mysqli_free_result($result);
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
          <form id="assignMarksForm" action="submitted_admin.php" method="post">
            <div class="form-group">
              <label for="name">Name:</label>
              <input type="text" class="form-control" id="name" name="name" required readonly />
            </div>
            <div class="form-group">
              <label for="registration_no">Registration NO:</label>
              <input type="text" class="form-control" id="registration_no" name="registration_no" required readonly />
            </div>
            <div class="form-group">
              <label for="title">Title</label>
              <input type="text" class="form-control" id="title" name="title" required readonly />
            </div>

            <input type="hidden" id="student_id" name="student_id" />

            <div class="form-group">
              <label for="marks">Assign Marks</label>
              <input type="number" class="form-control" id="marks" name="marks" required />
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
          </form>
        </div>
      </div>
    </div>
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
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const assignMarksButtons = document.querySelectorAll(".assign-marks-btn");
      assignMarksButtons.forEach(function (button) {
        button.addEventListener("click", function () {
          $("#assignMarksModal").modal("show");
        });
      });
    });
  </script>
</body>

</html>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const assignMarksButtons = document.querySelectorAll(".assign-marks-btn");
    assignMarksButtons.forEach(function (button) {
      button.addEventListener("click", function () {
        const studentId = this.getAttribute("data-student-id");
        const studentName = this.getAttribute("data-student-name");
        const studentRegistration = this.getAttribute("data-student-registration");
        const studentTitle = this.getAttribute("data-student-title");

        document.getElementById("name").value = studentName;
        document.getElementById("registration_no").value = studentRegistration;
        document.getElementById("title").value = studentTitle;

        // Set the student id as a value or attribute in the form, so you can access it when the form is submitted
        document.getElementById("student_id").value = studentId;

        $("#assignMarksModal").modal("show");
      });
    });
  });

  document.addEventListener("DOMContentLoaded", function () {
    const assignMarksButtons = document.querySelectorAll(".assign-marks-btn");
    assignMarksButtons.forEach(function (button) {
      button.addEventListener("click", function () {
        const studentId = this.getAttribute("data-student-id");
        const studentName = this.getAttribute("data-student-name");
        const studentRegistration = this.getAttribute("data-student-registration");
        const studentTitle = this.getAttribute("data-student-title");

        document.getElementById("name").value = studentName;
        document.getElementById("registration_no").value = studentRegistration;
        document.getElementById("title").value = studentTitle;

        // Set the student id as a value in the hidden field
        document.getElementById("student_id").value = studentId;

        $("#assignMarksModal").modal("show");
      });
    });
  });




</script>