<?php
session_start();
include("../includes/db.php");
header('Content-Type: application/json');

if (!isset($_SESSION['student_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

$student_id = (int)$_SESSION['student_id'];
$plan_id    = isset($_POST['plan_id']) ? (int)$_POST['plan_id'] : 0;

if (!$plan_id) {
    echo json_encode(['success' => false, 'message' => 'Invalid plan selected']);
    exit;
}

// current billing month/year
$month = date('n'); // 1–12
$year  = date('Y');

// Get plan details
$sql = "SELECT id, cost FROM hostel_plans WHERE id = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $plan_id);
$stmt->execute();
$result = $stmt->get_result();
$plan = $result->fetch_assoc();
$stmt->close();

if (!$plan) {
    echo json_encode(['success' => false, 'message' => 'Plan not found']);
    exit;
}
$amount = $plan['cost'];

// Check if already paid for this month
$sql = "SELECT id FROM hostel_payments 
        WHERE student_id = ? AND payment_month = ? AND payment_year = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $student_id, $month, $year);
$stmt->execute();
$already = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($already) {
    echo json_encode(['success' => false, 'message' => 'You have already paid for this month.']);
    exit;
}

// Insert payment
$txn_id = 'HTXN' . time() . rand(1000, 9999);
$sql = "INSERT INTO hostel_payments 
(student_id, plan_id, amount, status, txn_id, payment_month, payment_year, created_at) 
VALUES (?, ?, ?, 'Paid', ?, ?, ?, NOW())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iidssi", $student_id, $plan_id, $amount, $txn_id, $month, $year);
$stmt->execute();
$payment_id = $stmt->insert_id;
$stmt->close();

echo json_encode(['success' => true, 'payment_id' => $payment_id]);
?>
