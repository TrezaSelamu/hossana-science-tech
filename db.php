<?php
$host = "localhost";
$user = "root";  // your MySQL username
$pass = "";      // your MySQL password
$dbname = "hossana_db";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
