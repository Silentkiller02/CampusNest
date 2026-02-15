<?php
session_start();
include("../includes/db.php");

// Example: logged-in student ID (replace later with $_SESSION['student_id'])
$student_id = $_SESSION['student_id'] ?? 1; 

// Fetch student’s current mess plan (latest record)
$sql = "SELECT sp.plan_id, sp.status, mp.plan_name, mp.plan_type, mp.category, mp.cost
        FROM mess_payments sp
        JOIN mess_plans mp ON sp.plan_id = mp.id
        WHERE sp.student_id = ?
        ORDER BY sp.id DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$current_plan = $result->fetch_assoc();

// Fetch all plan options for dropdowns (to pass to JS)
$planOptions = [];
$res = $conn->query("SELECT plan_type, category, cost FROM mess_plans");
while ($row = $res->fetch_assoc()) {
    $planOptions[] = $row;
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Mess Management — Student</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/style.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Mess — Student Panel</h2>
    <div>
      <?php if ($current_plan): ?>
        <strong>Current Plan:</strong> <?= htmlspecialchars($current_plan['plan_name']) ?> <br>
        <strong>Cost:</strong> ₹<?= number_format($current_plan['cost'], 2) ?> <br>
      <?php else: ?>
        <span class="text-muted">No plan selected yet</span>
      <?php endif; ?>
    </div>
  </div>

  <div class="card mb-3">
    <div class="card-body">
      <h5>Select Mess Plan</h5>
      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label">Plan Type</label>
          <select id="plan_type" class="form-select">
            <option value="2-time">2-time</option>
            <option value="3-time">3-time</option>
            <option value="4-time">4-time</option>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label">Category</label>
          <select id="category" class="form-select">
            <option value="Veg">Veg</option>
            <option value="Non-Veg">Non-Veg</option>
            <option value="Both">Both</option>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label">Plan Cost</label>
          <div id="plan-cost" class="h5">—</div>
        </div>
      </div>

      <div class="mt-3">
        <button id="pay-btn" class="btn btn-primary">Pay Mess Fees</button>
        <span id="payment-status" class="ms-3"></span>
        <span id="receipt-link" class="ms-3"></span>
      </div>
    </div>
  </div>

  <div id="menu" class="card">
    <div class="card-body">
      <h5>Weekly Menu</h5>
      <div id="menu-content">Loading...</div>
    </div>
  </div>
</div>

<script>
const planOptions = <?php echo json_encode($planOptions); ?>;

async function loadMenu() {
  const plan_type = document.getElementById('plan_type').value;
  const category = document.getElementById('category').value;

  const res = await fetch('mess_menu_ajax.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: new URLSearchParams({ plan_type, category })
  });
  const data = await res.json();

  document.getElementById('menu-content').innerHTML = data.menu_html;
  document.getElementById('plan-cost').textContent = data.cost ? ('₹ ' + data.cost) : '—';
  document.getElementById('payment-status').textContent = data.payment_status ? ('Status: ' + data.payment_status) : '';

  const receiptWrap = document.getElementById('receipt-link');
  receiptWrap.innerHTML = '';
  if (data.payment_status === 'Paid' && data.payment_id) {
    const a = document.createElement('a');
    a.href = 'receipt.php?id=' + encodeURIComponent(data.payment_id);
    a.textContent = 'View/Download Receipt';
    a.className = 'btn btn-sm btn-outline-secondary ms-2';
    receiptWrap.appendChild(a);
  }
}

// pay handler
document.getElementById('pay-btn').addEventListener('click', async function () {
  const plan_type = document.getElementById('plan_type').value;
  const category = document.getElementById('category').value;
  if (!confirm('Proceed to pay for ' + plan_type + ', ' + category + ' plan?')) return;
  this.disabled = true;
  this.textContent = 'Processing...';

  const res = await fetch('pay_mess.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: new URLSearchParams({ plan_type, category })
  });
  const data = await res.json();
  if (data.success) {
    alert('Payment successful!');
    await loadMenu();
  } else {
    alert('Payment failed: ' + (data.message || 'Unknown'));
  }
  this.disabled = false;
  this.textContent = 'Pay Mess Fees';
});

// initial load + re-load when selection changes
document.getElementById('plan_type').addEventListener('change', loadMenu);
document.getElementById('category').addEventListener('change', loadMenu);
loadMenu();
</script>
</body>
</html>
