<?php
include("../includes/db.php");

// --- Approve student ---
if (isset($_GET['approve_id'])) {
    $id = $_GET['approve_id'];
    $conn->query("UPDATE students SET status = 1 WHERE id = $id");
    header("Location: manage_student.php");
    exit();
}

// --- Delete student ---
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $conn->query("DELETE FROM students WHERE id = $id");
    header("Location: manage_student.php");
    exit();
}

// --- Search and Filter ---
$search = isset($_GET['search']) ? $_GET['search'] : '';
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

$sql = "SELECT * FROM students WHERE 1=1";

// search by enrollment, username, email
if ($search != '') {
    $sql .= " AND (enrollment_no LIKE '%$search%' OR username LIKE '%$search%' OR email LIKE '%$search%')";
}

// filter by status
if ($filter == 'approved') {
    $sql .= " AND status = 1";
} elseif ($filter == 'pending') {
    $sql .= " AND status = 0";
}

$sql .= " ORDER BY id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Students</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .student-card img {
            max-width: 100px;
            border-radius: 10px;
        }
    </style>
</head>
<body class="bg-light">

<div class="container mt-4">
    <h2 class="mb-4">Manage Students</h2>

    <!-- Search + Filter Form -->
    <form method="get" class="row mb-3">
        <div class="col-md-4">
            <input type="text" name="search" value="<?= $search ?>" class="form-control" placeholder="Search by Enrollment, Username, Email">
        </div>
        <div class="col-md-3">
            <select name="filter" class="form-select">
                <option value="all" <?= $filter=='all'?'selected':'' ?>>All Students</option>
                <option value="approved" <?= $filter=='approved'?'selected':'' ?>>Approved</option>
                <option value="pending" <?= $filter=='pending'?'selected':'' ?>>Pending</option>
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100">Search</button>
        </div>
    </form>

    <!-- Students Table -->
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Photo</th>
                <th>Enrollment</th>
                <th>Username</th>
                <th>Email</th>
                <th>Course</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td>
                        <?php if ($row['photo']): ?>
                            <img src="../uploads/<?= $row['photo'] ?>" width="60" class="rounded">
                        <?php else: ?>
                            <span class="text-muted">No Photo</span>
                        <?php endif; ?>
                    </td>
                    <td><?= $row['enrollment_no'] ?></td>
                    <td><?= $row['username'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td><?= $row['course'] ?></td>
                    <td>
                        <?php if ($row['status'] == 1): ?>
                            <span class="badge bg-success">Approved</span>
                        <?php else: ?>
                            <span class="badge bg-warning">Pending</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($row['status'] == 0): ?>
                            <a href="?approve_id=<?= $row['id'] ?>" class="btn btn-sm btn-success">Approve</a>
                        <?php endif; ?>
                        <a href="?delete_id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                        <a href="view_student.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-info">View</a>
                        <a href="edit_student.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>
