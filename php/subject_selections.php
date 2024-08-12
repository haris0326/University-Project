<?php
include "db_stand_ford.php"; // Include your database connection file
session_start(); // Start the session

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if at least 5 subjects are selected
    if (isset($_POST['subjects']) && count($_POST['subjects']) >= 5) {
        $selectedSubjects = $_POST['subjects'];
        $student_id = $_SESSION['id']; // Assuming student ID is stored in session

        foreach ($selectedSubjects as $subject_id) {
            // Insert selected subjects into student_subjects table
            $query = "INSERT INTO student_subjects (student_id, subject_id) VALUES (?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ii", $student_id, $subject_id);
            $stmt->execute();
        }

        // Close statement
        $stmt->close();

        // Redirect to the student dashboard or another page
        header("Location: student_dashboard.php");
        exit();
    } else {
        echo "Please select at least 5 subjects.";
    }
} else {
    echo "Invalid form submission.";
}

// Close database connection
$conn->close();
?>
