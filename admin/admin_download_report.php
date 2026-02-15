<?php
session_start(); include("../includes/db.php");
 if (!isset($_SESSION['admin_id'])) { header('Location: admin_login.php'); exit; }
$rows = $pdo->query('SELECT p.*, s.name, s.email FROM mess_payments p JOIN students s ON s.id = p.student_id ORDER BY p.created_at DESC')->fetchAll();

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="mess_payments_report.csv"');
$out = fopen('php://output', 'w');
fputcsv($out, ['ID','Student','Email','Plan','Category','Amount','Status','Txn ID','Date']);
foreach ($rows as $r) {
    fputcsv($out, [$r['id'],$r['name'],$r['email'],$r['plan_type'],$r['category'],$r['amount'],$r['status'],$r['txn_id'],$r['created_at']]);
}
fclose($out);
exit;