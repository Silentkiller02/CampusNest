<?php
session_start();
include(__DIR__ . '/../includes/db.php');

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_SESSION['student_id'];
    $visitor_name = $_POST['visitor_name'];
    $contact = $_POST['contact'];
    $relation = $_POST['relation'];
    $reason = $_POST['reason'];
    $visit_date = $_POST['visit_date'];

    $stmt = $conn->prepare("INSERT INTO visitor (student_id, visitor_name, contact_number, relation, reason, visit_date) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $student_id, $visitor_name, $contact, $relation, $reason, $visit_date);

    if ($stmt->execute()) {
        $msg = "Visitor entry submitted successfully.";
    } else {
        $msg = "Error submitting visitor entry.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Visitor Form</title>
    <link rel="stylesheet" href="../assets/bootstrap.min.css">
</head>
<body class="container mt-4">
    <h2>Visitor Entry Form</h2>
    <?php if ($msg) echo "<div class='alert alert-info'>$msg</div>"; ?>
    <form method="POST">
        <div class="mb-3">
            <label>Visitor Name:</label>
            <input type="text" name="visitor_name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Contact Number:</label>
            <input type="text" name="contact" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Relation with Student:</label>
            <input type="text" name="relation" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Reason for Visit:</label>
            <textarea name="reason" class="form-control" required></textarea>
        </div>
        <div class="mb-3">
            <label>Visit Date:</label>
            <input type="date" name="visit_date" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit Visitor Entry</button>
    </form>
</body>
</html>
