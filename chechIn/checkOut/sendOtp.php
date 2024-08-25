<?php
header('Content-Type: application/json');
include 'db_connection.php';
error_reporting(E_ALL); ini_set('display_errors', 1); // Enable error reporting in development

$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'];

// Check if the email format is valid
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["success" => false, "message" => "Invalid email format."]);
    exit();
}

// Check if the email exists
$sql = "SELECT * FROM attache WHERE email = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(["success" => false, "message" => "Database error: " . $conn->error]);
    exit();
}
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Generate OTP
    $otp = rand(100000, 999999);
    $expiry = date("Y-m-d H:i:s", strtotime("+5 minutes"));

    // Save OTP and expiry to the database
    $sql = "INSERT INTO otp (email, otp, expiry) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE otp = VALUES(otp), expiry = VALUES(expiry)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(["success" => false, "message" => "Database error: " . $conn->error]);
        exit();
    }
    $stmt->bind_param("sss", $email, $otp, $expiry);
    $stmt->execute();

    // Send OTP via email
    $to = $email;
    $subject = "Your OTP Code";
    $message = "Your OTP code is $otp. It will expire in 5 minutes.";
    $headers = "From: mwangangioscar2016@gmail.com";

    if (mail($to, $subject, $message, $headers)) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to send email."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Email not found."]);
}

$conn->close();
?>
