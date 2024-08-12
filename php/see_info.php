<?php
// session_start(); // Start the session

// // Check if the admin is logged in
// if (!isset($_SESSION['admin_id'])) {
//     // Redirect to login page if not logged in
//     header("Location: http://localhost/University-Project/stand_ford/html/admin_login.html");
//     exit();
// }

// Include database configuration file
include "db_stand_ford.php";
include "header.php";

// Initialize variables
$profileType = isset($_GET['type']) ? $_GET['type'] : '';
$profileId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($profileType == 'student' && $profileId > 0) {
    // Fetch student details
    $sql_student = "SELECT * FROM student WHERE id = ?";
    $stmt_student = $conn->prepare($sql_student);
    $stmt_student->bind_param("i", $profileId);
    $stmt_student->execute();
    $result_student = $stmt_student->get_result();
    $student = $result_student->fetch_assoc();

    // Fetch student subjects and assigned teachers
    $sql_student_subjects = "SELECT sub.subject_name, t.name AS teacher_name 
                             FROM student_subjects ss
                             JOIN subjects sub ON ss.subject_id = sub.id
                             LEFT JOIN teacher t ON ss.teacher_id = t.id
                             WHERE ss.student_id = ?";
    $stmt_student_subjects = $conn->prepare($sql_student_subjects);
    $stmt_student_subjects->bind_param("i", $profileId);
    $stmt_student_subjects->execute();
    $result_student_subjects = $stmt_student_subjects->get_result();
    $student_subjects = [];
    while ($row = $result_student_subjects->fetch_assoc()) {
        $student_subjects[] = $row;
    }
    $stmt_student_subjects->close();
    $stmt_student->close();
} elseif ($profileType == 'teacher' && $profileId > 0) {
    // Fetch teacher details
    $sql_teacher = "SELECT * FROM teacher WHERE id = ?";
    $stmt_teacher = $conn->prepare($sql_teacher);
    $stmt_teacher->bind_param("i", $profileId);
    $stmt_teacher->execute();
    $result_teacher = $stmt_teacher->get_result();
    $teacher = $result_teacher->fetch_assoc();

    // Fetch students assigned to the teacher
    $sql_teacher_students = "SELECT s.name AS student_name, sub.subject_name 
                            FROM student_subjects ss
                            JOIN student s ON ss.student_id = s.id
                            JOIN subjects sub ON ss.subject_id = sub.id
                            WHERE ss.teacher_id = ?";
    $stmt_teacher_students = $conn->prepare($sql_teacher_students);
    $stmt_teacher_students->bind_param("i", $profileId);
    $stmt_teacher_students->execute();
    $result_teacher_students = $stmt_teacher_students->get_result();
    $teacher_students = [];
    while ($row = $result_teacher_students->fetch_assoc()) {
        $teacher_students[] = $row;
    }
    $stmt_teacher_students->close();
    $stmt_teacher->close();
} else {
    echo "Invalid profile type or ID.";
    $conn->close();
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Info</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: #f7f7f7;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            margin-top: 0;
            color: #333;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #333;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .button-container {
            text-align: center;
            margin-top: 20px;
        }
        button {
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 4px;
        }
        button a {
            color: white;
            text-decoration: none;
        }
        button:hover {
            background-color: #45a049;
        }
        .back-button {
            background-color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($profileType == 'student'): ?>
            <h2>Student Profile: <?php echo htmlspecialchars($student['name']); ?></h2>
            <table>
                <tr>
                    <th>Name</th>
                    <td><?php echo htmlspecialchars($student['name']); ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?php echo htmlspecialchars($student['email']); ?></td>
                </tr>
                <tr>
                    <th>Course</th>
                    <td><?php echo htmlspecialchars($student['course']); ?></td>
                </tr>
                <tr>
                    <th>Address</th>
                    <td><?php echo htmlspecialchars($student['address']); ?></td>
                </tr>
                <tr>
                    <th>Merit Percentage</th>
                    <td><?php echo htmlspecialchars($student['merit_percentage']); ?></td>
                </tr>
            </table>

            <h3>Selected Subjects and Assigned Teachers</h3>
            <table>
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Teacher</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($student_subjects as $subject): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($subject['subject_name']); ?></td>
                            <td><?php echo htmlspecialchars($subject['teacher_name'] ?? 'Not Assigned'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php elseif ($profileType == 'teacher'): ?>
            <h2>Teacher Profile: <?php echo htmlspecialchars($teacher['name']); ?></h2>
            <table>
                <tr>
                    <th>Name</th>
                    <td><?php echo htmlspecialchars($teacher['name']); ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?php echo htmlspecialchars($teacher['email']); ?></td>
                </tr>
                <tr>
                    <th>Degree</th>
                    <td><?php echo htmlspecialchars($teacher['degree']); ?></td>
                </tr>
            </table>

            <h3>Assigned Students</h3>
            <table>
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Subject</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($teacher_students as $student): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($student['student_name']); ?></td>
                            <td><?php echo htmlspecialchars($student['subject_name']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <div class="button-container">
            <button class="back-button"><a href="admin_home.php">Back to Dashboard</a></button>
        </div>
    </div>
</body>
</html>
