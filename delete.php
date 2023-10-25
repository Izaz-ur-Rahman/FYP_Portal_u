<?php
include 'connect_db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Step 1: Retrieve the file name associated with the record.
    $sql = "SELECT file_reference FROM submitted WHERE id='$id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $filename = $row['file_reference'];    // this is used for file reference storing in a variable

        // Step 2: Delete the record from the 'submitted' table.
        $deleteSQL = "DELETE FROM submitted WHERE id='$id'";
        if ($conn->query($deleteSQL) === TRUE) {
            echo "Record deleted successfully from the database";

            // Step 3: Delete the associated file from the folder.
            $folderPath = 'D:\xampp\htdocs\internshipPHP\FYP_Portal_u\upload_files'; // Specify the path to your folder.
            $filePath = $folderPath . $filename;

            if (file_exists($filePath) && unlink($filePath)) {
                echo "File deleted successfully from the folder";
            } else {
                echo "Error deleting file from the folder";
            }

            header("Location: checked_teacher.php");
        } else {
            echo "Error deleting record from the database: " . $conn->error;
        }
    } else {
        echo "Record not found in the database.";
    }

    $conn->close();
}
?>