<?php
$servername = "localhost";  // usually localhost for XAMPP
$username = "root";         // default XAMPP username
$password = "";             // leave blank unless you set one
$database = "innovation_db"; // replace with your actual database name

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
