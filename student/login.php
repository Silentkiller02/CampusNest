<?php
session_start();
include("../includes/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password, status FROM students WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $hashed_password, $status);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            if ($status == 1) {
                $_SESSION['student_id'] = $id;
                echo "<script>alert('Login successful!'); window.location='dashboard.php';</script>";
            } else {
                echo "<script>alert('Your account is pending approval.');</script>";
            }
        } else {
            echo "<script>alert('Incorrect password.');</script>";
        }
    } else {
        echo "<script>alert('No account found with this username.');</script>";
    }
    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Page</title>
  <style>
    /* Reset */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Courier New', Courier, monospace;
    }

    body {
      
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background: linear-gradient(135deg, #2a0845, #6441A5);
    }

    .container {
      width: 95%;
      max-width: 950px;
      height: 550px;
      background: #1a0033;
      border-radius: 15px;
      box-shadow: 0 0 20px rgba(0,0,0,0.4);
      display: flex;
      flex-direction: column;
      overflow: hidden;
      position: relative;
    }

    /* Navbar */
    .navbar {
      width: 100%;
      padding: 15px 25px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: rgba(0,0,0,0.2);
      position: relative;
    }

    .logo {
      font-family:Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
      font-size: 50px;
      font-weight: bold;
      color: #c7f7c2;
      letter-spacing: 1px;
    }

    .nav-links {
      list-style: none;
      display: flex;
      gap: 20px;
    }

    .nav-links li a {
      text-decoration: none;
      color: #ccc;
      font-size: 14px;
      transition: 0.3s;
    }

    .nav-links li a:hover {
      color: #fff;
    }

    .btn {
      background: #0099ff;
      padding: 8px 18px;
      border-radius: 20px;
      color: white;
      text-decoration: none;
      font-size: 14px;
      transition: 0.3s;
    }

    .btn:hover {
      background: #007acc;
    }

    /* Hamburger */
    .menu-toggle {
      display: none;
    }

    .hamburger {
      display: none;
      flex-direction: column;
      cursor: pointer;
      gap: 4px;
    }

    .hamburger span {
      width: 25px;
      height: 3px;
      background: #fff;
      border-radius: 5px;
    }

    /* Mobile Menu */
    @media (max-width: 768px) {
      .nav-links, .btn {
        display: none;
      }

      .hamburger {
        display: flex;
      }

      .menu-toggle:checked ~ .nav-links {
        display: flex;
        flex-direction: column;
        position: absolute;
        top: 60px;
        right: 20px;
        background: #2a0050;
        padding: 15px;
        border-radius: 10px;
        gap: 15px;
        box-shadow: 0 0 10px rgba(0,0,0,0.5);
      }

      .menu-toggle:checked ~ .btn {
        display: inline-block;
        position: absolute;
        top: 250px;
        right: 20px;
      }
    }

    /* Content */
    .content {
      flex: 1;
      display: flex;
      flex-direction: row;
    }

    /* Left Panel */
    .login-box {
      flex: 1;
      background: rgba(0,0,0,0.3);
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      padding: 40px;
    }

    .login-box .avatar {
      width: 100px;
      height: 100px;
      border: 2px solid #0ff;
      border-radius: 50%;
      display: flex;
      justify-content: center;
      align-items: center;
      margin-bottom: 30px;
      color: #0ff;
      font-size: 40px;
    }

    .login-box input {
      width: 100%;
      padding: 12px 15px;
      margin: 10px 0;
      border: none;
      border-radius: 25px;
      outline: none;
      background: #2d004d;
      color: #fff;
    }

    .login-box input::placeholder {
      color: #aaa;
    }

    .login-box button {
      width: 25%;
  
      justify-content: center;
      padding: 12px;
      border: none;
      border-radius: 25px;
      margin-top: 20px;
      background: #ff007f;
      color: white;
      font-size: 16px;
      cursor: pointer;
      transition: 0.3s;
    }

    .login-box button:hover {
      background: #e60073;
    }

    .login-box .options {
      margin-top: 15px;
      display: flex;
      justify-content: space-between;
      width: 100%;
      font-size: 12px;
      color: #bbb;
    }

    .login-box .options a {
      color: #ff00ff;
      text-decoration: none;
    }

    /* Right Panel */
    .welcome-box {
      flex: 1;
      background: linear-gradient(135deg, #2e0055, #0f0028);
      display: flex;
      justify-content: center;
      align-items: center;
      position: relative;
      overflow: hidden;
    }

    .welcome-box::before {
      content: "";
      position: absolute;
      width: 300px;
      height: 300px;
      background: radial-gradient(circle, #ff00ff, #0000ff);
      border-radius: 50%;
      filter: blur(100px);
      animation: float 6s ease-in-out infinite alternate;
    }

    @keyframes float {
      from { transform: translate(-50px, -50px); }
      to { transform: translate(50px, 50px); }
    }

    .welcome-box h1 {
      text-align: center;
      color: white;
      font-size: 40px;
      z-index: 1;
    }

    @media (max-width: 768px) {
      .content {
        flex-direction: column;
      }
      .login-box, .welcome-box {
        flex: none;
        height: 50%;
      }
    }
    .navbar img{
      height: 50px;
    }
  </style>
</head>
<body>

  <div class="container">
    <!-- Navigation -->
    <div class="navbar">
      <img src="../img/logo.png" alt="Logo">
      <div class="logo">CampusNest</div>

      <!-- Hamburger -->
      <input type="checkbox" id="menu-toggle" class="menu-toggle">
      <label for="menu-toggle" class="hamburger">
        <span></span>
        <span></span>
        <span></span>
      </label>

      <!-- Menu Items -->
      <ul class="nav-links">
        <li><a href="../welcome_page.php">Home</a></li>
        <li><a href="../admin/admin_login.php">Admin</a></li>
        <li><a href="../includes/gallery.php">gallery</a></li>
        <li><a href="../includes/about.php">About</a></li>
        <li><a href="../includes/cantact.php">Contact</a></li>
      </ul>

      <!-- Button -->
      <a href="../student/signup.php" class="btn">SignUp</a>
    </div>

    <!-- Content Area -->
    <div class="content">
      <!-- Left Login Section -->
      <div class="login-box">
        <div class="avatar">👤</div>

        <!-- FORM with POST -->
        <form method="post" >
          <input type="text" name="username" placeholder="Username" required>
          <input type="password" name="password" placeholder="Password" required>
          <button type="submit">LOGIN</button>

          <div class="options">
            <a href="#">Forgot password?</a>
          </div>
        </form>
      </div>

      <!-- Right Welcome Section -->
      <div class="welcome-box">
        <h1>Welcome.
          <br>
        <p style="color: #aaa;"> The home far from the home! </p>
        </h1>
        
      </div>
    </div>
  </div>

<div  class="bg-black text-white text-center py-3 " style="position: fixed; bottom: 0; width: 100%; background-color:#a3a2ffc7; text-align:center;"><h3 >&copy; 2025 CampusNest | All Rights Reserved</h3></div>


</body>

</html>
