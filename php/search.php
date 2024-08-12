<?php
session_start(); // Start the session

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

// Include the database configuration file
include "db_stand_ford.php";

$searchTerm = isset($_GET['searchTerm']) ? $_GET['searchTerm'] : '';

// Function to fetch assigned teachers for a student
function getAssignedTeachers($studentId, $conn) {
    $sql_assigned_teachers = "SELECT t.name AS teacher_name, t.email AS teacher_email, sub.subject_name 
                             FROM student_subjects ss
                             JOIN teacher t ON ss.teacher_id = t.id
                             JOIN subjects sub ON ss.subject_id = sub.id
                             WHERE ss.student_id = ?";
    $stmt_assigned_teachers = $conn->prepare($sql_assigned_teachers);
    $stmt_assigned_teachers->bind_param("i", $studentId);
    $stmt_assigned_teachers->execute();
    $result_assigned_teachers = $stmt_assigned_teachers->get_result();

    $assigned_teachers = [];
    while ($row = $result_assigned_teachers->fetch_assoc()) {
        $assigned_teachers[] = $row;
    }

    $stmt_assigned_teachers->close();

    return $assigned_teachers;
}

// Function to fetch assigned students for a teacher
function getAssignedStudents($teacherId, $conn) {
    $sql_assigned_students = "SELECT s.name AS student_name, s.email AS student_email, sub.subject_name 
                             FROM student_subjects ss
                             JOIN student s ON ss.student_id = s.id
                             JOIN subjects sub ON ss.subject_id = sub.id
                             WHERE ss.teacher_id = ?";
    $stmt_assigned_students = $conn->prepare($sql_assigned_students);
    $stmt_assigned_students->bind_param("i", $teacherId);
    $stmt_assigned_students->execute();
    $result_assigned_students = $stmt_assigned_students->get_result();

    $assigned_students = [];
    while ($row = $result_assigned_students->fetch_assoc()) {
        $assigned_students[] = $row;
    }

    $stmt_assigned_students->close();

    return $assigned_students;
}

// Determine whether to redirect to student_info.php or teacher_info.php based on search context
if ($searchTerm != '') {
    // Search term is not empty, check if it matches a teacher or student
    $likeTerm = "%" . $searchTerm . "%";

    // Check if search term matches a teacher's name
    $sql_teacher_search = "SELECT id FROM teacher WHERE name LIKE ?";
    $stmt_teacher_search = $conn->prepare($sql_teacher_search);
    $stmt_teacher_search->bind_param("s", $likeTerm);
    $stmt_teacher_search->execute();
    $stmt_teacher_search->store_result();

    if ($stmt_teacher_search->num_rows > 0) {
        // Redirect to teacher_info.php with the search term
        header("Location: teacher_info.php?search=$searchTerm");
        exit();
    }

    // Check if search term matches a student's name
    $sql_student_search = "SELECT id FROM student WHERE name LIKE ?";
    $stmt_student_search = $conn->prepare($sql_student_search);
    $stmt_student_search->bind_param("s", $likeTerm);
    $stmt_student_search->execute();
    $stmt_student_search->store_result();

    if ($stmt_student_search->num_rows > 0) {
        // Redirect to student_info.php with the search term
        header("Location: student_info.php?search=$searchTerm");
        exit();
    }

    // If no matches found, redirect to dashboard or handle error as needed
    header("Location: admin_home.php");
    exit();
}
?>
