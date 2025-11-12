<?php
// login.php
session_start();
include('config.php');

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    $user = mysqli_fetch_assoc($result);

    // Simple plain-text password check
    if ($user && $password === $user['password']) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['fullname'] = $user['fullname'];
        $_SESSION['role'] = $user['role']; // assuming role is already set in DB

        // Redirect based on role
        if ($user['role'] == 'admin') {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: submit_innovation.php");
        }
        exit();
    } else {
        $message = "Invalid email or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login</title>
  <style>
    body {
      font-family: "Poppins", sans-serif;
      background: linear-gradient(135deg, #2e2e6f, #1a1a40);
      color: white;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .login-container {
      background: rgba(255, 255, 255, 0.1);
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.4);
      width: 350px;
    }
    h2 { text-align: center; color: #ffcc00; }
    input {
      width: 100%; padding: 10px; margin: 10px 0;
      border: none; border-radius: 5px;
    }
    button {
      width: 100%; padding: 10px;
      background: #ffcc00; border: none; border-radius: 5px;
      font-weight: bold; cursor: pointer;
    }
    button:hover { background: #ffdd33; }
    p { text-align: center; }
    a { color: #ffcc00; }
    .error { color: #ff5555; text-align: center; }
  </style>
</head>
<body>
  <div class="login-container">
    <h2>Login</h2>
    <?php if ($message != "") echo "<p class='error'>$message</p>"; ?>
    <form method="POST" action="">
      <input type="email" name="email" placeholder="Email" required />
      <input type="password" name="password" placeholder="Password" required />
      <button type="submit">Login</button>
    </form>
    <p>Don’t have an account? <a href="register.php">Register</a></p>
  </div>
</body>
</html>
