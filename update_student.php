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

error_reporting(E_ALL);
ini_set('display_errors', 1);
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "FYP_Portal";

// Create a connection
$conn =mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {die("Connection failed: " . $conn->connect_error); }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the values from the form
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $regno = $_POST['regno'];

    // Prepare and execute the SQL update query
    $query = "UPDATE student SET name='$name', email='$email', registration_no='$regno' WHERE id='$id'";
    $result=mysqli_query($conn,$query);
    if ($result) {
        // If the update was successful, redirect to the page where you list students
        header("Location: students_admin.php");
        exit();
    } else {
        // If there was an error with the update, handle it as needed
        echo "Error updating record: " . $conn->error;
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>