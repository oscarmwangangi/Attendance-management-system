<?php
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "attache_management";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$department = $_POST['department'];

$sql = "INSERT INTO departments (name) VALUES ('$department')";
if ($conn->query($sql) === TRUE) {
    $response = array('status' => 'success', 'message' => 'Department added successfully');
} else {
    $response = array('status' => 'error', 'message' => 'Error adding department: ' . $conn->error);
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>
