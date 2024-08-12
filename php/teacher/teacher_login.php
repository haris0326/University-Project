<?php
include 'teacher_db.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM teacher WHERE email='$email' AND is_approved=1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['password'] == $password) {
            $_SESSION['teacher_id'] = $row['id'];
            $_SESSION['teacher_name'] = $row['name'];
            $_SESSION['teacher_email'] = $row['email'];
            $_SESSION['teacher_qualifications'] = $row['qualifications'];
            header("Location: teacher_dashboard.php");
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No account found with that email or not approved yet.";
    }

    $conn->close();
}
?>