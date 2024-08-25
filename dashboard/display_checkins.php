<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    date_default_timezone_set('Africa/Nairobi'); // Set your timezone

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

    $department = $_POST['department'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Validate the dates
    if (strtotime($start_date) > strtotime($end_date)) {
        die("Start date must be before or equal to end date.");
    }

    // Adjust date format for SQL query
    $start_datetime = date('Y-m-d 00:00:00', strtotime($start_date));
    $end_datetime = date('Y-m-d 23:59:59', strtotime($end_date));
    $current_date = date('Y-m-d'); // Get the current date in YYYY-MM-DD format

    // Prepare the SQL query with adjusted date range
    $sql = "SELECT checkins.checkin_time, checkins.checkout_time, checkins.name, checkins.Supervisor, checkins.Department 
            FROM checkins 
            JOIN attache ON checkins.name = attache.name
            WHERE checkins.Department = ? 
            AND checkins.checkin_time >= ? 
            AND checkins.checkin_time <= ?
            AND attache.date_leaving > ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("ssss", $department, $start_datetime, $end_datetime, $current_date);
    $stmt->execute();
    $result = $stmt->get_result();

    // Display the results
    if ($result->num_rows > 0) {
        echo "<table class='table table-bordered'>";
        echo "<tr><th>No.</th><th>Name</th><th>Supervisor</th><th>Check-in Time</th><th>Check-out Time</th><th>Department</th></tr>";
        $counter = 1;
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $counter++ . "</td>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>" . $row['Supervisor'] . "</td>";
            echo "<td>" . $row['checkin_time'] . "</td>";
            echo "<td>" . ($row['checkout_time'] ?? 'NULL') . "</td>";
            echo "<td>" . $row['Department'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No check-ins found for the selected period and department.";
    }

    $stmt->close();
    $conn->close();
}
?>
