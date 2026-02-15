<?php
session_start();
include("../includes/db.php");

// Auto Allocate Room (simplified: assign next available room)
if (isset($_POST['auto_allocate'])) {
    $student_id = $_POST['student_id'];

    // find next available room
    $room = $conn->query("SELECT id FROM hostel_rooms WHERE occupied = 0 LIMIT 1")->fetch_assoc();
    if ($room) {
        $room_id = $room['id'];
        $conn->query("UPDATE hostel_rooms SET occupied=1, student_id=$student_id WHERE id=$room_id");
    }
}

// Manual Allocation
if (isset($_POST['manual_allocate'])) {
    $student_id = $_POST['student_id'];
    $room_id = $_POST['room_id'];
    $conn->query("UPDATE hostel_rooms SET occupied=1, student_id=$student_id WHERE id=$room_id");
}

// Fetch students & rooms
$students = $conn->query("SELECT id, name FROM students WHERE id NOT IN (SELECT student_id FROM hostel_rooms WHERE occupied=1)");
$rooms = $conn->query("SELECT id, room_no FROM hostel_rooms WHERE occupied=0");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Allocate Rooms</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
  <h2>Room Allocation</h2>
  <form method="post" class="row g-3 mb-3">
    <div class="col-md-4">
      <select name="student_id" class="form-select" required>
        <option value="">Select Student</option>
        <?php while($s = $students->fetch_assoc()): ?>
          <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
        <?php endwhile; ?>
      </select>
    </div>
    <div class="col-md-4">
      <select name="room_id" class="form-select">
        <option value="">Select Room (Manual)</option>
        <?php while($r = $rooms->fetch_assoc()): ?>
          <option value="<?= $r['id'] ?>">Room <?= $r['room_no'] ?></option>
        <?php endwhile; ?>
      </select>
    </div>
    <div class="col-md-2">
      <button type="submit" name="manual_allocate" class="btn btn-success w-100">Manual Allocate</button>
    </div>
    <div class="col-md-2">
      <button type="submit" name="auto_allocate" class="btn btn-primary w-100">Auto Allocate</button>
    </div>
  </form>
</div>
</body>
</html>
