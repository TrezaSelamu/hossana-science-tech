<?php
session_start();
include('config.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM innovations WHERE id=$id");
    header("Location: manage_innovations.php");
    exit();
}

$result = mysqli_query($conn, "SELECT * FROM innovations");
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Innovations</title>
<style>
body { font-family: Poppins, sans-serif; background: #f9f9f9; }
table {
  border-collapse: collapse;
  width: 90%;
  margin: 30px auto;
  background: white;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}
th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
th { background: #0A1931; color: #FFD700; }
a.btn {
  background: #FFD700;
  padding: 5px 10px;
  color: #0A1931;
  text-decoration: none;
  border-radius: 5px;
}
a.btn:hover { background: #e6c200; }
h2 { text-align: center; margin-top: 20px; }
</style>
</head>
<body>

<h2>Innovation Submissions</h2>
<table>
<tr>
  <th>ID</th>
  <th>Title</th>
  <th>Description</th>
  <th>Impact</th>
  <th>Resources</th>
  <th>Location</th>
  <th>PDF</th>
  <th>Action</th>
</tr>
<?php while($row = mysqli_fetch_assoc($result)): ?>
<tr>
  <td><?php echo $row['id']; ?></td>
  <td><?php echo $row['title']; ?></td>
  <td><?php echo $row['description']; ?></td>
  <td><?php echo $row['impact']; ?></td>
  <td><?php echo $row['resources']; ?></td>
  <td><?php echo $row['location']; ?></td>
  <td><a href="<?php echo $row['pdf_path']; ?>" target="_blank">View PDF</a></td>
  <td>
    <a class="btn" href="edit_innovation.php?id=<?php echo $row['id']; ?>">Edit</a> |
    <a class="btn" href="manage_innovations.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete this record?')">Delete</a>
  </td>
</tr>
<?php endwhile; ?>
</table>
</body>
</html>
