<?php
// register.php
session_start();
include('config.php'); // file that connects to your DB

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Confirm password validation
    if ($password !== $confirm_password) {
        $message = "Passwords do not match!";
    } else {
        // Check if email already exists
        $checkEmail = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
        if (mysqli_num_rows($checkEmail) > 0) {
            $message = "Email already exists!";
        } else {
            // Store password as plain text
            $query = "INSERT INTO users (fullname, email, password) VALUES ('$fullname', '$email', '$password')";
            if (mysqli_query($conn, $query)) {
                header("Location: login.php");
                exit();
            } else {
                $message = "Error: Could not register user.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register</title>
  <style>
    body {
      font-family: "Poppins", sans-serif;
      background: linear-gradient(135deg, #1a1a40, #2e2e6f);
      color: white;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .register-container {
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
  <div class="register-container">
    <h2>Create Account</h2>
    <?php if ($message != "") echo "<p class='error'>$message</p>"; ?>
    <form method="POST" action="">
      <input type="text" name="fullname" placeholder="Full Name" required />
      <input type="email" name="email" placeholder="Email" required />
      <input type="password" name="password" placeholder="Password" required />
      <input type="password" name="confirm_password" placeholder="Confirm Password" required />
      <button type="submit">Register</button>
    </form>
    <p>Already have an account? <a href="login.php">Login</a></p>
  </div>
</body>
</html>
