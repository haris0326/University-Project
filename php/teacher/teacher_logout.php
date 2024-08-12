<?php

session_start(); # NOTE THE SESSION START

$_SESSION = array(); 
session_unset();
session_destroy();

echo "Logged Out !";

header("Location: http://localhost/University-Project/stand_ford/html/teacher_login.html");
exit();
?>