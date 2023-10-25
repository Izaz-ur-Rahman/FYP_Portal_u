<?php
include 'connect_db.php'; // Include your database connection


// Check if 'name' is not set in the session
if (!isset($_SESSION['name'])) {
    // Redirect to the login page or display an error message
    echo '<div class="alert alert-danger" role="alert">Access denied. Please log in first.</div>';
    // You can also add a link to the login page for the user to log in.
    echo '<div class="col-12 d-flex justify-content-center"><p ><a class="btn btn-primary" style="color:white; text-align:center;" href="login-student.php">Log in</a></p></div>';
    // Optionally, you can exit the script to prevent further code execution.
    exit();
}

$name = $_SESSION["name"];
$query2 = "SELECT * FROM student where name = '$name'";
$result2 = mysqli_query($conn, $query2);
$supervisor = "";

if ($result2) {
    if (mysqli_num_rows($result2) == 1) {
        $row = mysqli_fetch_assoc($result2);
        $supervisor = $row['supervisor'];
    }
}
// Fetch existing news from the database
$newsQuery = "SELECT * FROM news WHERE teacher= '$supervisor'";
$result = mysqli_query($conn, $newsQuery);

// Check if the query was successful
if ($result) {
    // Check if there are any rows returned
    if (mysqli_num_rows($result) > 0) {
        // Loop through the rows of data and display them
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="card mb-3">';
            echo '<div class="card-body">';
            echo '<h5 class="card-title">' . $row['title'] . '</h5>';
            echo '<p class="card-text">' . $row['content'] . '</p>';
            echo '<p class="card-text"><small class="text-muted">' . $row['date'] . '</small></p>';
            echo '</div>';
            echo '</div>';
        }
    } else {
        // No records found
        echo '<div class="alert alert-info" role="alert">No news available.</div>';
    }

    // Free the result set
    mysqli_free_result($result);
} else {
    // Error handling if the query fails
    echo 'Error: ' . mysqli_error($conn);
}

$conn->close();
?>