<?php
session_start(); // Session shuru karna

// Check karna agar admin login hai ya nahi
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // Agar admin login nahi hai to login page par redirect karna
    header("Location: http://localhost/University-Project/stand_ford/php/admin_login.php");
    exit(); // Script execution ko yahin rokh dena
}


// Include database configuration file
include "db_stand_ford.php";
include "header.php";

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <title>Admin Home</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            margin: 0;
        }
        .container {
            margin: 0 auto;
            padding: 20px;
            background-color: #f7f7f7;
        }
        h1 {
            text-align: center;
            font-family:Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
            font-size: 40px;
        }

        .button-container {
            text-align: center;
            margin-top: 20px;
        }
        button {
            background-color: #4CAF50;
            border: 1px solid black;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
        }
        button a {
            text-decoration: none;
            color: white;
        }
        button:hover {
            background-color: #45a049;
        }
        .back-button {
            background-color: #555;
        }
        
        .logout-button {
            background-color: #555;
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            margin: 10px 10px;
            cursor: pointer;
            border-radius: 4px;
        }
        .logout-button:hover {
            background-color: red;
        }

    </style>

</head>
<body>

<div class="container">

<h1>Welcome to the Admin Dashboard</h1>
    <!-- Buttons -->
    <div class="button-container">
        <button><a href="student_admin.php">Students Request</a></button>
        <button><a href="http://localhost/University-Project/Stand_ford/php/teacher/teacher_admin.php">Teachers Request</a></button>
        <button class="back-button"><a href="home.php">Back to Home</a></button>
    </div>
    
    <div>
    <button><a href="student_info.php">Students Informations</a></button>
    <button><a href="teacher_info.php">Teachers Informations</a></button><br>
    <button><a href="search_by_subject.php">Subject Assigns info</a></button>
    <button><a href="search_subjects_by_name.php">Student Or Teacer Assigns info</a></button>
    </div>
</div>

<!-- Logout Button -->
<button class="logout-button"><a href="http://localhost/University-Project/stand_ford/php/admin_logout.php">Logout</a></button>

<!-- JavaScript for search functionality -->

</body>
</html>
