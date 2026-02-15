<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['student_id'])) {
    die("Not logged in");
}
$student_id = (int)$_SESSION['student_id'];
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Hostel Fees - Student</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
  <h2 class="mb-4">🏠 Hostel Fee Payment</h2>

  <div class="card shadow-sm mb-4">
    <div class="card-body">
      <h5>Select Hostel Plan</h5>
      <select id="plan" class="form-select mb-3">
        <option value="">-- Select Plan --</option>
        <?php
        $res = $conn->query("SELECT * FROM hostel_plans");
        while ($row = $res->fetch_assoc()) {
            echo "<option value='{$row['id']}'>
                    {$row['plan_name']} ({$row['category']} - {$row['type']}) - ₹{$row['cost']}
                  </option>";
        }
        ?>
      </select>
      <button id="payBtn" class="btn btn-primary">Pay Hostel Fees</button>
      <div id="payStatus" class="mt-3"></div>
    </div>
  </div>

  <div class="card shadow-sm">
    <div class="card-body">
      <h5>My Hostel Payments</h5>
      <table class="table table-bordered">
        <thead class="table-light">
          <tr><th>ID</th><th>Plan</th><th>Amount</th><th>Status</th><th>Month</th><th>Receipt</th></tr>
        </thead>
        <tbody>
          <?php
          $sql = "SELECT hp.*, pl.plan_name 
                  FROM hostel_payments hp
                  JOIN hostel_plans pl ON hp.plan_id = pl.id
                  WHERE hp.student_id = ?
                  ORDER BY hp.created_at DESC";
          $stmt = $conn->prepare($sql);
          $stmt->bind_param("i", $student_id);
          $stmt->execute();
          $res = $stmt->get_result();
          while ($row = $res->fetch_assoc()) {
              $monthName = date("F", mktime(0,0,0,$row['payment_month'],10));
              echo "<tr>
                      <td>{$row['id']}</td>
                      <td>{$row['plan_name']}</td>
                      <td>₹{$row['amount']}</td>
                      <td>{$row['status']}</td>
                      <td>{$monthName} {$row['payment_year']}</td>
                      <td>";
              if ($row['status'] == 'Paid') {
                  echo "<a href='receipt_hostel.php?id={$row['id']}' class='btn btn-sm btn-outline-secondary'>Receipt</a>";
              }
              echo "</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
document.getElementById("payBtn").addEventListener("click", async () => {
    const planId = document.getElementById("plan").value;
    if (!planId) { alert("Select a plan first"); return; }
    let res = await fetch("pay_hostel.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "plan_id=" + planId
    });
    let data = await res.json();
    if (data.success) {
        alert("Payment successful!");
        location.reload();
    } else {
        alert("Payment failed: " + data.message);
    }
});
</script>
</body>
</html>
