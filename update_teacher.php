<?php
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $cnic = $_POST['cnic'];

    $query = "UPDATE teacher SET name='$name', email='$email', cnic='$cnic' WHERE id='$id'";
    $result=  mysqli_query($conn,$query);
    if ($result) {
        // Teacher information updated successfully
        header('Location: teachers_admin.php'); // Redirect to teacher list page
        exit();
    } else {
        // Error handling if the update fails
        echo 'Error updating teacher information: ' . $conn->error;
    }
}

$conn->close();
?>
