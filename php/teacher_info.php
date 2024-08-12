<?php
session_start(); // Start the session

// Include the database configuration file
include "db_stand_ford.php";
include "header.php";

// Function to fetch assigned teachers for a student
function getAssignedTeachers($studentName, $conn) {
    $sql_assigned_teachers = "SELECT t.name  as name, t.email AS email, t.degree AS degree 
                              FROM student_subjects ss
                              JOIN teacher t ON ss.teacher_id = t.id
                              JOIN subjects sub ON ss.subject_id = sub.id
                              JOIN student s ON ss.student_id = s.id
                              WHERE s.name LIKE ?";
    $stmt_assigned_teachers = $conn->prepare($sql_assigned_teachers);
    if (!$stmt_assigned_teachers) {
        die('Error preparing statement: ' . $conn->error);
    }
    $studentName = "%" . $conn->real_escape_string($studentName) . "%";
    $stmt_assigned_teachers->bind_param("s", $studentName);
    $stmt_assigned_teachers->execute();
    $result_assigned_teachers = $stmt_assigned_teachers->get_result();

    $assigned_teachers = [];
    while ($row = $result_assigned_teachers->fetch_assoc()) {
        $assigned_teachers[] = $row;
    }

    $stmt_assigned_teachers->close();

    return $assigned_teachers;
}

// Fetch all teachers
$sql_teachers = "SELECT id, name, email, degree FROM teacher WHERE is_approved = 1";
$result_teachers = $conn->query($sql_teachers);

$teachers = [];
while ($row = $result_teachers->fetch_assoc()) {
    $teachers[] = $row;
}

// Display teachers or assigned teachers based on search
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = $_GET['search'];

    // If searching by student name, get assigned teachers
    $assignedTeachers = getAssignedTeachers($searchTerm, $conn);
    $displayTeachers = $assignedTeachers; // Display assigned teachers
} else {
    $displayTeachers = $teachers; // Display all teachers
}

$conn->close(); // Close the database connection
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Teacher Information</title>
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
    <h1>Teacher Information</h1>

    <form action="teacher_info.php" method="GET">
        <label for="search">Search by Student Name:</label>
        <input type="text" id="search" name="search" onkeyup="showSuggestions(this.value)">
        <div id="suggestions"></div>
        <button type="submit">Search</button>
    </form>

    <br>

    <table border="1">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Degree</th>
                <!-- <th>Profile</th> -->
            </tr>
        </thead>
        <tbody>
            <?php foreach ($displayTeachers as $teacher): ?>

                    <td><?php echo isset($teacher['name']) ? $teacher['name'] : ''; ?></td>
                    <td><?php echo isset($teacher['email']) ? $teacher['email'] : ''; ?></td>
                    <td><?php echo isset($teacher['degree']) ? $teacher['degree'] : ''; ?></td>
                    <!-- <td><button class="profile-button" onclick="window.location.href='see_info.php?type=teacher&id=<?php echo htmlspecialchars($teacher['id']);?>'">View Profile</button></td> -->
                
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
