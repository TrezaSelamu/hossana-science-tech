<?php
include('config.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<link rel="stylesheet" href="styl.css">
	<title>Admin Dashboard</title>

<style>
	.crud-section {
		margin-top: 40px;
		background: var(--light);
		padding: 25px;
		border-radius: 15px;
		box-shadow: 0 2px 10px rgba(0,0,0,0.1);
		width: 100%;
		overflow-x: auto; /* ✅ allow table to adjust inside container */
		box-sizing: border-box;
	}

	.crud-section h2 {
		color: var(--blue);
		margin-bottom: 20px;
		white-space: normal;
	}

	table {
		width: 100%;
		border-collapse: collapse;
		table-layout: auto; /* ✅ prevent table overflow */
		word-wrap: break-word;
	}

	th, td {
		padding: 10px;
		border-bottom: 1px solid #ccc;
		text-align: left;
		vertical-align: middle;
		word-break: break-word;
	}

	th {
		background: var(--grey);
	}

	.btn {
		background: var(--blue);
		color: white;
		padding: 6px 14px;
		border: none;
		border-radius: 6px;
		cursor: pointer;
		white-space: nowrap;
	}

	.btn:hover {
		background: #2f6fcf;
	}

	.add-btn {
		background: var(--yellow);
		color: black;
		margin-bottom: 10px;
	}

	.modal {
		display: none;
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background: rgba(0,0,0,0.5);
		justify-content: center;
		align-items: center;
		z-index: 3000;
		overflow: hidden;
	}

	.modal-content {
		background: white;
		padding: 30px;
		width: 400px;
		border-radius: 12px;
		box-sizing: border-box;
		max-width: 90%;
	}

	.modal-content h3 {
		margin-bottom: 20px;
	}

	.modal-content input,
	.modal-content textarea {
		width: 100%;
		padding: 10px;
		margin: 8px 0;
		border-radius: 6px;
		border: 1px solid #ccc;
		box-sizing: border-box;
	}

	.close {
		float: right;
		cursor: pointer;
		color: red;
		font-size: 20px;
		font-weight: bold;
	}

	/* ✅ Fix overall layout to fit next to sidebar without scrolling */
	.main-content {
		margin-left: 240px; /* adjust if your sidebar width differs */
		padding: 20px;
		width: calc(100% - 240px);
		overflow-x: hidden;
		box-sizing: border-box;
	}

	@media (max-width: 900px) {
		.main-content {
			margin-left: 0;
			width: 100%;
		}
	}
</style>

</head>
<body>

	<!-- SIDEBAR -->
	<section id="sidebar">
		<a href="#" class="brand">
			<i class='bx bxs-smile'></i>
			<span class="text">AdminHub</span>
		</a>
		<ul class="side-menu top">
			<li class="active"><a href="#"><i class='bx bxs-dashboard'></i><span class="text">Dashboard</span></a></li>
			<li><a href="#"><i class='bx bxs-cog'></i><span class="text">Settings</span></a></li>
		</ul>
		<ul class="side-menu">
			<li><a href="logout.php" class="logout"><i class='bx bxs-log-out-circle'></i><span class="text">Logout</span></a></li>
		</ul>
	</section>

	<!-- CONTENT -->
	<section id="content">
		<nav>
			<i class='bx bx-menu'></i>
			<h3>Admin Dashboard</h3>
		</nav>

		<main>
			<div class="head-title">
				<div class="left">
					<h1>Dashboard Home</h1>
				</div>
			</div>

			<!-- INNOVATION CRUD -->
<!-- INNOVATION CRUD -->
<div class="crud-section">
    <h2>Innovation Management</h2>
    <button class="btn add-btn" onclick="openModal('innovationModal')">+ Add Innovation</button>
    <table>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Impact</th>
            <th>Location</th>
            <th>Submitter Name</th>
            <th>Submitter Email</th>
            <th>PDF</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php
		ob_start();
        // Fetch innovations with submitter info
        $result = mysqli_query($conn, "
            SELECT i.*, u.fullname, u.email
            FROM innovations i
            JOIN users u ON i.user_id = u.id
            ORDER BY i.created_at DESC
        ");

        while($row = mysqli_fetch_assoc($result)) {
            // PDF link (if exists)
            $pdfLink = $row['pdf_path'] ? "<a href='{$row['pdf_path']}' target='_blank'>Download</a>" : "No PDF";

            // Status buttons for pending
            $statusAction = "";
            if($row['status'] == 'pending') {
                $statusAction = "
                    <form method='POST' style='display:inline-block'>
                        <input type='hidden' name='id' value='{$row['id']}'>
                        <button type='submit' name='approve' class='btn' style='background:green'>Approve</button>
                        <button type='submit' name='reject' class='btn' style='background:red'>Reject</button>
                    </form>
                ";
            }

            echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['title']}</td>
                <td>{$row['impact']}</td>
                <td>{$row['location']}</td>
                <td>{$row['fullname']}</td>
                <td>{$row['email']}</td>
                <td>$pdfLink</td>
                <td>{$row['status']}</td>
                <td>
                    <button class='btn' onclick=\"editItem('innovation', {$row['id']})\">Edit</button>
                    <button class='btn' style='background:#555' onclick=\"deleteItem('innovation', {$row['id']})\">Delete</button>
                    $statusAction
                </td>
            </tr>";
        }

        // Handle approve/reject actions
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if(isset($_POST['approve'])) {
                $id = intval($_POST['id']);
                mysqli_query($conn, "UPDATE innovations SET status='approved' WHERE id=$id");
                header("Location: ".$_SERVER['PHP_SELF']);
            }
            if(isset($_POST['reject'])) {
                $id = intval($_POST['id']);
                mysqli_query($conn, "UPDATE innovations SET status='rejected' WHERE id=$id");
                header("Location: ".$_SERVER['PHP_SELF']);
            }
        }
        ?>
    </table>
</div>


			

	<!-- INNOVATION MODAL -->
	<div id="innovationModal" class="modal">
		<div class="modal-content">
			<span class="close" onclick="closeModal('innovationModal')">&times;</span>
			<h3>Add Innovation</h3>
			<form method="POST" action="add_innovation.php" enctype="multipart/form-data">
				<input type="text" name="title" placeholder="Title" required>
				<textarea name="description" placeholder="Description" required></textarea>
				<input type="text" name="impact" placeholder="Impact" required>
				<input type="text" name="location" placeholder="Location">
				<input type="file" name="pdf" accept="application/pdf">
				<button type="submit" class="btn">Save</button>
			</form>
		</div>
	</div>

	

	<script src="script.js"></script>
	<script>
		function openModal(id) {
			document.getElementById(id).style.display = 'flex';
		}
		function closeModal(id) {
			document.getElementById(id).style.display = 'none';
		}
	</script>
</body>
</html>
