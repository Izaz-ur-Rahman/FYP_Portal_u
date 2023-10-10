<?php
include 'connect_db';
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