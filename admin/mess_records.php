<?php
session_start();
include("../includes/db.php");

// ✅ Only admin can access
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

// ✅ Handle status update
if (isset($_POST['update_status'])) {
    $payment_id = (int)$_POST['payment_id'];
    $status = $_POST['status'] === 'Paid' ? 'Paid' : 'Unpaid';

    $stmt = $conn->prepare("UPDATE mess_payments SET status=? WHERE id=?");
    $stmt->bind_param("si", $status, $payment_id);
    $stmt->execute();
}

// ✅ Filters
$filter_month = $_GET['month'] ?? date("m");
$filter_year  = $_GET['year'] ?? date("Y");

// ✅ Fetch records
$sql = "SELECT p.id, p.txn_id, p.amount, p.status, p.payment_month, p.created_at,
               s.full_name AS student_name, s.email,
               mp.plan_name, mp.plan_type, mp.category
        FROM mess_payments p
        JOIN students s ON s.id = p.student_id
        JOIN mess_plans mp ON mp.id = p.plan_id
        WHERE MONTH(p.payment_month) = ? AND YEAR(p.payment_month) = ?
        ORDER BY p.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $filter_month, $filter_year);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin - Mess Records</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4 bg-light">
<div class="container">
  <h2 class="mb-4">Mess Payment Records</h2>

  <!-- Filter Form -->
  <form method="GET" class="row g-3 mb-4">
    <div class="col-md-2">
      <label class="form-label">Month</label>
      <select name="month" class="form-select">
        <?php for($m=1;$m<=12;$m++): ?>
          <option value="<?= $m ?>" <?= ($filter_month==$m)?'selected':'' ?>>
            <?= date("F", mktime(0,0,0,$m,1)) ?>
          </option>
        <?php endfor; ?>
      </select>
    </div>
    <div class="col-md-2">
      <label class="form-label">Year</label>
      <select name="year" class="form-select">
        <?php for($y=date("Y")-2;$y<=date("Y")+1;$y++): ?>
          <option value="<?= $y ?>" <?= ($filter_year==$y)?'selected':'' ?>><?= $y ?></option>
        <?php endfor; ?>
      </select>
    </div>
    <div class="col-md-2 align-self-end">
      <button type="submit" class="btn btn-primary">Filter</button>
    </div>
    <div class="col-md-2 align-self-end">
      <a href="export_mess_records.php?month=<?= $filter_month ?>&year=<?= $filter_year ?>" class="btn btn-success">Download CSV</a>
    </div>
  </form>

  <!-- Records Table -->
  <table class="table table-bordered table-striped shadow-sm">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Txn ID</th>
        <th>Student</th>
        <th>Email</th>
        <th>Plan</th>
        <th>Amount</th>
        <th>Status</th>
        <th>Payment Month</th>
        <th>Created</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($result->num_rows > 0): ?>
      <?php while($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['txn_id']) ?></td>
        <td><?= htmlspecialchars($row['student_name']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
        <td><?= htmlspecialchars($row['plan_name']) ?> (<?= $row['plan_type'] ?> - <?= $row['category'] ?>)</td>
        <td>₹<?= number_format($row['amount'],2) ?></td>
        <td><?= $row['status'] ?></td>
        <td><?= date("F Y", strtotime($row['payment_month'])) ?></td>
        <td><?= $row['created_at'] ?></td>
        <td>
          <form method="POST" class="d-flex">
            <input type="hidden" name="payment_id" value="<?= $row['id'] ?>">
            <select name="status" class="form-select form-select-sm me-2">
              <option value="Paid" <?= $row['status']=='Paid'?'selected':'' ?>>Paid</option>
              <option value="Unpaid" <?= $row['status']=='Unpaid'?'selected':'' ?>>Unpaid</option>
            </select>
            <button type="submit" name="update_status" class="btn btn-sm btn-warning">Update</button>
          </form>
        </td>
      </tr>
      <?php endwhile; ?>
      <?php else: ?>
      <tr><td colspan="10" class="text-center">No records found for this month.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
</body>
</html>
