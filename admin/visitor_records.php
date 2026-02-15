<?php
session_start();
include(__DIR__ . '/../includes/db.php');

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

$query = "SELECT v.*, s.full_name, s.enrollment_no FROM visitor v 
          JOIN students s ON v.student_id = s.id 
          ORDER BY v.visit_date DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Visitor Records</title>
    <link rel="stylesheet" href="../assets/bootstrap.min.css">
</head>
<body class="container mt-4">
    <h2>All Visitor Records</h2>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Student</th>
                <th>Enrollment No</th>
                <th>Visitor Name</th>
                <th>Contact</th>
                <th>Relation</th>
                <th>Reason</th>
                <th>Visit Date</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['full_name']) ?></td>
                <td><?= htmlspecialchars($row['enrollment_no']) ?></td>
                <td><?= htmlspecialchars($row['visitor_name']) ?></td>
                <td><?= htmlspecialchars($row['contact_number']) ?></td>
                <td><?= htmlspecialchars($row['relation']) ?></td>
                <td><?= htmlspecialchars($row['reason']) ?></td>
                <td><?= htmlspecialchars($row['visit_date']) ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
