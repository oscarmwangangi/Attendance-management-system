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

if (isset($_GET['department'])) {
    $department = $_GET['department'];
    $currentDate = date("Y-m-d");

    $sql = "SELECT name, ID_number, school, course, date_joined, date_leaving, email, phone_number, Department, Supervisor 
            FROM attache 
            WHERE Department = ? AND date_leaving > ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $department, $currentDate);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr>
                <th>No.</th>
                <th>Name</th>
                <th>ID Number</th>
                <th>University</th>
                <th>Course</th>
                <th>Date Joined</th>
                <th>Date Leaving</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Supervisor</th>
                <th>Department</th>
              </tr>";
        $counter = 1;
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$counter}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['ID_number']}</td>
                    <td>{$row['school']}</td>
                    <td>{$row['course']}</td>
                    <td>{$row['date_joined']}</td>
                    <td>{$row['date_leaving']}</td>
                    <td>{$row['email']}</td>
                    <td>{$row['phone_number']}</td>
                    <td>{$row['Supervisor']}</td>
                    <td>{$row['Department']}</td>
                  </tr>";
            $counter++;
        }
        echo "</table>";
    } else {
        echo "No ongoing records found for the selected department.";
    }

    $stmt->close();
}

$conn->close();
?>
