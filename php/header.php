<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="C:\xampp\htdocs\University-Project\stand_ford\css\header.css">
    <title>Stanford University</title>
    <style>
        
    
#wrapper{

    width: 100%;
    /* margin: 50px auto 0; */
    background-color: #fff;
}

#header{
    text-align: center;
    background-color: #007bff;
    padding: 10px;
}

#header h1{
    color: #fff;
    width: 100%;
    font-size: 40px;
    font-style: italic;
    font-weight: 700;
    text-transform: uppercase;
    margin: 0;
}

#menu{
    background-color: #333;
}
#menu ul{
    font-size: 0;
    padding: 0 10px;
    margin: 0;
    list-style: none;
}

#menu ul li{
    display: inline-block;
}
#menu ul li a{
    color: #fff;
    font-size: 16px;
    font-weight: 600;
    padding: 8px 10px;
    display: block;
    text-decoration: none;
    text-transform: uppercase;
    transition: all 0.3s ease;
}

#menu ul li a:hover{
    background-color: rgba(255,255,255,0.2);
}

.admin{
    background-color: #black;
    color: #fff;
    padding: 10px;
    text-transform: uppercase;
    transition: all 0.3s ease;
}
.admin:hover{
    background-color: rgba(255,255,255,0.2);
}
    </style>
</head>
<body>
    
    <div id="wrapper">

    <div id="header">
        <h1>Stanford University</h1>
    </div>

    <div id="menu">

    <ul>

    <li>
    <a href="http://localhost/University-Project/stand_ford/php/home.php">Home</a>
    </li>

    <li>
        <a href="http://localhost/University-Project/stand_ford/php/register_links.php">Registration</a>
    </li>

    <li>
    <a href="http://localhost/University-Project/stand_ford/php/login_links.php">Log In</a>
    </li>

    <li>
        <a href="http://localhost/University-Project/stand_ford/php/student_dashboard.php">Student Dashboard</a>
    </li>

    <li>
        <a href="http://localhost/University-Project/stand_ford/php/teacher/teacher_dashboard.php">Teacher Dashboard</a>
    </li>

    <li class="admin">
        <a href="http://localhost/University-Project/stand_ford/php/admin_home.php">Admin</a>
    </li>

    <li>
        <a href="http://localhost/University-Project/stand_ford/php/about.php">About</a>
    </li>

    </ul>

    </div>


    </div>

</body>
</html>