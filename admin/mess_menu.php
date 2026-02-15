<?php
session_start();
include("../includes/db.php");

// ✅ Only admin can access
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

// Fetch all mess plans
$plans = $conn->query("SELECT * FROM mess_plans ORDER BY plan_type, category");

// If form submitted (update menu)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $plan_id = (int)$_POST['plan_id'];

    // Loop through 7 days
    $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
    foreach ($days as $day) {
        $meal1 = $_POST['meal1'][$day] ?? '';
        $meal2 = $_POST['meal2'][$day] ?? '';
        $meal3 = $_POST['meal3'][$day] ?? '';
        $meal4 = $_POST['meal4'][$day] ?? '';

        // Check if entry exists
        $stmt = $conn->prepare("SELECT id FROM mess_menu WHERE plan_id=? AND day_of_week=?");
        $stmt->bind_param("is", $plan_id, $day);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows > 0) {
            // Update existing
            $row = $res->fetch_assoc();
            $upd = $conn->prepare("UPDATE mess_menu SET meal1=?, meal2=?, meal3=?, meal4=? WHERE id=?");
            $upd->bind_param("ssssi", $meal1, $meal2, $meal3, $meal4, $row['id']);
            $upd->execute();
        } else {
            // Insert new
            $ins = $conn->prepare("INSERT INTO mess_menu (plan_id, day_of_week, meal1, meal2, meal3, meal4) VALUES (?,?,?,?,?,?)");
            $ins->bind_param("isssss", $plan_id, $day, $meal1, $meal2, $meal3, $meal4);
            $ins->execute();
        }
    }
    $msg = "Menu updated successfully!";
}

// If plan selected, fetch menu
$selected_plan = $_GET['plan_id'] ?? '';
$menu = [];
if ($selected_plan) {
    $stmt = $conn->prepare("SELECT * FROM mess_menu WHERE plan_id=? ORDER BY FIELD(day_of_week,'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday')");
    $stmt->bind_param("i", $selected_plan);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        $menu[$row['day_of_week']] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin - Mess Menu</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4 bg-light">
<div class="container">
  <h2 class="mb-4">Manage Mess Menu</h2>

  <!-- Select Plan -->
  <form method="GET" class="mb-3">
    <label class="form-label">Select Plan</label>
    <select name="plan_id" class="form-select w-auto d-inline" onchange="this.form.submit()" required>
      <option value="">-- Choose --</option>
      <?php while($row = $plans->fetch_assoc()): ?>
        <option value="<?= $row['id'] ?>" <?= ($selected_plan==$row['id'])?'selected':'' ?>>
          <?= htmlspecialchars($row['plan_name']) ?> (<?= $row['plan_type'] ?> - <?= $row['category'] ?>)
        </option>
      <?php endwhile; ?>
    </select>
  </form>

  <?php if ($selected_plan): ?>
  <?php if (!empty($msg)): ?>
    <div class="alert alert-success"><?= $msg ?></div>
  <?php endif; ?>

  <!-- Menu Form -->
  <form method="POST">
    <input type="hidden" name="plan_id" value="<?= $selected_plan ?>">

    <table class="table table-bordered shadow-sm">
      <thead class="table-dark">
        <tr>
          <th>Day</th>
          <th>Breakfast</th>
          <th>Lunch</th>
          <th>Snacks</th>
          <th>Dinner</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
        foreach ($days as $day):
          $row = $menu[$day] ?? ['meal1'=>'','meal2'=>'','meal3'=>'','meal4'=>''];
        ?>
        <tr>
          <td><strong><?= $day ?></strong></td>
          <td><input type="text" name="meal1[<?= $day ?>]" class="form-control" value="<?= htmlspecialchars($row['meal1']) ?>"></td>
          <td><input type="text" name="meal2[<?= $day ?>]" class="form-control" value="<?= htmlspecialchars($row['meal2']) ?>"></td>
          <td><input type="text" name="meal3[<?= $day ?>]" class="form-control" value="<?= htmlspecialchars($row['meal3']) ?>"></td>
          <td><input type="text" name="meal4[<?= $day ?>]" class="form-control" value="<?= htmlspecialchars($row['meal4']) ?>"></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <button type="submit" class="btn btn-success">Save Menu</button>
  </form>
  <?php endif; ?>
</div>
</body>
</html>
