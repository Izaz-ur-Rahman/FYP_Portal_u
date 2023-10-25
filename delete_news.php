<?php
session_start();
include ('connect_db.php');

if (isset($_GET['news_id'])) {
    $newsID = intval($_GET['news_id']); // Cast to integer to ensure it's a number

    // Prepare a DELETE statement using a prepared statement
    $deleteNewsQuery = $conn->prepare("DELETE FROM news WHERE id = ?");
    $deleteNewsQuery->bind_param("i", $newsID); // "i" for integer
    $deleteNewsQuery->execute();

    if ($deleteNewsQuery->affected_rows > 0) {
        // Redirect back to the page after deletion
        header("Location: {$_SERVER['HTTP_REFERER']}");
        exit();
    } else {
        echo 'Error: ' . $conn->error;
    }

    $deleteNewsQuery->close();
    $conn->close();
} else {
    echo 'Invalid request';
}

?>