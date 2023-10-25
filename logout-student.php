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

session_start();
session_unset();
session_destroy();
header('Location: login-student.php');
exit();
?>