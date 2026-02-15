<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['student_id'])) { 
    echo 'Not logged in'; 
    exit; 
}

$student_id = (int)$_SESSION['student_id'];
$payment_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch payment + student info
$sql = "SELECT p.*, s.name AS student_name, s.email, hp.plan_name 
        FROM hostel_payments p 
        JOIN students s ON s.id = p.student_id 
        JOIN hostel_plans hp ON hp.id = p.plan_id
        WHERE p.id = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $payment_id);
$stmt->execute();
$result = $stmt->get_result();
$pay = $result->fetch_assoc();
$stmt->close();

if (!$pay) { 
    echo 'Payment not found'; 
    exit; 
}
if ($pay['student_id'] != $student_id) { 
    echo 'Permission denied'; 
    exit; 
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Hostel Receipt #<?php echo $pay['id']; ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>body{padding:20px;}</style>
</head>
<body>
<div class="container">
  <div class="card">
    <div class="card-body">
      <h3>Hostel Fee Receipt</h3>
      <p><strong>Receipt ID:</strong> <?php echo $pay['id']; ?></p>
      <p><strong>Txn ID:</strong> <?php echo htmlspecialchars($pay['txn_id']); ?></p>
      <p><strong>Name:</strong> <?php echo htmlspecialchars($pay['student_name']); ?></p>
      <p><strong>Email:</strong> <?php echo htmlspecialchars($pay['email']); ?></p>
      <p><strong>Plan:</strong> <?php echo htmlspecialchars($pay['plan_name']); ?></p>
      <p><strong>Amount:</strong> ₹ <?php echo htmlspecialchars($pay['amount']); ?></p>
      <p><strong>Status:</strong> <?php echo htmlspecialchars($pay['status']); ?></p>
      <p><strong>Month:</strong> <?php echo date("F", mktime(0,0,0,$pay['payment_month'],1)); ?> <?php echo $pay['payment_year']; ?></p>
      <p><strong>Date:</strong> <?php echo htmlspecialchars($pay['created_at']); ?></p>

      <div class="mt-3">
        <button onclick="window.print()" class="btn btn-primary">Print / Save as PDF</button>
        <a href="hostel_student.php" class="btn btn-secondary ms-2">Back</a>
      </div>
    </div>
  </div>
</div>
</body>
</html>
