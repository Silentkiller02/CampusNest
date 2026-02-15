<?php
session_start();
include("../includes/db.php");

// Handle form submission (assign room)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['student_id'], $_POST['room_id'])) {
    $student_id = (int)$_POST['student_id'];
    $room_id    = (int)$_POST['room_id'];

    // Remove existing allocation if any
    $conn->query("DELETE FROM hostel_allocations WHERE student_id = $student_id");

    // Insert new allocation
    if ($room_id > 0) {
        $stmt = $conn->prepare("INSERT INTO room_allocations (student_id, room_id, allocated_on) VALUES (?, ?, NOW())");
        $stmt->bind_param("ii", $student_id, $room_id);
        $stmt->execute();
        $stmt->close();
    }

    $_SESSION['msg'] = "Room updated successfully!";
    header("Location: admin_hostel_allocate.php");
    exit;
}

// Fetch students with current allocation
$sql = "SELECT s.id, s.full_name, hp.plan_name, 
               r.room_number, ha.room_id
        FROM students s
        LEFT JOIN hostel_payments p ON p.student_id = s.id
        LEFT JOIN hostel_plans hp ON hp.id = p.plan_id
        LEFT JOIN room_allocations ha ON ha.student_id = s.id
        LEFT JOIN hostel_rooms r ON r.id = ha.room_id
        GROUP BY s.id
        ORDER BY s.full_name";
$students = $conn->query($sql);

// Fetch available rooms
$rooms = $conn->query("SELECT id, room_number, category, type 
                       FROM hostel_rooms 
                       WHERE capacity > (SELECT COUNT(*) FROM room_allocations ha WHERE ha.room_id = hostel_rooms.id)");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Admin - Hostel Room Allocation</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
  <h2>Hostel Room Allocation</h2>

  <?php if (!empty($_SESSION['msg'])): ?>
    <div class="alert alert-success"><?= $_SESSION['msg']; unset($_SESSION['msg']); ?></div>
  <?php endif; ?>

  <table class="table table-bordered">
    <thead class="table-light">
      <tr>
        <th>Student</th>
        <th>Plan</th>
        <th>Current Room</th>
        <th>Assign New Room</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
    <?php while ($row = $students->fetch_assoc()): ?>
      <tr>
        <form method="post">
          <td><?= htmlspecialchars($row['full_name']); ?></td>
          <td><?= htmlspecialchars($row['plan_name'] ?? '-'); ?></td>
          <td><?= $row['room_number'] ? "Room ".$row['room_number'] : "<span class='text-danger'>Unallocated</span>"; ?></td>
          <td>
            <select name="room_id" class="form-select">
              <option value="0">-- Remove Allocation --</option>
              <?php
              // reload rooms fresh each loop
              $roomlist = $conn->query("SELECT id, room_number, category, type 
                                        FROM hostel_rooms 
                                        WHERE capacity > (SELECT COUNT(*) FROM hostel_allocations ha WHERE ha.room_id = hostel_rooms.id)
                                        OR id = " . (int)$row['room_id']); // include current
              while ($r = $roomlist->fetch_assoc()):
              ?>
                <option value="<?= $r['id']; ?>" <?= $row['room_id']==$r['id'] ? 'selected' : '' ?>>
                  Room <?= $r['room_number']; ?> (<?= $r['category']; ?>, <?= $r['type']; ?>)
                </option>
              <?php endwhile; ?>
            </select>
          </td>
          <td>
            <input type="hidden" name="student_id" value="<?= $row['id']; ?>">
            <button class="btn btn-sm btn-primary">Save</button>
          </td>
        </form>
      </tr>
    <?php endwhile; ?>
    </tbody>
  </table>
</div>
</body>
</html>
