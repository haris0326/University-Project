<?php
session_start(); // Start the session

// Include database configuration and header
include "db_stand_ford.php";
include "header.php";

// Initialize display data array
$displayData = [];
$defaultView = true; // Flag for default view

// Handle search functionality
if (isset($_GET['search']) && !empty($_GET['search'])) {
    
    $searchTerm = $_GET['search'];

    $searchTerm = $conn->real_escape_string($searchTerm);

    // No matches found, show subjects and associated users
    $sql_search = "SELECT s.name AS name, s.email AS email, 'Student' AS type
                   FROM student_subjects ss
                   JOIN student s ON ss.student_id = s.id
                   JOIN subjects sub ON ss.subject_id = sub.id
                   WHERE sub.subject_name LIKE '%$searchTerm%'
                   
                   UNION
                   
                   SELECT t.name AS name, t.email AS email, 'Teacher' AS type
                   FROM teacher_subjects ts
                   JOIN teacher t ON ts.teacher_id = t.id
                   JOIN subjects sub ON ts.subject_id = sub.id
                   WHERE sub.subject_name LIKE '%$searchTerm%'";

    $result_search = $conn->query($sql_search);

    while ($row = $result_search->fetch_assoc()) {
        $displayData[] = $row;
    }

    $defaultView = false; // Not default view anymore
} else {
    // Default view: show all subjects
    $sql_default = "SELECT id, subject_name, course FROM subjects";
    $result_default = $conn->query($sql_default);

    while ($row = $result_default->fetch_assoc()) {
        $displayData[] = $row;
    }
}

$conn->close(); // Close database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Subjects and Associated Users</title>
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
            margin-left: 11.2%;
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
</head>
<body>
    <h1>Subjects and Associated Users Information</h1>

    <form action="search_by_subject.php" method="GET">
        <label for="search">Search by Subject Name:</label>
        <input type="text" id="search" name="search" onkeyup="showSuggestions(this.value)>
        <div id="suggestions"></div>
        <button type="submit">Search</button>
    </form>

    <br>

    <table border="1">
        <thead>
            <tr>
                <?php if ($defaultView): ?>
                    <th>ID</th>
                    <th>Subject Name</th>
                    <th>Course</th>
                <?php else: ?>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Type</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($displayData as $item): ?>
                <tr>
                    <?php if ($defaultView): ?>
                        <td><?php echo htmlspecialchars($item['id']); ?></td>
                        <td><?php echo htmlspecialchars($item['subject_name']); ?></td>
                        <td><?php echo htmlspecialchars($item['course']); ?></td>
                    <?php else: ?>
                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                        <td><?php echo htmlspecialchars($item['email']); ?></td>
                        <td><?php echo htmlspecialchars($item['type']); ?></td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
