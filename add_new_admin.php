<?php
include 'connect_db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate the input
    $name = $_POST['name'];
    $registrationNo = $_POST['registration_no'];
    $title = $_POST['title'];
    $teacher = $_POST['teacher']; // Assuming teacher name is selected from a dropdown
    $file = $_FILES['file']['name'];

    // Move the uploaded file to a designated directory (e.g., uploads/)
    $uploadDir = 'uploads/';
    $targetFile = $uploadDir . basename($file);

    if (move_uploaded_file($_FILES['file']['tmp_name'], $targetFile)) {
        // File uploaded successfully
        // Insert the data into the submitted table
        $insertSql = "INSERT INTO submitted (name, registration_no, title, teacher, file_reference)
                      VALUES ('$name', '$registrationNo', '$title', '$teacher', '$file')";

        if ($conn->query($insertSql) === TRUE) {
            echo "Task submitted successfully";
            header('Location: teacher.php'); // Redirect to a success page
            exit();
        } else {
            echo 'Error inserting data: ' . $conn->error;
        }
    } else {
        echo 'Error uploading file';
    }
}
?>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'connect_db.php';
include 'header_admin.php';
session_start();

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
        header('Location: teacher.php'); // Redirect to a success page
        exit();
    } else {
        // Error handling if the update fails
        echo 'Error updating marks: ' . $conn->error;
    }

    $stmt->close();
}

$sno=0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
    integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous" />
  <link rel="stylesheet" href="style.css" />
  <title>Registration</title>
</head>

<body>
  <table class="table">
    <thead>
      <tr>
        <th scope="col">Sno</th>
        <th scope="col">Name</th>
        <th scope="col">Registration No</th>
        <th scope="col">Title</th>
        <th scope="col">Submitted Date</th>
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
                    echo '<td>' . $row['submitted_date'] . '</td>';
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
          <form id="assignMarksForm" action="teacher.php" method="post">
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

  <!-- Add New Button -->
  <div class="d-flex justify-content-center">
    <button class="btn btn-primary add-new-task" data-toggle="modal" data-target="#addTaskModal">ADD New +</button>
  </div>
  <!-- Modal for Adding New Task -->
  <div class="modal fade" id="addTaskModal" tabindex="-1" role="dialog" aria-labelledby="addTaskModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addTaskModalLabel">Add New Task</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="add_new_admin.php" method="post">
            <div class="form-group">
              <label for="name">Student Name</label>
              <input type="text" class="form-control" id="name" name="name" required />
            </div>
            <div class="form-group">
              <label for="registration_no">Registration No</label>
              <input type="text" class="form-control" id="registration_no" name="registration_no" required />
            </div>
            <div class="form-group">
              <label for="title">Title</label>
              <input type="text" class="form-control" id="title" name="title" required />
            </div>
            <div class="form-group">
              <label for="teacher">Teacher</label>
              <select class="form-control" id="teacher" name="teacher" required>
                <!-- Options will be populated dynamically via JavaScript -->
              </select>
            </div>
            <div class="form-group">
              <label for="file">File</label>
              <input type="file" class="form-control" id="file" name="file" accept=".pdf,.doc,.docx" required />
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

      const addNewTaskButton = document.querySelector(".add-new-task");

      addNewTaskButton.addEventListener("click", function () {
        const addTaskModal = document.querySelector("#addTaskModal");
        addTaskModal.querySelector("form").reset();
        addTaskModal.querySelector("#teacher").innerHTML = ""; // Clear previous options

        // Fetch teacher names from the database and populate the dropdown
        fetch('get_teachers.php') // Assuming you have a PHP file that fetches teacher names
          .then(response => response.json())
          .then(data => {
            const teacherDropdown = addTaskModal.querySelector("#teacher");
            data.teachers.forEach(function (teacherName) {
              const option = document.createElement("option");
              option.value = teacherName;
              option.textContent = teacherName;
              teacherDropdown.appendChild(option);

            });
          });

        $("#addTaskModal").modal("show");
      });
    });
  </script>
</body>

</html>
<?php
// Assuming you have a connection to the database in connect_db.php
include 'connect_db.php';

// Fetch teacher names from the database
$sql = "SELECT name FROM teacher";
$result = $conn->query($sql);

$teachers = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $teachers[] = $row['name'];
    }
}

?>