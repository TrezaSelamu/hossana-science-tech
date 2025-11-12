<?php
// admin_innovations.php
session_start();
include('config.php');

// Check if admin
if (!isset($_SESSION['id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Approve/Reject actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if ($_GET['action'] == 'approve') {
        mysqli_query($conn, "UPDATE innovations SET status='approved' WHERE id=$id");
    } elseif ($_GET['action'] == 'reject') {
        mysqli_query($conn, "UPDATE innovations SET status='rejected' WHERE id=$id");
    }
}

// Fetch all innovations with user info
$query = "
SELECT i.id, i.title, i.description, i.impact, i.resources, i.location, i.pdf_path, i.status, i.created_at,
       u.fullname, u.email
FROM innovations i
JOIN users u ON i.user_id = u.id
ORDER BY i.created_at DESC
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin - Innovations</title>
<style>
body { font-family:'Poppins', sans-serif; background:#f4f4f4; color:#222; }
.container { max-width: 1200px; margin:30px auto; background:#fff; padding:20px; border-radius:10px; box-shadow:0 4px 12px rgba(0,0,0,0.1);}
h1 { text-align:center; color:#00416a; margin-bottom:20px; }
table { width:100%; border-collapse:collapse; }
th, td { border:1px solid #ccc; padding:10px; text-align:left; }
th { background:#00416a; color:white; }
a.action-btn { padding:5px 10px; border-radius:5px; color:white; text-decoration:none; margin-right:5px; }
a.approve { background:green; }
a.reject { background:red; }
</style>
</head>
<body>

<div class="container">
    <h1>All Innovation Submissions</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Description</th>
                <th>Impact</th>
                <th>Resources</th>
                <th>Location</th>
                <th>Submitter Name</th>
                <th>Submitter Email</th>
                <th>PDF</th>
                <th>Status</th>
                <th>Submitted At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php
        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_assoc($result)){
                echo "<tr>";
                echo "<td>".$row['id']."</td>";
                echo "<td>".$row['title']."</td>";
                echo "<td>".$row['description']."</td>";
                echo "<td>".$row['impact']."</td>";
                echo "<td>".$row['resources']."</td>";
                echo "<td>".$row['location']."</td>";
                echo "<td>".$row['fullname']."</td>";
                echo "<td>".$row['email']."</td>";
                echo "<td>";
                if($row['pdf_path']){
                    echo "<a href='".$row['pdf_path']."' target='_blank'>Download</a>";
                } else {
                    echo "N/A";
                }
                echo "</td>";
                echo "<td>".$row['status']."</td>";
                echo "<td>".$row['created_at']."</td>";
                echo "<td>";
                if($row['status'] == 'pending'){
                    echo "<a class='action-btn approve' href='?action=approve&id=".$row['id']."'>Approve</a>";
                    echo "<a class='action-btn reject' href='?action=reject&id=".$row['id']."'>Reject</a>";
                }
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='12' style='text-align:center;'>No submissions yet.</td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>

</body>
</html>
