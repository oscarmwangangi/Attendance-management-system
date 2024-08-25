<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./print.css">
</head>
<body>
    

<?php
date_default_timezone_set('Africa/Nairobi'); // Set your timezone, e.g., 'America/New_York'

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "attache_management";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $selected_date = $_POST['date'];

    // Fetch check-ins and check-outs for the selected date
    $sql = "SELECT name, checkin_time, checkout_time FROM checkins WHERE DATE(checkin_time) = ? OR DATE(checkout_time) = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $selected_date, $selected_date);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<h1>Check-ins/Check-outs for " . $selected_date . "</h1>";
        echo "<table>
                <tr>
                    <th>Name</th>
                    <th>Check-in Time</th>
                    <th>Check-out Time</th>
                </tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['name']}</td>
                    <td>{$row['checkin_time']}</td>
                    <td>{$row['checkout_time']}</td>
                  </tr>";
        }

        echo "</table>";
        echo "<button onclick='window.print()'>Print</button>";
    } else {
        echo "No records found for the selected date.";
    }

    $stmt->close();
}

$conn->close();
?>
</body>
</html>