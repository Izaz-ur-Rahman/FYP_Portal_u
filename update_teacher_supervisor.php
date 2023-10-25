<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'connect_db.php';
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

    $teacher_id = $_GET["id"];

    // Assuming you have a column named 'supervisor' in your 'teacher' table
    $sql = "UPDATE teacher SET supervisor = 1 WHERE id = $teacher_id";

    if ($conn->query($sql) === TRUE) {
        header('teacher_admin.php');
    } else {
        echo "Error updating supervisor status: " . $conn->error;
    }

    $conn->close();
?>