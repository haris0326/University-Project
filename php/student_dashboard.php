<?php
session_start(); // Start session if not already started

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    // Redirect user to login page if not logged in
    header("Location: http://localhost/University-Project/stand_ford/html/student_login.html");
    exit(); // Stop script execution
}

// Include database configuration file
include "db_stand_ford.php";
include "header.php";

// Fetch student's information from the database
$user_id = $_SESSION['id'];

$sql = "SELECT * FROM student WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
$row = $result->fetch_assoc();

// Close the first query
$stmt->close();

// Fetch the student's course from the database
$student_course = $row['course'];

// Fetch selected subjects for the student and the teachers assigned to those subjects
$sql_selected_subjects = "SELECT s.subject_name, t.name AS teacher_name, t.email AS teacher_email 
                          FROM subjects s 
                          JOIN student_subjects ss ON s.id = ss.subject_id 
                          LEFT JOIN teacher t ON ss.teacher_id = t.id 
                          WHERE ss.student_id = ?";
$stmt_selected_subjects = $conn->prepare($sql_selected_subjects);
$stmt_selected_subjects->bind_param("i", $user_id);
$stmt_selected_subjects->execute();
$result_selected_subjects = $stmt_selected_subjects->get_result();

$selected_subjects = [];
while ($row_selected_subject = $result_selected_subjects->fetch_assoc()) {
    $selected_subjects[] = $row_selected_subject;
}

// Close database connection
$stmt_selected_subjects->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Include your CSS file here -->
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

        .user-info, .subjects-info {
            background-color: #fff;
            padding: 20px;
            margin-top: 20px;
        }

        .user-info table, .subjects-info table {
            width: 100%;
            border-collapse: collapse;
        }

        .user-info table th, .subjects-info table th,
        .user-info table td, .subjects-info table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        .container h2, .container h3 {
            margin-bottom: 20px;
        }

        .button-container {
            display: flex;
        }

        .logout-button {
            margin-left: 5px;
            font-family: Helvetica, sans-serif;
        }
        .teacher-button{
            margin-left: 5px;
            font-family: Helvetica, sans-serif;
        }

        button {
            padding: 10px 20px;
            cursor: pointer;
            border: none;
            background-color: #4CAF50;
            color: #f9f9f9;
            border-radius: 4px;
            margin-top: 20px;
            margin-bottom: 20px;
            text-align: center;
            display: block;
            text-decoration: none;
            text-transform: uppercase;
            transition: all 0.3s ease;
        }

        button:hover {
            background-color: #7FFFD4;
        }
        a {
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Welcome to <?php echo htmlspecialchars($row['name']); ?>'s Dashboard</h2>
        <div class="user-info">
            <table>
                <tr>
                    <th>Name:</th>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                </tr>
                <tr>
                    <th>Email:</th>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                </tr>
                <tr>
                    <th>Course:</th>
                    <td><?php echo htmlspecialchars($row['course']); ?></td>
                </tr>
                <tr>
                    <th>Address:</th>
                    <td><?php echo htmlspecialchars($row['address']); ?></td>
                </tr>
            </table>
        </div>

        <div class="subjects-info">
            <h3>Selected Subjects and Assigned Teachers:</h3>
            <?php if (!empty($selected_subjects)): ?>
                <ul>
                    <?php foreach ($selected_subjects as $subject): ?>
                        <li>
                            <?php echo htmlspecialchars($subject['subject_name']); ?> 
                            - Teacher: <?php echo htmlspecialchars($subject['teacher_name'] ?? 'Not Assigned'); ?>
                            (<?php echo htmlspecialchars($subject['teacher_email'] ?? ''); ?>)
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No subjects selected yet.</p>
            <?php endif; ?>
        </div>

        <div class="button-container">
            <button><a href="subjects.php">Select Subjects</a></button>
            <button class="teacher-button"><a href="select_teacher.php">Select Teacher</a></button>
            <button class="logout-button"><a href="http://localhost/University-Project/stand_ford/php/student_logout.php">Logout</a></button>
        </div>
    </div>
</body>
</html>
