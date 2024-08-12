<?php
session_start();
session_destroy();
header("Location: http://localhost/University-Project/stand_ford/html/student_login.html");
exit();
?>