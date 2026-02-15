<!DOCTYPE html>
<html>
<head>
    <title>Welcome Page</title>
    <link rel="stylesheet" type="text/css" href="includes/welcome.css">
    
    </head>

<body>
    

  <!-- Navigation Bar -->
<nav class="navbar">
    <div class="nav-logo">
        <img src="img/logo.png" alt="Logo">
    </div>
    <ul class="nav-links">
        <li><a href="index.html">Home</a></li>
        <li><a href="about.html">About Us</a></li>
    </ul>
</nav>

<div class="campusnest-box">
    <h1>CampusNest</h1>
    <hr>
    <p>Welcome to your home far from home</p>
</div>
<div class="cards">
 <fieldset>
<div class="card">
    <img src="img/1.jpeg" alt="Admin">
    <h3>Admin Access</h3>
    <p>Say goodbye to paperwork and hello to smart hostel administration..</p>
    <a href="admin/admin_login.php">Login</a>
</div>
</fieldset>
<!-- Modal -->
 <fieldset>
 
<div class="card">
    <img src="img/6.png" alt="Student">
    <h3>Student Access</h3>
    <p>Log in now and take control of your hostel journey—stress-free and student-friendly.</p>
    <a href="student/login.php">Login</a>
    <a href="student/signup.php">Signup</a>
</div>

</fieldset>
</div>
 <!--foter-->

    <?php include("includes/footer.php"); ?>
 

</body>
</html>
