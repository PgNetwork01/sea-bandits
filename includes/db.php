<?php
$servername = "localhost";
$username = "seauser";
$password = "StrongPassword123!";
$dbname = "sea_auth";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
