<?php
include "db_stand_ford.php";

$q = isset($_GET['q']) ? $_GET['q'] : '';

if (!empty($q)) {
    $q = $conn->real_escape_string($q);
    $likeTerm = "%" . $q . "%";

    // Fetch student names
    $sql_students = "SELECT name FROM student WHERE name LIKE ?";
    $stmt_students = $conn->prepare($sql_students);
    $stmt_students->bind_param("s", $likeTerm);
    $stmt_students->execute();
    $result_students = $stmt_students->get_result();

    while ($row = $result_students->fetch_assoc()) {
        echo "<div onclick=\"setSearchValue('" . htmlspecialchars($row['name']) . "')\">" . htmlspecialchars($row['name']) . "</div>";
    }

    $stmt_students->close();

    // Fetch teacher names
    $sql_teachers = "SELECT name FROM teacher WHERE name LIKE ?";
    $stmt_teachers = $conn->prepare($sql_teachers);
    $stmt_teachers->bind_param("s", $likeTerm);
    $stmt_teachers->execute();
    $result_teachers = $stmt_teachers->get_result();

    while ($row = $result_teachers->fetch_assoc()) {
        echo "<div onclick=\"setSearchValue('" . htmlspecialchars($row['name']) . "')\">" . htmlspecialchars($row['name']) . "</div>";
    }

    $stmt_teachers->close();

    // Fetch subject names
    // $sql_subjects = "SELECT subject_name FROM subjects WHERE subject_name LIKE ?";
    // $stmt_subjects = $conn->prepare($sql_subjects);
    // $stmt_subjects->bind_param("s", $likeTerm);
    // $stmt_subjects->execute();
    // $result_subjects = $stmt_subjects->get_result();

    // while ($row = $result_subjects->fetch_assoc()) {
    //     echo "<div onclick=\"setSearchValue('" . htmlspecialchars($row['subject_name']) . "')\">" . htmlspecialchars($row['subject_name']) . "</div>";
    // }

    // $stmt_subjects->close();
}

$conn->close();
?>
