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

$id = $_POST['id'];

$sql = "DELETE FROM departments WHERE id='$id'";
if ($conn->query($sql) === TRUE) {
    $response = array('status' => 'success', 'message' => 'Department deleted successfully');
} else {
    $response = array('status' => 'error', 'message' => 'Error deleting department: ' . $conn->error);
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>
