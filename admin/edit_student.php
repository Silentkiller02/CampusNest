<?php
include("../includes/db.php");

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM students WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $full_name = $_POST['full_name'];
    $father_name = $_POST['father_name'];
    $mother_name = $_POST['mother_name'];
    $countryname = $_POST['countryname'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $age = $_POST['age'];
    $address = $_POST['address'];
    $mobile_no = $_POST['mobile_no'];
    $parent_mobile = $_POST['parent_mobile'];
    $course = $_POST['course'];
    $semester = $_POST['semester'];
    $category = $_POST['category'];
    $degree = $_POST['degree'];
    $department_name = $_POST['department_name'];
    $status = $_POST['status'];

    // File Handling (photo, signature)
    $photo = $student['photo']; // keep old
    if (!empty($_FILES['photo']['name'])) {
        $photo = time() . "_" . $_FILES['photo']['name'];
        move_uploaded_file($_FILES['photo']['tmp_name'], "../uploads/" . $photo);
    }

    $signature = $student['signature']; // keep old
    if (!empty($_FILES['signature']['name'])) {
        $signature = time() . "_" . $_FILES['signature']['name'];
        move_uploaded_file($_FILES['signature']['tmp_name'], "../uploads/" . $signature);
    }

    $sql = "UPDATE students SET full_name=?, father_name=?, mother_name=?, countryname=?, email=?, gender=?, dob=?, age=?, address=?, mobile_no=?, parent_mobile=?, course=?, semester=?, category=?, degree=?, department_name=?, status=?, photo=?, signature=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssssssssssssi", $full_name, $father_name, $mother_name, $countryname, $email, $gender, $dob, $age, $address, $mobile_no, $parent_mobile, $course, $semester, $category, $degree, $department_name, $status, $photo, $signature, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Student updated successfully'); window.location.href='manage_student.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Student</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<div class="container">
    <h2>Edit Student Information</h2>
    <form method="POST" enctype="multipart/form-data" class="edit-form">
        <input type="hidden" name="id" value="<?php echo $student['id']; ?>">

        <label>Full Name</label>
        <input type="text" name="full_name" value="<?php echo $student['full_name']; ?>" required>

        <label>Father Name</label>
        <input type="text" name="father_name" value="<?php echo $student['father_name']; ?>">

        <label>Mother Name</label>
        <input type="text" name="mother_name" value="<?php echo $student['mother_name']; ?>">

        <label>Email</label>
        <input type="email" name="email" value="<?php echo $student['email']; ?>">

        <label>Mobile</label>
        <input type="text" name="mobile_no" value="<?php echo $student['mobile_no']; ?>">

        <label>Address</label>
        <textarea name="address"><?php echo $student['address']; ?></textarea>

        <label>Current Photo</label>
        <img src="../uploads/<?php echo $student['photo']; ?>" class="preview">
        <input type="file" name="photo">

        <label>Current Signature</label>
        <img src="../uploads/<?php echo $student['signature']; ?>" class="preview">
        <input type="file" name="signature">

        <label>Status</label>
        <select name="status">
            <option value="1" <?php if ($student['status']==1) echo "selected"; ?>>Approved</option>
            <option value="0" <?php if ($student['status']==0) echo "selected"; ?>>Pending</option>
        </select>

        <button type="submit" class="btn">Update</button>
    </form>
</div>
</body>
</html>

