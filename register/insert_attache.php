<?php
session_start(); // Start the session

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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date_joined = $_POST['date_joined'];
    $date_leaving = $_POST['date_leaving'];

    $today = date('Y-m-d');

    if ($date_leaving <= $date_joined) {
        $_SESSION['error'] = 'Date Leaving must be greater than Date Joined.';
        header('Location: your_form_page.php');
        exit();
    }

    if ($date_leaving <= $today) {
        $_SESSION['error'] = 'Date Leaving cannot be today or in the past.';
        header('Location: register.php');
        exit();
    }

    // Retrieve POST data
    $ID_number = $_POST['ID_number'];
    $email = $_POST['email'];
    $name = $_POST['name'];
    $school = $_POST['school'];
    $course = $_POST['course'];
    $phone_number = $_POST['phone_number'];
    $Department = $_POST['Department'];
    $Supervisor = $_POST['Supervisor'];
    $school_supervisor_name = $_POST['school_supervisor_name'];
    $school_supervisor_number = $_POST['school_supervisor_number'];
    $verified_at = isset($_POST['verified_at']) ? $_POST['verified_at'] : null;

    // Check if ID_number or email already exists
    $checkQuery = "SELECT ID_number, email FROM attache WHERE ID_number = ? OR email = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("ss", $ID_number, $email);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        // ID_number or email already exists
        $_SESSION['error'] = "The ID number or email already exists.";
        $checkStmt->close();
        $conn->close();
        header("Location: register.php");
        exit();
    }

    $checkStmt->close();

    // Prepare and bind without the status column
    $stmt = $conn->prepare("INSERT INTO attache (name, ID_number, school, course, date_joined, date_leaving, email, phone_number, Department, Supervisor, school_supervisor_name, school_supervisor_number, verified_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("sssssssssssss", $name, $ID_number, $school, $course, $date_joined, $date_leaving, $email, $phone_number, $Department, $Supervisor, $school_supervisor_name, $school_supervisor_number, $verified_at);

    if ($stmt->execute()) {
        $_SESSION['success'] = "New record created successfully";
    } else {
        $_SESSION['error'] = "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    header("Location: register.php");
    exit();
}
?>
