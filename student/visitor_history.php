<?php
session_start();
include(__DIR__ . '/../includes/db.php');

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

$result = $conn->query("SELECT * FROM visitor WHERE student_id = $student_id ORDER BY visit_date DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Visitors</title>
    <link rel="stylesheet" href="../assets/bootstrap.min.css">
</head>
<body class="container mt-4">
    <h2>Your Visitor History</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
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
