<<<<<<< HEAD
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
  integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

<?php

session_start();

// Check if 'name' is not set in the session
if (!isset($_SESSION['name'])) {
    // Redirect to the login page or display an error message
    echo '<div class="alert alert-danger" role="alert">Access denied. Please log in first.</div>';
    // You can also add a link to the login page for the user to log in.
    echo '<p ><a class="btn btn-primary" style="color:white; text-align:center;" href="login-student.php">Log in</a></p>';
    // Optionally, you can exit the script to prevent further code execution.
    exit();
}

?>


<?php

include ('connect_db.php');
include('header_1.php');

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST")
  // Check if a file was uploaded
  if (isset($_FILES["file"]) && $_FILES["file"]["error"] == 0) {
    // Specify the folder where you want to save the uploaded files
    $uploadDir = "upload_files/";

    // Generate a unique filename to avoid overwriting existing files
    $fileName = uniqid() . "_" . $_FILES["file"]["name"];
    $targetPath = $uploadDir . $fileName;

    // Move the uploaded file to the specified folder
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetPath)) {

      // Retrieve form data
      $name = $_POST["name"];
      $regNo = $_POST["regNo"];
      $title = $_POST["title"];
      $teacher = $_POST["supervisor"];
      // Retrieve the selected project type from the dropdown
      $projectType = $_POST["projectType"];



      // Insert student data into the "submitted" table
      $stmt = $conn->prepare("INSERT INTO submitted (name, registration_no, title, teacher,project_type ,file_reference) VALUES (?, ?, ?, ?, ?, ?)");
      $stmt->bind_param("ssssss", $name, $regNo, $title, $teacher, $projectType, $fileName);

      if ($stmt->execute()) {
          // Record inserted successfully
          echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Congratulations!</strong> Record inserted successfully.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>';
      } else {
          // Error handling if the insertion fails
          echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Sorry!</strong>Your insertion failed.
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
}



$name = $_SESSION["name"];
$sqlt = "SELECT name FROM teacher"; // Assuming 'teachers' is the name of your table
$resultt = $conn->query($sqlt);

// Check if the query was successful
if ($resultt) {
    // Check if there are any rows returned
    if (mysqli_num_rows($resultt) > 0) {
        // Create an array to hold the teacher names
        $teachers = array();

        // Loop through the rows of data and add the names to the array
        while ($row = mysqli_fetch_assoc($resultt)) {
            $teachers[] = $row['name'];
        }
    }
}
// Fetching Name and Registration Number from the database
$sql = "SELECT name, registration_no FROM student WHERE name = '$name'";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $studentName = $row['name'];
    $registrationNo = $row['registration_no'];
} else {
    // Handle if no result is found
    $studentName = '';
    $registrationNo = '';
}

$sqlq= "SELECT * from submitted where name='$name' AND checked=0";
$resultq=$conn->query($sqlq);
// Rest of your code...
$sno=0;
=======
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
>>>>>>> c31ce7ba3f969ba68344d18f0e3286603692987e
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<<<<<<< HEAD

=======
>>>>>>> c31ce7ba3f969ba68344d18f0e3286603692987e
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
<<<<<<< HEAD
  <link rel="stylesheet" href="style.css">
=======
>>>>>>> c31ce7ba3f969ba68344d18f0e3286603692987e
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
<<<<<<< HEAD
        <th scope="col">File Type</th>
        <th scope="col">Submitted Date</th>
        <th scope="col">Assigned To</th>
        <th scope="col">Status</th>
=======
        <th scope="col">Submitted Date</th>
        <th scope="col">Assigned To</th>
        <th scope="col">Project</th>
>>>>>>> c31ce7ba3f969ba68344d18f0e3286603692987e
      </tr>
    </thead>
    <tbody>
      <?php
