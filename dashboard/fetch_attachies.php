<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "attache_management";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$currentDate = isset($_GET['current_date']) ? $_GET['current_date'] : date('Y-m-d');
$dateComparison = isset($_GET['date_comparison']) ? $_GET['date_comparison'] : '<';

// SQL query to fetch attachees where date_leaving is less than the current date
// SQL query based on date_comparison parameter
$sql = "SELECT name, ID_number, school, course, date_joined, date_leaving, email, phone_number, Department, Supervisor
        FROM attache
        WHERE date_leaving $dateComparison ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $currentDate);
$stmt->execute();
$result = $stmt->get_result();

// Output HTML for the table
if ($result->num_rows > 0) {
    echo '<table>';
    echo '<tr>
            <th>#</th>
            <th>Name</th>
            <th>ID Number</th>
            <th>School</th>
            <th>Course</th>
            <th>Date Joined</th>
            <th>Date Leaving</th>
            <th>Email</th>
            <th>Phone Number</th>
            <th>Department</th>
            <th>Supervisor</th>
          </tr>';
    $count = 1;
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$count}</td>
                <td>{$row['name']}</td>
                <td>{$row['ID_number']}</td>
                <td>{$row['school']}</td>
                <td>{$row['course']}</td>
                <td>{$row['date_joined']}</td>
                <td>{$row['date_leaving']}</td>
                <td>{$row['email']}</td>
                <td>{$row['phone_number']}</td>
                <td>{$row['Department']}</td>
                <td>{$row['Supervisor']}</td>
              </tr>";
        $count++;
    }
    echo '</table>';
} else {
    echo 'No records found';
}

$stmt->close();
$conn->close();
?>
