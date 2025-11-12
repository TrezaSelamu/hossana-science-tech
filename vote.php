<?php
include("config.php"); // Database connection

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo "Invalid request";
    exit;
}

$innovation_id = intval($_GET['id']);

// Check if innovation exists
$check = $conn->query("SELECT votes FROM innovations WHERE id=$innovation_id");
if ($check && $check->num_rows > 0) {
    $row = $check->fetch_assoc();
    $newVotes = intval($row['votes']) + 1;
    $conn->query("UPDATE innovations SET votes=$newVotes WHERE id=$innovation_id");
    echo $newVotes;
} else {
    http_response_code(404);
    echo "Not found";
}
?>
