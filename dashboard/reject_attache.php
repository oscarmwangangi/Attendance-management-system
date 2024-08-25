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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];

    $stmt = $conn->prepare("DELETE FROM attache WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo "<script>alert('Attaché rejected and deleted successfully.'); window.location.href='admin_dasboard.php';</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "'); window.location.href='admin_dasboard.php';</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Prepare failed: " . $conn->error . "'); window.location.href='admin_dasboard.php';</script>";
    }
} else {
    echo "<script>alert('Invalid request.'); window.location.href='admin_dasboard.php';</script>";
}

$conn->close();
?>
