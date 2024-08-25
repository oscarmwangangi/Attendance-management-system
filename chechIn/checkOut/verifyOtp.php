<?php
header('Content-Type: application/json');
include 'db_connection.php';
error_reporting(E_ALL); ini_set('display_errors', 1); // Enable error reporting in development

$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'];
$otp = $data['otp'];

// Verify OTP
$sql = "SELECT * FROM otp WHERE email = ? AND otp = ? AND expiry > NOW()";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $email, $otp);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // OTP is valid, record check-in time
    $check_in_date = date("Y-m-d");
    $check_in_time = date("H:i:s");

    // Get attache_id
    $sql = "SELECT id FROM attache WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $attache = $result->fetch_assoc();
    $attache_id = $attache['id'];

    // Insert check-in record
    $sql = "INSERT INTO attendance (attache_id, check_in_date, check_in_time) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $attache_id, $check_in_date, $check_in_time);
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "dateTime" => "$check_in_date $check_in_time"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to record check-in."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid OTP or OTP expired."]);
}

$conn->close();
?>
