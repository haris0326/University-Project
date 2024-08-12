<?php
session_start(); // Start the session

// Include the database configuration file
include "db_stand_ford.php";
include "header.php";

// Function to fetch assigned students for a teacher
function getAssignedStudents($teacherName, $conn) {
    $sql_assigned_students = "SELECT s.name AS student_name, s.email AS student_email, s.course, s.address, s.merit_percentage 
                             FROM student_subjects ss
                             JOIN student s ON ss.student_id = s.id
                             JOIN subjects sub ON ss.subject_id = sub.id
                             JOIN teacher t ON ss.teacher_id = t.id
                             WHERE t.name LIKE ?";
    $stmt_assigned_students = $conn->prepare($sql_assigned_students);
    if (!$stmt_assigned_students) {
        die('Error preparing statement: ' . $conn->error);
    }
    $teacherName = "%" . $conn->real_escape_string($teacherName) . "%";
    $stmt_assigned_students->bind_param("s", $teacherName);
    $stmt_assigned_students->execute();
    $result_assigned_students = $stmt_assigned_students->get_result();

    $assigned_students = [];
    while ($row = $result_assigned_students->fetch_assoc()) {
        $assigned_students[] = $row;
    }

    $stmt_assigned_students->close();

    return $assigned_students;
} 


// Display students or assigned students based on search
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = $_GET['search'];

    // If searching by teacher name, get assigned students
    $displayStudents = getAssignedStudents($searchTerm, $conn);

} else {
    
    // Fetch all students
$sql_students = "SELECT id, name, email, course, address, merit_percentage FROM student WHERE is_approved = 1";
$result_students = $conn->query($sql_students);

$displayStudents = [];
while ($row = $result_students->fetch_assoc()) {
    $displayStudents[] = $row;
}
    
}

$conn->close(); // Close the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Information</title>
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
    <h1>Student Information</h1>

    <form action="student_info.php" method="GET">
        <label for="search">Search by Teacher Name:</label>
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
                <th>Course</th>
                <th>Address</th>
                <th>Merit Percentage</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($displayStudents)): ?>
                <tr>
                    <td colspan="5">No students found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($displayStudents as $student): ?>
                    <tr>
                        <td><?php echo isset($student['student_name']) ? $student['student_name'] : $student['name']; ?></td>
                        <td><?php echo isset($student['student_email']) ? $student['student_email'] : $student['email']; ?></td>
                        <td><?php echo $student['course']; ?></td>
                        <td><?php echo $student['address']; ?></td>
                        <td><?php echo $student['merit_percentage']; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>


