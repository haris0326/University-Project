<?php
session_start(); // Start the session

// Include database configuration and header
include "db_stand_ford.php";
include "header.php";

// Initialize display data array
$displayData = [];
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

if (!empty($searchTerm)) {
    $searchTerm = $conn->real_escape_string($searchTerm);

    // Search subjects by student name
    $sql_student_search = "SELECT sub.subject_name, sub.course
                           FROM student_subjects ss
                           JOIN student s ON ss.student_id = s.id
                           JOIN subjects sub ON ss.subject_id = sub.id
                           WHERE s.name LIKE ?";
    $stmt_student_search = $conn->prepare($sql_student_search);
    $likeTerm = "%" . $searchTerm . "%";
    $stmt_student_search->bind_param("s", $likeTerm);
    $stmt_student_search->execute();
    $result_student_search = $stmt_student_search->get_result();

    while ($row = $result_student_search->fetch_assoc()) {
        $row['type'] = 'Student';
        $displayData[] = $row;
    }

    // Search subjects by teacher name
    $sql_teacher_search = "SELECT sub.subject_name, sub.course
                           FROM teacher_subjects ts
                           JOIN teacher t ON ts.teacher_id = t.id
                           JOIN subjects sub ON ts.subject_id = sub.id
                           WHERE t.name LIKE ?";
    $stmt_teacher_search = $conn->prepare($sql_teacher_search);
    $stmt_teacher_search->bind_param("s", $likeTerm);
    $stmt_teacher_search->execute();
    $result_teacher_search = $stmt_teacher_search->get_result();

    while ($row = $result_teacher_search->fetch_assoc()) {
        $row['type'] = 'Teacher';
        $displayData[] = $row;
    }
}

$conn->close(); // Close database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Subjects by Name</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f0f0f0;
            padding: 20px;
        }

        h1 {
            margin-bottom: 20px;
        }

        form {
            margin-bottom: 20px;
            position: relative;
        }

        label {
            font-weight: bold;
            margin-right: 10px;
        }

        input[type="text"] {
            padding: 8px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 200px;
        }

        button[type="submit"] {
            padding: 8px 16px;
            font-size: 16px;
            cursor: pointer;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        #suggestions {
            border: 1px solid #ccc;
            border-top: none;
            max-height: 150px;
            overflow-y: auto;
            background-color: white;
            position: absolute;
            margin-left: 144px;
            width: 250px; /* Adjust width to match input field */
            top: 36px; /* Position it just below the input field */
            left: 0; /* Align it to the left of the input field */
            z-index: 1000;
        }

        #suggestions div {
            padding: 8px;
            cursor: pointer;
        }

        #suggestions div:hover {
            background-color: #f2f2f2;
        }

        @media (max-width: 768px) {
            table {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <h1>Search Subjects by Name</h1>

    <form action="search_subjects_by_name.php" method="GET">
        <label for="search">Search by Name:</label>
        <input type="text" id="search" name="search" onkeyup="showSuggestions(this.value)">
        <div id="suggestions"></div>
        <button type="submit">Search</button>
    </form>

    <br>

    <table border="1">
        <thead>
            <tr>
                <th>Subject Name</th>
                <th>Course</th>
                <th>Type</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($displayData as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['subject_name']); ?></td>
                    <td><?php echo htmlspecialchars($item['course']); ?></td>
                    <td><?php echo htmlspecialchars($item['type']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <script>
        function showSuggestions(str) {
            if (str.length == 0) {
                document.getElementById("suggestions").innerHTML = "";
                return;
            }
            const xhttp = new XMLHttpRequest();
            xhttp.onload = function() {
                document.getElementById("suggestions").innerHTML = this.responseText;
            }
            xhttp.open("GET", "get_suggestions.php?q=" + str, true);
            xhttp.send();
        }

        function setSearchValue(value) {
            document.getElementById("search").value = value;
            document.getElementById("suggestions").innerHTML = "";
        }
    </script>
</body>
</html>

