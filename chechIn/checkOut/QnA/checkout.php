<?php
// date_default_timezone_set('Africa/Nairobi');
// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "attache_management";

// // Create connection
// $conn = new mysqli($servername, $username, $password, $dbname);

// // Check connection
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }

// if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//     $email = $_POST['email'];

//     // Get attache details
//     $sql = "SELECT id, name, Supervisor, Department, status FROM attache WHERE email = ?";
//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param("s", $email);
//     $stmt->execute();
//     $result = $stmt->get_result();
//     $attache = $result->fetch_assoc();
//     $attache_id = $attache['id'];
//     $attache_name = $attache['name'];
//     $supervisor = $attache['Supervisor'];
//     $department = $attache['Department'];
//     $status = $attache['status'];

//     // Check attache status
//     if ($status == 'finished') {
//         die("You cannot check in or out because your status is finished.");
//     }

//     // Update checkout time
//     $checkout_time = date('Y-m-d H:i:s');
//     $sql = "UPDATE checkins SET checkout_time = ? WHERE attache_id = ? AND checkout_time IS NULL";
//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param("si", $checkout_time, $attache_id);
//     $stmt->execute();

//     echo "Checkout successful!";
// }

// $conn->close();
?>
