<?php
include "db_stand_ford.php";
include "header.php";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $matricMarks = $_POST['matricMarks'];
    $intermediateMarks = $_POST['intermediateMarks'];
    $degree = $_POST['degree'];
    $address = $_POST['address'];
    $password = $_POST['password']; // Ensure 'password' is correctly received

    // Check if email already exists
    $sql_check_email = "SELECT * FROM Student WHERE Email = ?";
    $stmt_check_email = $conn->prepare($sql_check_email);   
    $stmt_check_email->bind_param("s", $email);
    $stmt_check_email->execute();
    $result_check_email = $stmt_check_email->get_result();

    if ($result_check_email->num_rows > 0) {
        // Email already exists
        echo "<br> You are already registered. <br><br>";
    } else {
        // Email does not exist, proceed with registration
        if (empty($password)) {
            echo "Error: Password cannot be empty.";
            exit();
        }

        $merit_percentage = (0.3 * $matricMarks) + (0.7 * $intermediateMarks);

        if ($merit_percentage >= 60) {
            $is_approved = 0;
        } else {
            echo "You are not qualified.";
            exit();
        }

        // Inserting into database
        $sql_insert = "INSERT INTO student (name, email, marks_in_matriculation, marks_in_intermediate, course, address, password, merit_percentage, is_approved) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("ssddssidi", $name, $email, $matricMarks, $intermediateMarks, $degree, $address, $password, $merit_percentage, $is_approved);

        if ($stmt_insert->execute()) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $stmt_insert->error;
        }

        $stmt_insert->close();
    }

    $stmt_check_email->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <style>
        body{
            margin: 0;
        }
       .r-login{
        }
       .r-login a{
        text-decoration: none;
        color: black;
        font-size: 20px;
        font-weight: bold;
       }
    
        .r-login{
        background-color: skyblue;
        padding: 10px;
        font-size: 20px;
        font-weight: bold;
        border-radius: 10px;
        border: none;
        color: black;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        }
        .r-login:hover{
        background-color: yellow;
        color: white;
        }
    </style>

</head>
<body>
    <br>
    <button class='r-login'><a href="http://localhost/University-Project/stand_ford/html/student_login.html">Log in Now</a></button>
</body>
</html>