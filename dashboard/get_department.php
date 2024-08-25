<?php
require 'db_connection.php'; // Include the database connection

header('Content-Type: application/json');

$query = "SELECT name FROM departments";
$result = $conn->query($query);

$departments = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $departments[] = $row;
    }
    echo json_encode($departments);
} else {
    echo json_encode(['error' => 'Error fetching departments']);
}

$conn->close();
?>
