<?php
include 'connect_db';
<<<<<<< HEAD
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
=======
>>>>>>> c31ce7ba3f969ba68344d18f0e3286603692987e
// Check if the "file" parameter is present in the URL
if (isset($_GET['file'])) {
    $file = $_GET['file'];

    // Specify the folder where uploaded files are stored
    $uploadDir = "upload_files/";

    // Generate the full path to the file
    $filePath = $uploadDir . $file;

    // Check if the file exists
    if (file_exists($filePath)) {
        // Set the appropriate headers for file download
        header("Content-Disposition: attachment; filename=" . $file);
        header("Content-Type: application/octet-stream");
        header("Content-Length: " . filesize($filePath));

        // Output the file content
        readfile($filePath);
        exit;
    } else {
        // File does not exist
        echo "File not found.";
    }
} else {
    // "file" parameter is missing from the URL
    echo "Invalid request.";
}
?>