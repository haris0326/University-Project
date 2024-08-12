<?php
include 'teacher_db.php'; // Include database connection
include 'C:\xampp\htdocs\University-Project\stand_ford\php\header.php';

// Assuming form is submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Fetch form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $qualifications = $_POST['qualifications'];
    $degree = $_POST['degree'];
    $subjects = $_POST['subjects']; // Assuming subjects are passed as an array from the form
    $experience = $_POST['experience'];
    $address = $_POST['address'];
    $password = $_POST['password'];

    // Define is_approved with a default value
    $is_approved = 0;

    // Check if the email already exists in the database
    $checkEmailSql = "SELECT id FROM teacher WHERE email = ?";
    $checkEmailStmt = $conn->prepare($checkEmailSql);
    $checkEmailStmt->bind_param("s", $email);
    $checkEmailStmt->execute();
    $checkEmailResult = $checkEmailStmt->get_result();

    if ($checkEmailResult->num_rows > 0) {
        // If email exists, display an error message
        echo "Error: Email address already exists.";
        exit();
        
    }

    // Prepare SQL statement for inserting teacher information
    $sql = "INSERT INTO teacher (name, email, qualifications, degree, experience, address, password, is_approved)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare and bind parameters for teacher insertion
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssi", $name, $email, $qualifications, $degree, $experience, $address, $password, $is_approved);

    // Execute the statement to insert teacher details
    if ($stmt->execute()) {
        // Get the inserted teacher ID
        $teacher_id = $stmt->insert_id;

        // Insert subjects into teacher_subjects table
        foreach ($subjects as $subject) {
            // Prepare SQL statement to insert into teacher_subjects
            $subjectSql = "INSERT INTO teacher_subjects (teacher_id, subject_id) 
                           SELECT ?, id FROM subjects WHERE subject_name = ?";
            $subjectStmt = $conn->prepare($subjectSql); 
            $subjectStmt->bind_param("is", $teacher_id, $subject);
            
            // Execute subject insertion
            if ($subjectStmt->execute() !== TRUE) {
                echo "Error: " . $subjectSql . "<br>" . $conn->error;
                exit(); // Stop execution if subject insertion fails
            }
        }

        echo "Registration successful.";
    
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statements and connection
    $stmt->close();
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
    <br><br>
    <button class='r-login'><a href="http://localhost/University-Project/stand_ford/html/teacher_login.html">Log in Now</a></button>
</body>
</html>