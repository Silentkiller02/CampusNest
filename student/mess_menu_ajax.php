<?php
include("../includes/db.php");
session_start();

$plan_type = $_POST['plan_type'] ?? '';
$category  = $_POST['category'] ?? '';
$student_id = $_SESSION['student_id'] ?? 1;

$response = [
    "menu_html" => "<div class='alert alert-warning'>Please select both plan type and category.</div>",
    "cost" => null,
    "payment_status" => null,
    "payment_id" => null
];

if (!$plan_type || !$category) {
    echo json_encode($response);
    exit;
}

// 1. Get plan details
$sql = "SELECT * FROM mess_plans WHERE plan_type = ? AND category = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $plan_type, $category);
$stmt->execute();
$result = $stmt->get_result();
$plan = $result->fetch_assoc();

if (!$plan) {
    $response["menu_html"] = "<div class='alert alert-danger'>No plan found.</div>";
    echo json_encode($response);
    exit;
}

$response["cost"] = $plan['cost'];

// 2. Get weekly menu
$sql = "SELECT * FROM mess_menu 
        WHERE plan_id = ? 
        ORDER BY FIELD(day_of_week,'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday')";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $plan['id']);
$stmt->execute();
$menu_result = $stmt->get_result();

$menu_html = "<div class='card shadow-sm mb-3'><div class='card-body'>";
$menu_html .= "<h5>{$plan['plan_name']} ({$plan['plan_type']} - {$plan['category']})</h5>";
$menu_html .= "<p><strong>Cost:</strong> ₹".number_format($plan['cost'],2)."</p>";
$menu_html .= "</div></div>";

if ($menu_result->num_rows > 0) {
    $menu_html .= "<table class='table table-bordered shadow-sm'>";
    $menu_html .= "<thead class='table-light'><tr><th>Day</th><th>Meal 1</th><th>Meal 2</th><th>Meal 3</th><th>Meal 4</th></tr></thead><tbody>";
    while ($row = $menu_result->fetch_assoc()) {
        $menu_html .= "<tr>
            <td>{$row['day_of_week']}</td>
            <td>".($row['Breakfast'] ?? '-')."</td>
            <td>".($row['Lunch'] ?? '-')."</td>
            <td>".($row['Snacks'] ?? '-')."</td>
            <td>".($row['Dinner'] ?? '-')."</td>
        </tr>";
    }
    $menu_html .= "</tbody></table>";
} else {
    $menu_html .= "<div class='alert alert-secondary'>No menu set for this plan.</div>";
}

$response["menu_html"] = $menu_html;

// 3. Check student’s payment status
$sql = "SELECT id, status FROM mess_payments 
        WHERE student_id = ? AND plan_id = ?
        ORDER BY id DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $student_id, $plan['id']);
$stmt->execute();
$payment = $stmt->get_result()->fetch_assoc();

if ($payment) {
    $response["payment_status"] = $payment['status'];
    $response["payment_id"] = $payment['id'];
} else {
    $response["payment_status"] = "Not Paid";
}

echo json_encode($response);
