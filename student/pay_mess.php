<?php
session_start();
include("../includes/db.php");
header('Content-Type: application/json');

if (!isset($_SESSION['student_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

$student_id = (int)$_SESSION['student_id'];
$plan_type  = isset($_POST['plan_type']) ? (int)$_POST['plan_type'] : 0;
$category   = isset($_POST['category']) ? $_POST['category'] : '';

$month = date('n');   // current month (1–12)
$year  = date('Y');   // current year

// Get plan
$sql = "SELECT id, cost FROM mess_plans WHERE plan_type = ? AND category = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $plan_type, $category);
$stmt->execute();
$result = $stmt->get_result();
$plan = $result->fetch_assoc();
$stmt->close();

if (!$plan) {
    echo json_encode(['success' => false, 'message' => 'Plan not found']);
    exit;
}
$plan_id = $plan['id'];
$amount  = $plan['cost'];

// Check if already paid this month
$sql = "SELECT id FROM mess_payments WHERE student_id = ? AND payment_month = ? AND payment_year = ?";
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
$txn_id = 'TXN' . time() . rand(1000, 9999);
$sql = "INSERT INTO mess_payments 
(student_id, plan_id, plan_type, category, amount, status, txn_id, payment_month, payment_year, created_at) 
VALUES (?, ?, ?, ?, ?, 'Paid', ?, ?, ?, NOW())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiisdsii", $student_id, $plan_id, $plan_type, $category, $amount, $txn_id, $year, $month);
$stmt->execute();
$payment_id = $stmt->insert_id;
$stmt->close();

// Insert/update subscription
$sql = "SELECT id FROM mess_subscriptions WHERE student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$sub = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($sub) {
    $sql = "UPDATE mess_subscriptions SET plan_type = ?, category = ?, subscribed_on = NOW() WHERE student_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isi", $plan_type, $category, $student_id);
    $stmt->execute();
    $stmt->close();
} else {
    $sql = "INSERT INTO mess_subscriptions (student_id, plan_type, category, subscribed_on) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $student_id, $plan_type, $category);
    $stmt->execute();
    $stmt->close();
}

echo json_encode(['success' => true, 'payment_id' => $payment_id]);
