<?php
// Database configuration
$db_server = 'localhost';
$db_username = 'root';
$db_password = ''; // Ensure this is an empty string, not a space
$db_name = 'stock';

// Attempt to connect to MySQL database using mysqli
$conn = new mysqli($db_server, $db_username, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("ERROR: Could not connect. " . $conn->connect_error);
}

?>