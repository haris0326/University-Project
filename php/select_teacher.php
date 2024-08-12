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

// Fetch student's selected subjects
$student_id = $_SESSION['id'];
$sql_subjects = "SELECT subject_id FROM student_subjects WHERE student_id = ?";
$stmt_subjects = $conn->prepare($sql_subjects);
$stmt_subjects->bind_param("i", $student_id);
$stmt_subjects->execute();
$result_subjects = $stmt_subjects->get_result();

$subject_ids = [];
while ($row = $result_subjects->fetch_assoc()) {
    $subject_ids[] = $row['subject_id'];
}
$stmt_subjects->close();

// Fetch teachers for the selected subjects
if (!empty($subject_ids)) {
    $placeholders = implode(',', array_fill(0, count($subject_ids), '?'));
    $types = str_repeat('i', count($subject_ids));
    $sql_teachers = "SELECT t.id AS teacher_id, t.name AS teacher_name, t.email AS teacher_email, sub.id AS subject_id, sub.subject_name
                     FROM teacher_subjects ts
                     JOIN teacher t ON ts.teacher_id = t.id
                     JOIN subjects sub ON ts.subject_id = sub.id
                     WHERE ts.subject_id IN ($placeholders) AND t.is_approved = 1";
    
    $stmt_teachers = $conn->prepare($sql_teachers);
    $stmt_teachers->bind_param($types, ...$subject_ids);
    $stmt_teachers->execute();
    $result_teachers = $stmt_teachers->get_result();

    $teachers = [];
    while ($row = $result_teachers->fetch_assoc()) {
        $teachers[] = $row;
    }
    $stmt_teachers->close();
} else {
    $teachers = [];
}


// Handle form submission to select or unselect a teacher for a subject
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $teacher_id = $_POST['teacher_id'];
    $subject_id = $_POST['subject_id'];
    $action = $_POST['action'];

    if ($action == 'select') {
        // Update student_subjects to set the selected teacher
        $sql_update = "UPDATE student_subjects SET teacher_id = ? WHERE student_id = ? AND subject_id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("iii", $teacher_id, $student_id, $subject_id);
    } else if ($action == 'unselect') {
        // Update student_subjects to remove the selected teacher
        $sql_update = "UPDATE student_subjects SET teacher_id = NULL WHERE student_id = ? AND subject_id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("ii", $student_id, $subject_id);
    }

    if ($stmt_update->execute()) {
        echo "<p>Teacher action executed successfully.</p>";
    } else {
        echo "<p>Error: " . $stmt_update->error . "</p>";
    }

    $stmt_update->close();
    $conn->close();
    // Redirect to avoid form resubmission
    header("Location: select_teacher.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Teacher</title>
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
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        button {
            background-color: #4CAF50;
            border: none;
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
        button a:hover {
            text-decoration: none;
            color: white;
        }
        button:hover {
            background-color: #45a049;
        }
        button:active {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Select Your Teacher</h2>
        <?php if (!empty($teachers)): ?>
            <table>
                <tr>
                    <th>Teacher Name</th>
                    <th>Email</th>
                    <th>Subject</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($teachers as $teacher): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($teacher['teacher_name']); ?></td>
                        <td><?php echo htmlspecialchars($teacher['teacher_email']); ?></td>
                        <td><?php echo htmlspecialchars($teacher['subject_name']); ?></td>
                        <td>
                            <form action="select_teacher.php" method="post" style="display:inline;">
                                <input type="hidden" name="teacher_id" value="<?php echo $teacher['teacher_id']; ?>">
                                <input type="hidden" name="subject_id" value="<?php echo $teacher['subject_id']; ?>">
                                <input type="hidden" name="action" value="select">
                                <button type="submit">Select</button>
                            </form>
                            <form action="select_teacher.php" method="post" style="display:inline;">
                                <input type="hidden" name="teacher_id" value="<?php echo $teacher['teacher_id']; ?>">
                                <input type="hidden" name="subject_id" value="<?php echo $teacher['subject_id']; ?>">
                                <input type="hidden" name="action" value="unselect">
                                <button type="submit">Unselect</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No teachers available for your selected subjects.</p>
        <?php endif; ?>
        <button><a href="student_dashboard.php">Back to Dashboard</a></button>
    </div>
</body>
</html>
