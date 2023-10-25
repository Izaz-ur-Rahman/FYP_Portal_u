<?php

include 'connect_db.php';


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

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM student WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // If the deletion was successful, redirect to the page where you list students
        header("Location: students_admin.php");
        exit();
    } else {
        // If there was an error with the deletion, handle it as needed
        echo "Error deleting record: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>