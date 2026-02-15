<?php
include("../includes/db.php");
include("header.php");

if (!isset($_GET['id'])) {
    echo "<div class='alert alert-danger'>No student selected.</div>";
    exit;
}

$id = intval($_GET['id']);

// Fetch student details
$stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

if (!$student) {
    echo "<div class='alert alert-danger'>Student not found.</div>";
    exit;
}
?>

<div class="container mt-4">
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header bg-primary text-white text-center">
            <h3>Student Full Details</h3>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <!-- Profile Photo -->
                <div class="col-md-4 text-center">
                    <img src="../uploads/photos/<?php echo htmlspecialchars($row['photo']); ?>" width="120"> 
                        
                    <p class="mt-3"><strong>Signature:</strong></p>
                    <img src="../uploads/signatures/<?php echo htmlspecialchars($row['signature']); ?>" width="120">
                </div>

                <!-- Student Info -->
                <div class="col-md-8">
                    <table class="table table-bordered table-striped">
                        <tr><th>Enrollment No.</th><td><?php echo htmlspecialchars($student['enrollment_no']); ?></td></tr>
                        <tr><th>Username</th><td><?php echo htmlspecialchars($student['username']); ?></td></tr>
                        <tr><th>Full Name</th><td><?php echo htmlspecialchars($student['full_name']); ?></td></tr>
                        <tr><th>Father's Name</th><td><?php echo htmlspecialchars($student['father_name']); ?></td></tr>
                        <tr><th>Mother's Name</th><td><?php echo htmlspecialchars($student['mother_name']); ?></td></tr>
                        <tr><th>Country</th><td><?php echo htmlspecialchars($student['countryname']); ?></td></tr>
                        <tr><th>Email</th><td><?php echo htmlspecialchars($student['email']); ?></td></tr>
                        <tr><th>Gender</th><td><?php echo htmlspecialchars($student['gender']); ?></td></tr>
                        <tr><th>Date of Birth</th><td><?php echo htmlspecialchars($student['dob']); ?></td></tr>
                        <tr><th>Age</th><td><?php echo htmlspecialchars($student['age']); ?></td></tr>
                        <tr><th>Address</th><td><?php echo htmlspecialchars($student['address']); ?></td></tr>
                        <tr><th>Mobile No.</th><td><?php echo htmlspecialchars($student['mobile_no']); ?></td></tr>
                        <tr><th>Parent Mobile</th><td><?php echo htmlspecialchars($student['parent_mobile']); ?></td></tr>
                        <tr><th>Course</th><td><?php echo htmlspecialchars($student['course']); ?></td></tr>
                        <tr><th>Semester</th><td><?php echo htmlspecialchars($student['semester']); ?></td></tr>
                        <tr><th>Category</th><td><?php echo htmlspecialchars($student['category']); ?></td></tr>
                        <tr><th>Degree</th><td><?php echo htmlspecialchars($student['degree']); ?></td></tr>
                        <tr><th>Department</th><td><?php echo htmlspecialchars($student['department_name']); ?></td></tr>
                        <tr>
                            <th>Address Proof</th>
                            <td>
                                <?php if (!empty($student['address_proof'])): ?>
                                    <a href="../uploads/<?php echo htmlspecialchars($student['address_proof']); ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                        View Document
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">Not Uploaded</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <?php if ($student['status'] == 1): ?>
                                    <span class="badge bg-success">Approved</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark">Pending</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="card-footer text-center">
            <a href="manage_student.php" class="btn btn-secondary">⬅ Back</a>
            <a href="edit_student.php?id=<?php echo $student['id']; ?>" class="btn btn-warning">✏ Edit</a>
            <a href="delete_student.php?id=<?php echo $student['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure to delete this student?');">🗑 Delete</a>
        </div>
    </div>
</div>

<?php include("footer.php"); ?>
