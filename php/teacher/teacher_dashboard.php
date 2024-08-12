<?php
session_start(); // Start session if not already started

// Check if user is logged in
if (!isset($_SESSION['teacher_id'])) {
    // Redirect user to login page if not logged in
    header("Location: http://localhost/University-Project/stand_ford/html/teacher_login.html");
    exit(); // Stop script execution
}

// Include database configuration file
include 'C:\xampp\htdocs\University-Project\stand_ford\php\db_stand_ford.php';
include 'C:\xampp\htdocs\University-Project\stand_ford\php\header.php';

// Fetch teacher's information from the database
$teacher_id = $_SESSION['teacher_id'];

$sql_teacher = "SELECT * FROM teacher WHERE id = ?";
$stmt_teacher = $conn->prepare($sql_teacher);
$stmt_teacher->bind_param("i", $teacher_id);
$stmt_teacher->execute();
$result_teacher = $stmt_teacher->get_result();

if ($result_teacher->num_rows > 0) {
    $teacher = $result_teacher->fetch_assoc();
} else {
    $teacher = null;
}

// Fetch students who have selected this teacher and their subjects
$sql_students = "SELECT s.name AS student_name, s.email AS student_email, sub.subject_name 
                 FROM student_subjects ss
                 JOIN student s ON ss.student_id = s.id
                 JOIN subjects sub ON ss.subject_id = sub.id
                 WHERE ss.teacher_id = ?";
$stmt_students = $conn->prepare($sql_students);
$stmt_students->bind_param("i", $teacher_id);
$stmt_students->execute();
$result_students = $stmt_students->get_result();

$students = [];
while ($row_student = $result_students->fetch_assoc()) {
    $students[] = $row_student;
}

// Close database connections
$stmt_teacher->close();
$stmt_students->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard</title>
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
        .user-info {
            background-color: #fff;
            padding: 20px;
            margin-top: 20px;
        }
        .user-info table {
            width: 100%;
            border-collapse: collapse;
        }
        .user-info table th {
            background-color: #f2f2f2;
            color: #333;
        }
        .user-info table th,
        .user-info table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        .user-info table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .user-info table tr:hover {
            background-color: #f0f0f0;
        }
        a {
            text-decoration: none;
            color: #333;
            transition: all 0.3s ease;
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
    </style>
</head>
<body>
    <div class="container">
        <h2>Welcome to <?php echo isset($teacher['name']) ? htmlspecialchars($teacher['name']) : 'the'; ?> Dashboard</h2>
        <?php if ($teacher): ?>
            <div class="user-info">
                <table>
                    <tr><th>Name:</th><td><?php echo htmlspecialchars($teacher['name']); ?></td></tr>
                    <tr><th>Email:</th><td><?php echo htmlspecialchars($teacher['email']); ?></td></tr>
                    <tr><th>Qualifications:</th><td><?php echo htmlspecialchars($teacher['qualifications']); ?></td></tr>
                    <tr><th>Degree:</th><td><?php echo htmlspecialchars($teacher['degree']); ?></td></tr>
                    <tr><th>Experience:</th><td><?php echo htmlspecialchars($teacher['experience']); ?></td></tr>
                    <tr><th>Address:</th><td><?php echo htmlspecialchars($teacher['address']); ?></td></tr>
                </table>
            </div>
        <?php else: ?>
            <p>No records found.</p>
        <?php endif; ?>

        <?php if (!empty($students)): ?>
            <div class="user-info">
                <h3>Students who have selected you:</h3>
                <table>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Subject</th>
                    </tr>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($student['student_name']); ?></td>
                            <td><?php echo htmlspecialchars($student['student_email']); ?></td>
                            <td><?php echo htmlspecialchars($student['subject_name']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        <?php else: ?>
            <p>No students have selected you yet.</p>
        <?php endif; ?>

        <button><a href="http://localhost/University-Project/stand_ford/php/teacher/teacher_logout.php">Logout</a></button>
    </div>
</body>
</html>
