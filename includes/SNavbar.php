




           <!--navbar-->

<?php
if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']==true){
 $loggedin = true;
}
else{
 $loggedin = false;
}
echo '<nav class="navbar navbar-expand-lg navbar-dark  " style="background:#000000;">

  <nav class="navbar navbar-expand-lg navbar-dark" style="background:#000000;">
    <a class="navbar-brand" href="#">
      <img src="../img/logo.png" alt="Logo" width="80px" height="80px">
    </a>
  </nav>
 <a class="navbar-brand" href="/CampusNest/student/dashboard.php">CampusNest</a>
 
 
 
 <li class="nav-item active">
 <a class="nav-link btn btn-outline-primary mt-3 mr-3 mb-3 btn-neon-green" href="/CampusNest/student/dashboard.php">Home <span class="sr-only">(current)</span></a>
 </li>';
 if(!$loggedin){
 echo '<li class="nav-item">
 <a class="nav-link btn btn-outline-primary mt-3 mr-3 mb-3 btn-neon-green " style="color:"rgb(255 255 255);" href="/CampusNest/student/login.php">Login</a>
 </li>
 <li class="nav-item">
 <a class="nav-link btn btn-outline-primary mt-3 mr-3 mb-3 btn-neon-green" href="/CampusNest/student/signup.php">SignUp</a>
 </li>';
 }
 if($loggedin){
 echo '<li class="nav-item">
 <a class="nav-link btn btn-outline-primary mt-3 mr-3 mb-3 btn-neon-green" href="/CampusNest/logout.php">Logout</a>
 </li>';
 }
 echo'</ul>

 <div class="collapse navbar-collapse" id="About">

<li class="nav-item active">
<a class="nav-link btn btn-outline-primary mt-3 mb-3  mr-3 btn-neon-green" href="../includes/about.php"> About <span class="sr-only">(current)</span></a>
</li></div>

 </div>
</nav>';
?>