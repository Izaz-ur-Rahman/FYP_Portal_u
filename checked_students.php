<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
  integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

<?php

session_start();

// Check if 'name' is not set in the session
if (!isset($_SESSION['name'])) {
    // Redirect to the login page or display an error message
    echo '<div class="alert alert-danger" role="alert">Access denied. Please log in first.</div>';
    // You can also add a link to the login page for the user to log in.
    echo '<div class="col-12 d-flex justify-content-center"><p ><a class="btn btn-primary" style="color:white; text-align:center;" href="login-student.php">Log in</a></p></div>';
    // Optionally, you can exit the script to prevent further code execution.
    exit();
}

?>

<?php
include 'connect_db.php';
include('header_1.php');
$name=$_SESSION["name"];
$sql = "SELECT * FROM submitted WHERE name='$name' AND checked=1";
$result = $conn->query($sql);
error_reporting(E_ALL);
$sno=0;
?>
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

  <title>Checked</title>
</head>

<body>

  <table class="table">
    <thead>
      <tr>
        <th scope="col">Sno</th>
        <th scope="col">Name</th>
        <th scope="col">Project Type</th>
        <th scope="col">Presentation Marks</th>
        <th scope="col">Communication Marks</th>
        <th scope="col">Thesis Marks</th>
        <th scope="col">Project Marks</th>
      </tr>
    </thead>
    <tbody>
      <?php
        // SQL query to retrieve marks for students with checked=1
        $name = $_SESSION["name"]; // Get the student's name from the session

        // SQL query to retrieve marks for the specific student with checked=1
        $sql = "SELECT s.name, m.presentation_marks, m.communication_marks, m.thesis_marks, m.project_marks, project_type
                FROM submitted s
                INNER JOIN marks m ON s.name = m.name
                WHERE s.checked = 1 AND s.name = '$name'"; // Compare with the session name


$result = $conn->query($sql);

        if ($result) {
          $sno = 0;
          while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<td>' . ++$sno . '</td>';
            echo '<td>' . $row['name'] . '</td>';
            $projectType = ucfirst($row['project_type']);
            if($row['project_type']== 'three_chapters'){
              echo '<td>Three Chapters</td>';
            }
            else if($row['project_type'] == 'final_project'){
              echo '<td>Final Project</td>';
            }
            else if($row['project_type'] == 'proposal'){
              echo '<td>Proposal</td>';
            }

            echo '<td>' . $row['presentation_marks'] . '</td>';
            echo '<td>' . $row['communication_marks'] . '</td>';
            echo '<td>' . $row['thesis_marks'] . '</td>';
            echo '<td>' . $row['project_marks'] . '</td>';
            echo '</tr>';
          }
        } else {
          echo 'Error: ' . mysqli_error($conn);
        }
      ?>
    </tbody>
  </table>
</body>

</html>
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
  integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
  integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
  integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<div class="text-center mt-5">
  <h1>Latest News</h1>
  <?php include 'news_student.php'; ?>
</div>
</body>

</html>