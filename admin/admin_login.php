<?php
session_start();
include("../includes/db.php");

// already logged in?
if (!empty($_SESSION['admin_id'])) {
  header("Location: admin_dashboard.php");
  exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $username = trim($_POST['username'] ?? "");
  $password = $_POST['password'] ?? "";

  $stmt = $conn->prepare("SELECT id, password, full_name, role FROM admins WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows === 1) {
    $stmt->bind_result($id, $hash, $full_name, $role);
    $stmt->fetch();

    if (password_verify($password, $hash)) {
      session_regenerate_id(true);
      $_SESSION['admin_id']   = $id;
      $_SESSION['admin_user'] = $username;
      $_SESSION['admin_name'] = $full_name;
      $_SESSION['admin_role'] = $role;

      header("Location: admin_dashboard.php");
      exit;
    } else {
      $error = "Incorrect password.";
    }
  } else {
    $error = "No admin found with that username.";
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login - CampusNest</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body{min-height:100vh;display:flex;align-items:center;justify-content:center;background:#0f0028;}
    .card{width:380px;border-radius:14px;box-shadow:0 10px 30px rgba(0,0,0,.35);}
  </style>
</head>
<body>
  <div class="card p-4">
    <h4 class="mb-3 text-center">Admin Login</h4>
    <?php if($error): ?>
      <div class="alert alert-danger py-2"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="post" novalidate>
      <div class="mb-3">
        <label class="form-label">Username</label>
        <input name="username" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input name="password" type="password" class="form-control" required>
      </div>
      <button class="btn btn-primary w-100">Login</button>
    </form>
  </div>
</body>
</html>
