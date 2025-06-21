<?php
$host = "localhost";
$user = "root";
$password = "Root@1234";
$dbname = "taskbuddy_db";

$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
