<?php
session_start(); // Start the session

include "db_stand_ford.php"; // Include your database connection file
include "header.php"; // Include your header file with HTML head content

// Ensure the session variable is set
if (!isset($_SESSION['id'])) {
    // Redirect to login if the session is not set
    header("Location: http://localhost/University-Project/stand_ford/html/student_login.html");
    exit();
}

// Fetch the student's course from the database
$student_id = $_SESSION['id']; // Assuming you have stored student ID in session after login

$query_course = "SELECT course FROM student WHERE id = ?";
$stmt_course = $conn->prepare($query_course);
$stmt_course->bind_param("i", $student_id);
$stmt_course->execute();
$result_course = $stmt_course->get_result();
$row_course = $result_course->fetch_assoc();
$student_course = $row_course['course']; // Fetch the course for the student

// Fetch subjects based on the student's course
$query = "SELECT * FROM subjects WHERE course = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $student_course);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subject Selection</title>
    <!-- Include your CSS file if needed -->
    <link rel="stylesheet" href="path/to/your/css/file.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f7f7f7;
        }

        .container {
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
        }

        h2 {
            margin-bottom: 20px;
        }

        input[type="submit"] {
            margin-top: 20px;
            padding: 10px 20px;
            cursor: pointer;
            border: none;
            background-color: #4CAF50;
            color: #fff;
            border-radius: 4px;
            text-transform: uppercase;
            transition: all 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Select Your Subjects</h2>
        <form action="subject_selections.php" method="post">
        
        <?php
        if ($result->num_rows > 0) {
            // Display subjects as checkboxes
            while ($row = $result->fetch_assoc()) {
                echo '<input type="checkbox" name="subjects[]" value="' . htmlspecialchars($row['id']) . '">';
                echo '<label>' . htmlspecialchars($row['subject_name']) . '</label><br>';
            }
        } else {
            echo '<p>No subjects available for your course.</p>';
        }
        ?>

        <input type="submit" value="Submit Subjects">
        </form>
    </div>

<?php include "footer.php"; // Include your footer file ?>
</body>
</html>

<?php
// Close prepared statement and database connection
$stmt->close();
$stmt_course->close();
$conn->close();
?>
