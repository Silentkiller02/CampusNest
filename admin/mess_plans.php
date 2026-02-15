<?php
session_start();
include("../includes/db.php");

// ✅ Only admin can access
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

// ✅ Add/Edit Plan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id        = $_POST['id'] ?? 0;
    $plan_name = $_POST['plan_name'] ?? '';
    $plan_type = $_POST['plan_type'] ?? '';
    $category  = $_POST['category'] ?? '';
    $cost      = $_POST['cost'] ?? 0;

    if ($id) {
        // Update existing plan
        $stmt = $conn->prepare("UPDATE mess_plans SET plan_name=?, plan_type=?, category=?, cost=? WHERE id=?");
        $stmt->bind_param("sssdi", $plan_name, $plan_type, $category, $cost, $id);
        $stmt->execute();
    } else {
        // Insert new plan
        $stmt = $conn->prepare("INSERT INTO mess_plans (plan_name, plan_type, category, cost) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssd", $plan_name, $plan_type, $category, $cost);
        $stmt->execute();
    }
    header("Location: mess_plans.php");
    exit;
}

// ✅ Fetch all plans
$result = $conn->query("SELECT * FROM mess_plans ORDER BY plan_type, category");

// ✅ If editing, fetch plan details
$edit_plan = ['id'=>'','plan_name'=>'','plan_type'=>'','category'=>'','cost'=>''];
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $res = $conn->query("SELECT * FROM mess_plans WHERE id=$id");
    if ($res->num_rows > 0) {
        $edit_plan = $res->fetch_assoc();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin - Mess Plans</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4 bg-light">

<div class="container">
  <h2 class="mb-4">Mess Plans</h2>

  <!-- Plans Table -->
  <table class="table table-bordered table-striped shadow-sm">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Plan Name</th>
        <th>Plan Type</th>
        <th>Category</th>
        <th>Cost (₹)</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php while($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['plan_name']) ?></td>
        <td><?= htmlspecialchars($row['plan_type']) ?></td>
        <td><?= htmlspecialchars($row['category']) ?></td>
        <td><?= number_format($row['cost'], 2) ?></td>
        <td>
          <a href="mess_plans.php?edit=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <!-- Add/Edit Form -->
  <div class="card mt-4">
    <div class="card-body">
      <h4><?= $edit_plan['id'] ? "Edit Plan" : "Add New Plan" ?></h4>
      <form method="POST" class="row g-3">
        <input type="hidden" name="id" value="<?= $edit_plan['id'] ?>">
        <div class="col-md-3">
          <input type="text" name="plan_name" class="form-control" placeholder="Plan Name" required value="<?= htmlspecialchars($edit_plan['plan_name']) ?>">
        </div>
        <div class="col-md-2">
          <select name="plan_type" class="form-control" required>
            <option value="2-time" <?= $edit_plan['plan_type']=='2-time'?'selected':'' ?>>2-time</option>
            <option value="3-time" <?= $edit_plan['plan_type']=='3-time'?'selected':'' ?>>3-time</option>
            <option value="4-time" <?= $edit_plan['plan_type']=='4-time'?'selected':'' ?>>4-time</option>
          </select>
        </div>
        <div class="col-md-2">
          <select name="category" class="form-control" required>
            <option value="Veg" <?= $edit_plan['category']=='Veg'?'selected':'' ?>>Veg</option>
            <option value="Non-Veg" <?= $edit_plan['category']=='Non-Veg'?'selected':'' ?>>Non-Veg</option>
            <option value="Both" <?= $edit_plan['category']=='Both'?'selected':'' ?>>Both</option>
          </select>
        </div>
        <div class="col-md-2">
          <input type="number" step="0.01" name="cost" class="form-control" placeholder="Cost" required value="<?= $edit_plan['cost'] ?>">
        </div>
        <div class="col-md-2">
          <button type="submit" class="btn btn-success"><?= $edit_plan['id'] ? "Update" : "Add" ?></button>
        </div>
      </form>
    </div>
  </div>
</div>

</body>
</html>