<<<<<<< HEAD
      // Perform a database query to fetch data from the "submitted" table

      // Check if the query was successful
      if ($resultq) {
          // Check if there are any rows returned
          if (mysqli_num_rows($resultq) > 0) {
              // Loop through the rows of data and display them in a table
              while ($row = mysqli_fetch_assoc($resultq)) {
=======


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
>>>>>>> c31ce7ba3f969ba68344d18f0e3286603692987e
                  echo '<tr>';
                  echo '<td>' . ++$sno. '</td>';
                  echo '<td>' . $row['name'] . '</td>';
                  echo '<td>' . $row['registration_no'] . '</td>';
                  echo '<td>' . $row['title'] . '</td>';
<<<<<<< HEAD
                  if($row['project_type'] == 'proposal'){
                    echo '<td>Proposal</td>';
                  }else if($row['project_type'] == 'three_chapters'){
                    echo '<td>Three Chapters</td>';
                  }
                  else
                  {
                    echo '<td>Final Project</td>';
                  }

                  echo '<td>' . $row['submitted_date'] . '</td>';
                  echo '<td>' . $row['teacher'] . '</td>';
                  echo '<td class="btn btn-warning pend">Pending</td>';

                  echo '</tr>';
              }
              echo '</table>';
          } else {
              // No records found
              echo '<div class="alert alert-info" role="alert">No records found.</div>';
            }
          // Free the result set
          mysqli_free_result($resultq);
=======
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
>>>>>>> c31ce7ba3f969ba68344d18f0e3286603692987e
      } else {
          // Error handling if the query fails
          echo 'Error: ' . mysqli_error($conn);
      }
      ?>
<<<<<<< HEAD
=======


>>>>>>> c31ce7ba3f969ba68344d18f0e3286603692987e
    </tbody>
  </table>

  <div class="d-flex justify-content-center">
    <!-- "No Record Found" button remains the same -->
  </div><br>

  <div class="d-flex justify-content-center">
    <button class="btn btn-primary add-new-task" data-toggle="modal" data-target="#addTaskModal">ADD New +</button>
  </div>
<<<<<<< HEAD
  <?php
  // Fetching Name and Registration Number from the database
  $sql = "SELECT supervisor FROM student WHERE name = '$name'";
  $result = $conn->query($sql);

  if ($result && $result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $supervisorName = $row['supervisor'];

  } else {
      // Handle if no result is found
      $superevisorName = '';

  }



?>
  <!-- Modal for adding a new record -->
=======

>>>>>>> c31ce7ba3f969ba68344d18f0e3286603692987e
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
<<<<<<< HEAD
          <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
              <label for="name">Name</label>
              <input type="text" class="form-control" id="name" name="name" value="<?php echo $studentName; ?>"
                readonly>
            </div>
            <div class="form-group">
              <label for="regNo">Registration Number</label>
              <input type="text" class="form-control" id="regNo" name="regNo" value="<?php echo $registrationNo; ?>"
                readonly>
            </div>
            <div class="form-group">
              <label for="supervisor">Supervisor Name</label>
              <input type="text" class="form-control" id="supervisor" name="supervisor"
                value="<?php echo $supervisorName; ?>" readonly>
            </div>

            <div class="form-group">
=======
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
>>>>>>> c31ce7ba3f969ba68344d18f0e3286603692987e
              <label for="title">Title</label>
              <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="form-group">
<<<<<<< HEAD
              <label for="projectType">File Type</label>
              <select class="form-control" id="projectType" name="projectType" required>
                <option value="proposal">Proposal</option>
                <option value="three_chapters">Three Chapters</option>
                <option value="final_project">Final Project</option>
              </select>

=======
              <label for="teacher">Teacher</label>
              <select class="form-control" id="teacher" name="teacher" required>
                <?php
                // Populate the dropdown options with teacher names from the database
                foreach ($teachers as $teacherName) {
                    echo '<option value="' . $teacherName . '">' . $teacherName . '</option>';
                }
                ?>
              </select>
>>>>>>> c31ce7ba3f969ba68344d18f0e3286603692987e
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
<<<<<<< HEAD
  <div class="text-center mt-5">
    <h1>Latest News</h1>
    <?php include 'news_student.php'; ?>
  </div>
=======
>>>>>>> c31ce7ba3f969ba68344d18f0e3286603692987e
</body>

</html>