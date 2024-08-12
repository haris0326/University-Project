<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "stand_ford";

// Create connection
$conn = new mysqli("localhost", "root", "", "stand_ford");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>