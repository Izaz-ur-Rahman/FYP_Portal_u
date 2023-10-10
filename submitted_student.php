<?php
include('header.php');

// Include your database connection file (e.g., 'connect_db.php')
include('connect_db.php');

// Fetch teacher names from the database
$teacherQuery = "SELECT name FROM teacher";
$teacherResult = mysqli_query($conn, $teacherQuery);
$teachers = [];

if ($teacherResult) {
    while ($row = mysqli_fetch_assoc($teacherResult)) {
        $teachers[] = $row['name'];
    }
}


// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if a file was uploaded
    if (isset($_FILES["file"]) && $_FILES["file"]["error"] == 0) {
        // Specify the folder where you want to save the uploaded files
        $uploadDir = "upload_files/";

        // Generate a unique filename to avoid overwriting existing files
        $fileName = uniqid() . "_" . $_FILES["file"]["name"];
        $targetPath = $uploadDir . $fileName;

        // Move the uploaded file to the specified folder
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetPath)) {
            // File was successfully uploaded

            // Retrieve form data
            $name = $_POST["name"];
            $regNo = $_POST["regNo"];
            $title = $_POST["title"];
            $teacher = $_POST["teacher"];

            // Insert student data into the "submitted" table
            $stmt = $conn->prepare("INSERT INTO submitted (name, registration_no, title, teacher, file_reference) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $name, $regNo, $title, $teacher, $fileName);

            if ($stmt->execute()) {
                // Record inserted successfully
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                  <strong>Congratulation!</strong> Record inserted successfully.
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>';
            } else {
                // Error handling if the insertion fails
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <strong>Sorry!</strong>Your inserting was failed.
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>';
            }

            $stmt->close();
        } else {
            // Error handling if file upload fails
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
              <strong>Sorry!</strong>File upload failed.
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>';
        }
    } else {
        // Error handling if no file was uploaded
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
          <strong>Please!</strong>Select a file to upload.
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Submitted</title>
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
    integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
    integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
    integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
    crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
    integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const addTaskModal = new bootstrap.Modal(document.getElementById('addTaskModal'));

      document.querySelector('.add-new-task').addEventListener('click', function () {
        addTaskModal.show();
      });
    });
  </script>
</head>

<body>

  <table class="table">
    <thead>
      <tr>
        <th scope="col">Sno</th>
        <th scope="col">Name</th>
        <th scope="col">Reg No</th>
        <th scope="col">Title</th>
        <th scope="col">Submitted Date</th>
        <th scope="col">Assigned To</th>
        <th scope="col">Project</th>
      </tr>
    </thead>
    <tbody>
      <?php


      // Perform a database query to fetch data from the "submitted" table
      $query = "SELECT * FROM submitted";
      $result = mysqli_query($conn, $query);
       $sno = 0;
      // Check if the query was successful
      if ($result) {
          // Check if there are any rows returned
          if (mysqli_num_rows($result) > 0) {


              // Loop through the rows of data and display them in a table
              while ($row = mysqli_fetch_assoc($result)) {
                  echo '<tr>';
                  echo '<td>' . ++$sno. '</td>';
                  echo '<td>' . $row['name'] . '</td>';
                  echo '<td>' . $row['registration_no'] . '</td>';
                  echo '<td>' . $row['title'] . '</td>';
                  echo '<td>' . $row['submitted_date'] . '</td>';
                  echo '<td>' . $row['teacher'] . '</td>';
                  echo '<td><a href="download.php?file=' . urlencode($row['file_reference']) . '" class="btn btn-success">Download</a></td>';
                  echo '</tr>';
              }

              echo '</table>';
          } else {
              // No records found
              echo 'No records found.';
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

  <div class="d-flex justify-content-center">
    <!-- "No Record Found" button remains the same -->
  </div><br>

  <div class="d-flex justify-content-center">
    <button class="btn btn-primary add-new-task" data-toggle="modal" data-target="#addTaskModal">ADD New +</button>
  </div>

  <!-- Modal for adding a new record -->
  <div class="modal fade" id="addTaskModal" tabindex="-1" role="dialog" aria-labelledby="addTaskModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addTaskModalLabel">Add New</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- Form for adding a new record -->
          <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
              <label for="name">Name</label>
              <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
              <label for="regNo">Registration Number</label>
              <input type="text" class="form-control" id="regNo" name="regNo" required>
            </div>
            <div class="form-group">
              <label for="title">Title</label>
              <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="form-group">
              <label for="teacher">Teacher</label>
              <select class="form-control" id="teacher" name="teacher" required>
                <?php
                // Populate the dropdown options with teacher names from the database
                foreach ($teachers as $teacherName) {
                    echo '<option value="' . $teacherName . '">' . $teacherName . '</option>';
                }
                ?>
              </select>
            </div>
            <div class="form-group">
              <label for="file">Upload File</label>
              <small><i>(File Should be Less than 2.00 GB)</i></small>
              <input type="file" class="form-control-file" id="file" name="file" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>

</html>