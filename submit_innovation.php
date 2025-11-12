<?php

session_start();
include('config.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $impact = mysqli_real_escape_string($conn, $_POST['impact']);
    $resources = mysqli_real_escape_string($conn, $_POST['resources']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);

    // PDF upload
    $filePath = null;
    if(isset($_FILES['pdf']) && $_FILES['pdf']['error'] === 0){
        $allowed = ['pdf'];
        $fileName = $_FILES['pdf']['name'];
        $fileTmp = $_FILES['pdf']['tmp_name'];
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if(in_array($fileExt, $allowed)){
            $newFileName = uniqid() . "." . $fileExt;
            $uploadDir = "uploads/";
            if(!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            $filePath = $uploadDir . $newFileName;
            move_uploaded_file($fileTmp, $filePath);
        } else {
            $message = "Only PDF files are allowed!";
        }
    }

    if ($message == "") {
        $query = "INSERT INTO innovations (user_id, title, description, impact, resources, location, pdf_path)
                  VALUES ('$user_id', '$title', '$description', '$impact', '$resources', '$location', '$filePath')";
        if(mysqli_query($conn, $query)){
            $message = "Innovation submitted successfully!";
        } else {
            $message = "Error: Could not submit innovation.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Submit Innovation</title>
<style>
body { font-family: 'Poppins', sans-serif; background: #f4f4f4; }
.container { max-width: 600px; margin: 50px auto; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
h2 { text-align:center; color: #0A1931; }
input, textarea { width:100%; padding:10px; margin:10px 0; border-radius:5px; border:1px solid #ccc; }
button { width:100%; padding:10px; background:#FFD700; border:none; border-radius:5px; font-weight:bold; cursor:pointer; }
button:hover { background:#e6c200; }
p.message { text-align:center; color: green; }
p.error { text-align:center; color: red; }
</style>
</head>
<body>

<div class="container">
    <h2>Submit Your Innovation</h2>
    <?php if($message != "") echo "<p class='".($message == "Innovation submitted successfully!" ? "message" : "error")."'>$message</p>"; ?>
    <form method="POST" action="" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="Project Title" required>
        <textarea name="description" placeholder="Describe your idea" required></textarea>
        <input type="text" name="impact" placeholder="Potential Impact" required>
        <input type="text" name="resources" placeholder="Required Resources">
        <input type="text" name="location" placeholder="Project Location (City)">
        <input type="file" name="pdf" accept="application/pdf">
        <button type="submit">Submit Innovation</button>
    </form>
</div>

</body>
</html>
