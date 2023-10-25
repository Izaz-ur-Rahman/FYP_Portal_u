<?php
session_start();
include 'connect_db.php';
include 'header.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Checked</title>

  <!-- Include Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
    integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

<body>

  <?php


// Check if the teacher is authenticated (i.e., if the teacher's name is in the session)
if (!isset($_SESSION["teacher_name"])) {
    // Redirect to the login page or display an error message
    echo '<div class="alert alert-danger" role="alert">Access denied. Please log in first.</div>';
    // You can also add a link to the login page for the user to log in.
    echo '<p><a class="btn btn-primary" style="color:white; text-align:center;" href="login-professor.php">Log in</a></p>';
    // Optionally, you can exit the script to prevent further code execution.
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['hidden_name'])) {
    // Retrieve data from the form
    $name = $_POST['hidden_name'];
    $presentationMarks = $_POST['presentation_marks'];
    $thesisMarks = $_POST['thesis_marks'];
    $projectMarks = $_POST['project_marks'];
    $communicationMarks = $_POST['communication_marks'];

    // Connect to the database (assuming you have a function to handle the connection)
    $conn = connectToDatabase();

    if (!$conn) {
        // Handle the database connection error here (e.g., display an error message)
        echo 'Database connection error.';
        exit();
    }

    // Sanitize user inputs (prevent SQL injection)
    $name = mysqli_real_escape_string($conn, $name);
    $presentationMarks = mysqli_real_escape_string($conn, $presentationMarks);
    $thesisMarks = mysqli_real_escape_string($conn, $thesisMarks);
    $projectMarks = mysqli_real_escape_string($conn, $projectMarks);
    $communicationMarks = mysqli_real_escape_string($conn, $communicationMarks);

    // Prepare and execute the SQL UPDATE query
    $updateQuery = "UPDATE marks SET presentation_marks = '$presentationMarks', thesis_marks = '$thesisMarks', project_marks = '$projectMarks', communication_marks = '$communicationMarks' WHERE name = '$name'";

    if (mysqli_query($conn, $updateQuery)) {
        // The marks have been updated successfully
        // You can redirect back to the page with the table or perform any other actions.
        header("Location: your_table_page.php");
    } else {
        // Handle the SQL update error (e.g., display an error message)
        echo 'Error updating marks: ' . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);
}

?>

  <?php
error_reporting(E_ALL);
ini_set('display_errors', 1);



// Check if the teacher is authenticated (i.e., if the teacher's name is in the session)
if (!isset($_SESSION["teacher_name"])) {
    // Redirect to the login page or display an error message
    echo '<div class="alert alert-danger" role="alert">Access denied. Please log in first.</div>';
    // You can also add a link to the login page for the user to log in.
    echo '<p><a class="btn btn-primary" style="color:white; text-align:center;" href="login-professor.php">Log in</a></p>';
    // Optionally, you can exit the script to prevent further code execution.
    exit();
}

?>
  <?php
$teacherName = $_SESSION["teacher_name"];
$sql = "SELECT s.name, s.title, s.submitted_date, m.presentation_marks, m.thesis_marks, m.project_marks,
        m.communication_marks
        FROM submitted s
        JOIN marks m ON s.name = m.name
        WHERE s.checked = 1 AND s.teacher = '$teacherName'";
$result = $conn->query($sql);
error_reporting(E_ALL);
$sno = 0;
?>
  <table class="table">
    <thead>
      <tr>
        <th scope="col">Sno</th>
        <th scope="col">Name</th>
        <th scope="col">Title</th>
        <th scope="col">Submitted Date</th>
        <th scope="col">Presentation Marks</th>
        <th scope="col">Thesis Marks</th>
        <th scope="col">Project Marks</th>
        <th scope="col">Communication Marks</th>
        <th scope="col">Action</th>
      </tr>
    </thead>
    <tbody>
      <?php

    // Check if the query was successful
    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            // Loop through the rows of data and display them in the table
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                  $_SESSION['student_name'] = $row['name'];
                echo '<td>' . ++$sno . '</td>';
                echo '<td>' . $row['name'] . '</td>';
                echo '<td>' . $row['title'] . '</td>';
                echo '<td>' . $row['submitted_date'] . '</td>';
                echo '<td>' . $row['presentation_marks'] . '</td>';
                echo '<td>' . $row['thesis_marks'] . '</td>';
                echo '<td>' . $row['project_marks'] . '</td>';
                echo '<td>' . $row['communication_marks'] . '</td>';
                echo '<form action="edit_marks.php" method="POST">';
                  echo '<input value="' . $row['name'] . '" hidden>';

                echo '<td><a class="btn btn-warning" onclick = "postData()" href="edit_marks.php">Edit</a></td>';

                echo '</form>';
                echo '</tr>';
            }
        } else {
            // No records found, display the alert here
            echo '<tr><td colspan="9" class="alert alert-info " style="text-align: center;">No records found.</td></tr>';
        }
        // Free the result set
        mysqli_free_result($result);
    } else {
        // Error handling if the query fails
        echo '<tr><td colspan="8" class="alert alert-danger">Error: ' . mysqli_error($conn) . '</td></tr>';
    }
    ?>
    </tbody>
  </table>

  <!-- First, include jQuery -->
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkwXmFcJlSAwiGgFAW/dAiS6JXm"
    crossorigin="anonymous"></script>

  <!-- Then, include Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
    integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

  <!-- Followed by Bootstrap JavaScript -->
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
    integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
    integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
    crossorigin="anonymous"></script>
  <script>
    function postData() {
      var formData = new FormData(document.getElementById("myForm"));

      fetch('process_data.php', {
        method: 'POST',
        body: formData
      })
        .then(response => response.text())
        .then(name => {
          console.log(name);
        })
        .catch(error => console.error('Error:', error));
    }
  </script>
</body>

</html>