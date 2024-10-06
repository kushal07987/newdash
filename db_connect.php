<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "newdash";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set the connection to use UTF-8
if (!$conn->set_charset("utf8")) {
    printf("Error loading character set utf8: %s\n", $conn->error);
    exit();
}

// Function to get database connection
function getDBConnection() {
    global $conn;
    return $conn;
}
?>