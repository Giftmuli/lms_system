<?php
session_start();

// Unset all session variables
unset($_SESSION['auth']);
unset($_SESSION['auth_role']);
unset($_SESSION['auth_user']);

$_SESSION['message'] = "Logged Out Successfully";

// Redirect to the landing page
header("Location: login.php");
exit(0);
?>