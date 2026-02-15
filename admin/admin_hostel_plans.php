<?php
session_start();
include("../includes/db.php");

// Handle Add Plan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_plan'])) {
    $plan_name = $_POST['plan_name'];
    $cost = $_POST['cost'];

    $sql = "INSERT INTO hostel_plans (plan_name, cost) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sd", $plan_name, $cost);
    $stmt->execute();
    $stmt->close();
}

// Fetch Plans
$result = $conn->query("SELECT * FROM hostel_plans");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Manage Hostel Plans</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
  <h2>Hostel Plans</h2>
  <form method="post" class="row g-3 mb-3">
    <div class="col-md-5">
      <input type="text" name="plan_name" class="form-control" placeholder="Plan Name" required>
    </div>
    <div class="col-md-5">
      <input type="number" step="0.01" name="cost" class="form-control" placeholder="Cost" required>
    </div>
    <div class="col-md-2">
      <button type="submit" name="add_plan" class="btn btn-primary w-100">Add</button>
    </div>
  </form>

  <table class="table table-bordered">
    <thead><tr><th>ID</th><th>Plan Name</th><th>Cost</th><th>Action</th></tr></thead>
    <tbody>
      <?php while($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= htmlspecialchars($row['plan_name']) ?></td>
          <td>₹<?= number_format($row['cost'],2) ?></td>
          <td>
            <a href="edit_hostel_plan.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
            <a href="delete_hostel_plan.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this plan?')">Delete</a>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
</body>
</html>
