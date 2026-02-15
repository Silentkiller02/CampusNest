<?php
session_start();
include("../includes/db.php");

// Handle room allocation update by admin
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_room'])) {
    $student_id = (int)$_POST['student_id'];
    $room_id    = (int)$_POST['room_id'];

    // Remove old allocation if any
    $conn->query("DELETE FROM room_allocations WHERE student_id = $student_id");

    // Assign new allocation
    if ($room_id > 0) {
        $stmt = $conn->prepare("INSERT INTO room_allocations (student_id, room_id, allocated_on) VALUES (?, ?, NOW())");
        $stmt->bind_param("ii", $student_id, $room_id);
        $stmt->execute();
        $stmt->close();
    }

    $_SESSION['msg'] = "Room updated successfully.";
    header("Location: admin_hostel_records.php");
    exit;
}

// Fetch all rooms
$rooms = $conn->query("SELECT id, room_number, capacity FROM hostel_rooms");

// Fetch hostel records
$sql = "SELECT p.id, p.student_id, s.full_name, hp.plan_name, p.amount, p.status, p.payment_month, p.payment_year, r.room_id, hr.room_number
        FROM hostel_payments p
        JOIN students s ON s.id = p.student_id
        JOIN hostel_plans hp ON hp.id = p.plan_id
        LEFT JOIN room_allocations r ON r.student_id = p.student_id
        LEFT JOIN hostel_rooms hr ON hr.id = r.room_id
        ORDER BY p.created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
  <title>Hostel Records</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
  <h2>Hostel Fee Records</h2>

  <?php if(isset($_SESSION['msg'])): ?>
    <div class="alert alert-success"><?= $_SESSION['msg']; unset($_SESSION['msg']); ?></div>
  <?php endif; ?>

  <table class="table table-bordered">
    <thead>
      <tr>
        <th>ID</th><th>Student</th><th>Plan</th><th>Amount</th>
        <th>Status</th><th>Month</th><th>Year</th><th>Room</th><th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php while($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= htmlspecialchars($row['full_name']) ?></td>
          <td><?= htmlspecialchars($row['plan_name']) ?></td>
          <td>₹<?= number_format($row['amount'],2) ?></td>
          <td><?= htmlspecialchars($row['status']) ?></td>
          <td><?= date("F", mktime(0,0,0,$row['payment_month'],1)) ?></td>
          <td><?= $row['payment_year'] ?></td>
          <td><?= $row['room_number'] ? "Room ".$row['room_number'] : "<span class='text-danger'>Not Allocated</span>" ?></td>
          <td>
            <form method="post" class="d-flex">
              <input type="hidden" name="student_id" value="<?= $row['student_id'] ?>">
              <select name="room_id" class="form-select form-select-sm me-2">
                <option value="0">-- None --</option>
                <?php 
                $rooms->data_seek(0); // reset pointer
                while($r = $rooms->fetch_assoc()): ?>
                  <option value="<?= $r['id'] ?>" 
                    <?= ($row['room_id'] == $r['id']) ? "selected" : "" ?>>
                    <?= "Room ".$r['room_number']." (Cap:".$r['capacity'].")" ?>
                  </option>
                <?php endwhile; ?>
              </select>
              <button type="submit" name="update_room" class="btn btn-sm btn-primary">Update</button>
            </form>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
</body>
</html>
