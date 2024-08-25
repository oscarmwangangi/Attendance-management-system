<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attache Management</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['ID_number']) && !empty($_POST['ID_number'])) {
        $ID_number = $_POST['ID_number'];

        // Sanitize input to prevent SQL injection
        $ID_number = $conn->real_escape_string($ID_number);

        // Fetch attache details
        $sql_attache = "SELECT * FROM attache WHERE ID_number='$ID_number'";
        $result_attache = $conn->query($sql_attache);

        if ($result_attache->num_rows > 0) {
            $attache = $result_attache->fetch_assoc();

            // Fetch checkin details
            $attache_id = $attache['id'];
            $sql_checkins = "SELECT * FROM checkins WHERE attache_id='$attache_id' ORDER BY checkin_time";
            $result_checkins = $conn->query($sql_checkins);

            // Display attache information
            echo "<div class='container mt-5'>";
            echo "<div class='card shadow-sm'>";
            echo "<div class='card-body'>";
            echo "<h1 class='mb-4'>Attache Information</h1>";
            echo "<table class='table table-hover'>";
            echo "<thead class='thead-dark'>
                    <tr>
                    <th>ID Number</th><th>Name</th><th>Supervisor</th><th>Department</th><th>Date Leaving</th>
                    </tr>
                  </thead>";
            echo "<tbody>";
            echo "<tr class='table-info'>";
            echo "<td>" . htmlspecialchars($attache['ID_number'] ?? 'N/A') . "</td>";
            echo "<td>" . htmlspecialchars($attache['name'] ?? 'N/A') . "</td>";
            echo "<td>" . htmlspecialchars($attache['Supervisor'] ?? 'N/A') . "</td>";
            echo "<td>" . htmlspecialchars($attache['Department'] ?? 'N/A') . "</td>";
            echo "<td>" . htmlspecialchars($attache['date_leaving'] ?? 'N/A') . "</td>";
            echo "</tr>";
            echo "</tbody>";
            echo "</table>";

            // Display checkin details
            echo "<h2 class='mt-12'>Check-in Details</h2>";
            echo "<table class='table table-hover'>";
            echo "<thead class='thead-dark'>
                    <tr>
                    <th>Check-in Time</th><th>Check-out Time</th>
                    </tr>
                  </thead>";
            echo "<tbody>";
            if ($result_checkins->num_rows > 0) {
                while($checkin = $result_checkins->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($checkin['checkin_time'] ?? 'N/A') . "</td>";
                    echo "<td>" . htmlspecialchars($checkin['checkout_time'] ?? 'N/A') . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='2'>No check-in records found.</td></tr>";
            }
            echo "</tbody>";
            echo "</table>";
            echo "</div>";
            echo "</div>";
            echo "</div>";

        } else {
            echo "<div class='container mt-5'>
                    <div class='alert alert-warning' role='alert'>
                        No attache found with the ID number '$ID_number'.
                    </div>
                  </div>";
        }
    } else {
        echo "<div class='container mt-5'>
                <div class='alert alert-danger' role='alert'>
                    Please enter an ID number.
                </div>
              </div>";
    }
}

$conn->close();
?>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
