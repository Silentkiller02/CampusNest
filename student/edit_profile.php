<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $gender = $_POST['gender'];
    $age = $_POST['age'];
    $address = $_POST['address'];

    $stmt = $conn->prepare("UPDATE students SET gender=?, age=?, address=? WHERE id=?");
    $stmt->bind_param("sisi", $gender, $age, $address, $student_id);
    $stmt->execute();

    header("Location: dashboard.php");
    exit();
}

$stmt = $conn->prepare("SELECT gender, age, address FROM students WHERE id=?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();
?>

<h2>Edit Profile</h2>
<form method="POST">
    <label>Gender:</label><br>
    <input type="text" name="gender" value="<?php echo $student['gender']; ?>"><br><br>

    <label>Age:</label><br>
    <input type="number" name="age" value="<?php echo $student['age']; ?>"><br><br>

    <label>Address:</label><br>
    <textarea name="address"><?php echo $student['address']; ?></textarea><br><br>

    <button type="submit">Update</button>
</form>
