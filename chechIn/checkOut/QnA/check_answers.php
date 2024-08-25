<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Answers</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        input {
            text-transform: lowercase;
        }
    </style>
</head>
<body>
<div class="container">
    <!-- Content will be loaded here -->
</div>

<!-- Bootstrap Modal -->
<div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="alertModalLabel">Alert</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="alertMessage">
                <!-- Message will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="redirect()">OK</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    function showAlert(message) {
        document.getElementById('alertMessage').innerText = message;
        $('#alertModal').modal('show');
    }

    function redirect() {
        window.location.href = 'index.html';  // Redirect to the desired page
    }
</script>

<?php
date_default_timezone_set('Africa/Nairobi');
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "attache_management";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("<script>showAlert('Connection failed: " . $conn->connect_error . "');</script>");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = strtolower($_POST['email']);  // Convert email to lowercase
    $action = $_POST['action'];

    // Get attache details
    $sql = "SELECT id, name, Supervisor, Department, date_leaving, verified_at FROM attache WHERE email = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("<script>showAlert('Prepare failed: " . $conn->error . "');</script>");
    }

    $today_date = date('Y-m-d'); // Get the current date in YYYY-MM-DD format
    if ($today_date == 'Saturday' || $today_date == 'Sunday') {
        echo "<script>showAlert('Check-in and check-out are only allowed on weekdays.');</script>";
        exit(); // Stop further execution
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        die("<script>showAlert('No attache found with this email.');</script>");
    }
    $attache = $result->fetch_assoc();
    $attache_id = $attache['id'];
    $attache_name = $attache['name'];
    $supervisor = $attache['Supervisor'];
    $department = $attache['Department'];
    $date_leaving = $attache['date_leaving'];
    $verified_at = $attache['verified_at'];
    
    $stmt->close();

    // Check if date_leaving is less than the current date
    if ($date_leaving < $today_date) {
        die("<script>showAlert('You cannot check in or out because your status is finished.');</script>");
    }

    // Check if attache is verified
    if ($verified_at === null) {
        die("<script>showAlert('You cannot check in because you have not been verified.');</script>");
    }

    // Get the question ID and the provided answer
    $question_id = null;
    $provided_answer = null;
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'question_') === 0) {
            $question_id = str_replace('question_', '', $key);
            $provided_answer = strtolower($value);  // Convert provided answer to lowercase
            break;
        }
    }

    if ($question_id === null || $provided_answer === null) {
        die("<script>showAlert('Question ID or provided answer not found.');</script>");
    }

    // Get the correct answer from the database
    $sql = "SELECT answer FROM answers WHERE attache_id = ? AND question_id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("<script>showAlert('Prepare failed: " . $conn->error . "');</script>");
    }
    $stmt->bind_param("ii", $attache_id, $question_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        die("<script>showAlert('No correct answer found for this question.');</script>");
    }
    $correct_answer = strtolower($result->fetch_assoc()['answer']);  // Convert correct answer to lowercase
    $stmt->close();

    if ($provided_answer == $correct_answer) {
        if ($action == 'checkin') {
            $sql = "INSERT INTO checkins (attache_id, name, Supervisor, Department, checkin_time) VALUES (?, ?, ?, ?, NOW())";
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                die("<script>showAlert('Prepare failed: " . $conn->error . "');</script>");
            }
            $stmt->bind_param("isss", $attache_id, $attache_name, $supervisor, $department);
            $stmt->execute();
            if ($stmt->affected_rows > 0) {
                echo "<script>showAlert('Check-in successful!');</script>";
            } else {
                echo "<script>showAlert('Check-in failed.');</script>";
            }
            $stmt->close();
        } elseif ($action == 'checkout') {
            $sql = "UPDATE checkins SET checkout_time = NOW() WHERE attache_id = ? AND DATE(checkin_time) = ? AND checkout_time IS NULL";
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                die("<script>showAlert('Prepare failed: " . $conn->error . "');</script>");
            }
            $current_date = date('Y-m-d');
            $stmt->bind_param("is", $attache_id, $current_date);
            $stmt->execute();
            if ($stmt->affected_rows > 0) {
                echo "<script>showAlert('Check-out successful!');</script>";
            } else {
                echo "<script>showAlert('Check-out failed.');</script>";
            }
            $stmt->close();
        }
    } else {
        echo "<script>showAlert('Incorrect answer. Please try again.');</script>";
    }
}

$conn->close();
?>
</body>
</html>
