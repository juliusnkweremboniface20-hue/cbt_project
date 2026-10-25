<?php
session_start();
unset($_SESSION['admin_logged']);
unset($_SESSION['admin_user']);
session_destroy();
header("Location: admin_login.php");
exit();
?>

