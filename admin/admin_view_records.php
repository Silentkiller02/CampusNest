<?php
session_start(); include("../includes/db.php"); if (!isset($_SESSION['admin_id'])) { header('Location: admin_login.php'); exit; }
// toggle status
if (isset($_GET['toggle']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $row = $pdo->prepare('SELECT status FROM mess_payments WHERE id = ?')->execute([$id]);
    $r = $pdo->prepare('SELECT * FROM mess_payments WHERE id = ? LIMIT 1'); $r->execute([$id]); $row = $r->fetch();
    if ($row) {
        $new = $row['status'] === 'Paid' ? 'Unpaid' : 'Paid';
        $pdo->prepare('UPDATE mess_payments SET status = ? WHERE id = ?')->execute([$new, $id]);
    }
    header('Location: admin_view_records.php'); exit;
}

$rows = $pdo->query('SELECT p.*, s.name, s.email FROM mess_payments p JOIN students s ON s.id = p.student_id ORDER BY p.created_at DESC')->fetchAll();
?>
<!doctype html>
<html><head><title>Payments</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"></head><body class="p-4">
<div class="container">
  <h3>Student Payments</h3>
  <table class="table table-striped">
    <thead><tr><th>ID</th><th>Student</th><th>Plan</th><th>Category</th><th>Amount</th><th>Status</th><th>Txn</th><th>Date</th><th>Action</th></tr></thead>
    <tbody>
      <?php foreach ($rows as $r): ?>
      <tr>
        <td><?php echo $r['id']; ?></td>
        <td><?php echo htmlspecialchars($r['name']); ?><br><small><?php echo htmlspecialchars($r['email']); ?></small></td>
        <td><?php echo htmlspecialchars($r['plan_type']); ?>-time</td>
        <td><?php echo htmlspecialchars($r['category']); ?></td>
        <td>₹ <?php echo $r['amount']; ?></td>
        <td><?php echo $r['status']; ?></td>
        <td><?php echo htmlspecialchars($r['txn_id']); ?></td>
        <td><?php echo $r['created_at']; ?></td>
        <td><a class="btn btn-sm btn-secondary" href="admin_view_records.php?toggle=1&id=<?php echo $r['id']; ?>">Toggle Status</a></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
</body></html>