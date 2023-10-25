<?php
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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['news_id'])) {
    $newsID = $_POST['news_id'];

    $query = "SELECT * FROM news WHERE id = $newsID";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $news = mysqli_fetch_assoc($result);
        echo json_encode($news);
    } else {
        echo json_encode(['error' => 'News not found']);
    }
} else {
    echo json_encode(['error' => 'Invalid request']);
}

mysqli_close($conn);
?>