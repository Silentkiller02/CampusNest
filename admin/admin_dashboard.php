<?php

include("../admin/admin_auth.php");
include("../includes/db.php");
include("../admin/admin_navbar.php");

// ---------- DASHBOARD DATA ----------
$total_students = $conn->query("SELECT COUNT(*) AS total FROM students")->fetch_assoc()['total'];
$approved_students = $conn->query("SELECT COUNT(*) AS total FROM students WHERE status=1")->fetch_assoc()['total'];
$pending_students = $conn->query("SELECT COUNT(*) AS total FROM students WHERE status=0")->fetch_assoc()['total'];
$grad_students = $conn->query("SELECT COUNT(*) AS total FROM students WHERE degree='UnderGraduation'")->fetch_assoc()['total'];
$postgrad_students = $conn->query("SELECT COUNT(*) AS total FROM students WHERE degree='PostGraduation'")->fetch_assoc()['total'];
$phd_students = $conn->query("SELECT COUNT(*) AS total FROM students WHERE degree='PhD Scholar'")->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard - CampusNest</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: #abc2d8ff; }
    .card { border-radius: 15px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
    .sidebar { min-height: 100vh; background: #0f766e; }
    .sidebar a { color: #fff; display: block; padding: 12px; text-decoration: none; }
    .sidebar a:hover { background: #495057; }
  </style>
</head>
<body>
<div class="container-fluid">
  <div class="row">
    <!-- Sidebar -->
    <div class="col-md-2 sidebar">
      
      <a href="admin_dashboard.php">Dashboard</a>
      <a href="../admin/manage_student.php">Manage Students</a>
      <a href="mess_plans.php">Mess plans</a>
      <a href="mess_menu.php">Mess Menu</a>
      <a href="mess_records.php">Mess records</a>
      <a href="admin_hostel_plans.php">Hostel plan</a>
      <a href="admin_hostel_records.php">Hostel records</a>
      <a href="manage_visitors.php">Visitor Records</a>
      
    </div>

    <!-- Main Content -->
    <div class="col-md-10 p-4">
      <h2 class="center">Admin Dashboard</h2>
      <div class="row g-4 mt-3">
        <div class="col-md-4">
          <div class="card text-center bg-primary text-white">
            <div class="card-body">
              <h3><?php echo $total_students; ?></h3>
              <p>Total Students</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card text-center bg-success text-white">
            <div class="card-body">
              <h3><?php echo $approved_students; ?></h3>
              <p>Approved Students</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card text-center bg-warning text-dark">
            <div class="card-body">
              <h3><?php echo $pending_students; ?></h3>
              <p>Pending Approvals</p>
            </div>
          </div>
        </div>
      </div>

      <div class="row g-4 mt-3">
        <div class="col-md-4">
          <div class="card text-center bg-info text-white">
            <div class="card-body">
              <h3><?php echo $grad_students; ?></h3>
              <p>Graduation Students</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card text-center bg-dark text-white">
            <div class="card-body">
              <h3><?php echo $postgrad_students; ?></h3>
              <p>PostGraduation Students</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card text-center bg-danger text-white">
            <div class="card-body">
              <h3><?php echo $phd_students; ?></h3>
              <p>PhD Scholars</p>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
</body>
</html>
