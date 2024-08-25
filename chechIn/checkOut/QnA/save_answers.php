<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Save Answers</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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

    // Get attache ID and name
    $sql = "SELECT id, name FROM attache WHERE email = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("<script>showAlert('Prepare failed: " . $conn->error . "');</script>");
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
    $stmt->close();

    // Check if 'answers' is set in $_POST
    if (isset($_POST['answers']) && is_array($_POST['answers'])) {
        $answers = $_POST['answers'];

        foreach ($answers as $question_id => $answer) {
            $answer = strtolower($answer);  // Convert answer to lowercase

            $sql = "INSERT INTO answers (attache_id, name, question_id, answer) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                die("<script>showAlert('Prepare failed: " . $conn->error . "');</script>");
            }
            $stmt->bind_param("isis", $attache_id, $attache_name, $question_id, $answer);
            $stmt->execute();
            $stmt->close();
        }

        echo "<script>showAlert('Answers saved successfully!');</script>";
    } else {
        echo "<script>showAlert('No answers provided!');</script>";
    }
}

$conn->close();
?>
</body>
</html>
