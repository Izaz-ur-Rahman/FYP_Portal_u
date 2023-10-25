<?php

include ('connect_db.php');
session_start();

  // Check if the teacher is authenticated (i.e., if the teacher's name is in the session)
  if (!isset($_SESSION["teacher_name"])) {
      // Redirect to the login page or display an error message
      echo '<div class="alert alert-danger" role="alert">Access denied. Please log in first.</div>';
      // You can also add a link to the login page for the user to log in.
      echo '<p ><a class="btn btn-primary" style="color:white; text-align:center;" href="login-professor.php">Log in</a></p>';
      // Optionally, you can exit the script to prevent further code execution.
      exit();
  }
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $deleteSql = "DELETE FROM teacher WHERE id=$id";
    if ($conn->query($deleteSql) === TRUE) {
        header("Location: teachers_admin.php");
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

$sql = "SELECT * FROM teacher";
$result = $conn->query($sql);
$sno = 1;
?>