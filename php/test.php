<?php
// Database connection

include "db_stand_ford.php";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to retrieve students related to a specific teacher
$desired_teacher_name = "desired_teacher_name";
$sql = "SELECT s.name, s.email, s.course
        FROM student s
        JOIN student_subjects ss ON s.id = ss.student_id
        JOIN teacher t ON ss.teacher_id = t.id
        WHERE t.name = '$desired_teacher_name'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Display data in a table
    echo "<table border='1'>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Course</th>
            </tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>".$row['name']."</td>
                <td>".$row['email']."</td>
                <td>".$row['course']."</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "No results found";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST">
<div class="search-bar">
            <div class="icon">
                <img src="search-icon.png" alt="Search Icon">
            </div>
            <center>
                <input id="myInput" type="text" placeholder="Search..">
            </center>
        </div>
    </form>

    <?php if ($result_teachers->num_rows > 0): ?>
                <table>
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                    </tr>
                <?php // endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No approved students found.</p>
        <?php endif; ?>
    </div>

</body>
</html>