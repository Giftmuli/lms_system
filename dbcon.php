<?php
$host = "localhost";
$username = "root";
$password = ""; 
$database = "lms_db";

$con = mysqli_connect($host, $username, $password, $database);

// Checking the connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}
?>