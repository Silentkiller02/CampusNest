<?php
session_start();
include("../includes/db.php");

// ✅ Only admin
if (!isset($_SESSION['admin_id'])) {
    exit("Not allowed");
}

$month = $_GET['month'] ?? date("m");
$year  = $_GET['year'] ?? date("Y");

header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=mess_records_{$month}_{$year}.csv");

$output = fopen("php://output", "w");
fputcsv($output, ["ID","Txn ID","Student","Email","Plan","Amount","Status","Payment Month","Created"]);

$sql = "SELECT p.id, p.txn_id, s.name AS student_name, s.email,
               mp.plan_name, mp.plan_type, mp.category,
               p.amount, p.status, p.payment_month, p.created_at
        FROM mess_payments p
        JOIN students s ON s.id = p.student_id
        JOIN mess_plans mp ON mp.id = p.plan_id
        WHERE MONTH(p.payment_month)=? AND YEAR(p.payment_month)=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $month, $year);
$stmt->execute();
$res = $stmt->get_result();

while($row=$res->fetch_assoc()){
    fputcsv($output, [
        $row['id'],
        $row['txn_id'],
        $row['student_name'],
        $row['email'],
        $row['plan_name']." ({$row['plan_type']} - {$row['category']})",
        $row['amount'],
        $row['status'],
        $row['payment_month'],
        $row['created_at']
    ]);
}
fclose($output);
exit;
