<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "FYP_Portal";

// Create a connection
$conn =mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {die("Connection failed: " . $conn->connect_error); } ?>
