<?php
include "header.php";
include 'db_stand_ford.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM student WHERE email='$email' AND is_approved=1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['password'] == $password) {
            $_SESSION['id'] = $row['id'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['student_merit_percentage'] = $row['merit_percentage'];
            header("Location: student_dashboard.php");
            exit(); // Make sure to exit after header redirection
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No account found with that email or not approved yet.";
    }

    $conn->close();
}
?>