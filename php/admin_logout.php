<?php
session_start();
session_destroy();
header("Location: http://localhost/University-Project/stand_ford/php/admin_login.php");
exit();
?>