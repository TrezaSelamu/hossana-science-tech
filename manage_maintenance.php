<?php
session_start();
include('config.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM maintenance WHERE id=$id");
    header("Location: manage_maintenance.php");
    exit();
}

$result = mysqli_query($conn, "SELECT * FROM maintenance");
?>
<!DOCTYPE html>
<html>
<head>
<title>Manage Maintenance</title>
<style>
body { font-family: Poppins, sans-serif; background: #f9f9f9; }
table { width: 90%; margin: 30px auto; border-collapse: collapse; background: white; }
th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
th { background: #1E2A47; color: #FFD700; }
a.btn { background: #FFD700; color: #1E2A47; padding: 5px 10px; text-decoration: none; border-radius: 5px; }
a.btn:hover { background: #e6c200; }
</style>
</head>
<body>
<h2 style="text-align:center;">Maintenance Reports</h2>
<table>
<tr>
<th>ID</th><th>Title</th><th>Description</th><th>Status</th><th>Date</th><th>Action</th>
</tr>
<?php while($row = mysqli_fetch_assoc($result)): ?>
<tr>
<td><?php echo $row['id']; ?></td>
<td><?php echo $row['title']; ?></td>
<td><?php echo $row['description']; ?></td>
<td><?php echo $row['status']; ?></td>
<td><?php echo $row['created_at']; ?></td>
<td>
<a class="btn" href="edit_maintenance.php?id=<?php echo $row['id']; ?>">Edit</a> |
<a class="btn" href="manage_maintenance.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete this item?')">Delete</a>
</td>
</tr>
<?php endwhile; ?>
</table>
</body>
</html>
