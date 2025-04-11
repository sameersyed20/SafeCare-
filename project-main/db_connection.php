<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "safecare_hospital";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set character set
$conn->set_charset("utf8mb4");

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
} else {
    echo "Database connection successful!";
}
?>