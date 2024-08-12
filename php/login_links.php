<?php include "header.php";
include "footer.php"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login Links</title>

    <style>

        .student{
            text-decoration: none;      
            background-color: skyblue; 
            font-size: 20px;
            padding: 10px;
            font-family: Monospace; 
            font-weight: 5px;
            display: block;
        }
        a {
            text-decoration: none;
        }

        button.student:hover {
            background-color: greenyellow;
        }

        .teacher{
            text-decoration: none;      
            background-color: orange; 
            font-size: 20px;
            padding: 10px;
            font-family: Monospace; 
            display: block;
        }

        button.teacher:hover {
            background-color: aqua;
        }

        </style>

</head>
<body>
        
    <br>

    <center>
    <button class='student'><a href='http://localhost/University-Project/stand_ford/html/student_login.html'>Student login</a></button><br><br>

    <button class='teacher'><a href='http://localhost/University-Project/stand_ford/html/teacher_login.html'>Teacher login</a></button>
    </center>
</body>
</html>