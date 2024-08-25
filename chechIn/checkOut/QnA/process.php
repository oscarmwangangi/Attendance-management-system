<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Process</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./process.css">
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
        window.location.href = 'index.html';
    }
</script>

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
    die("<script>showAlert('Connection failed: " . $conn->connect_error . "');</script>");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $action = $_POST['action'];

    // Check if the attache exists
    $sql = "SELECT id, name, Supervisor, Department FROM attache WHERE email = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("<script>showAlert('Prepare failed: " . $conn->error . "');</script>");
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();    
    
     // Check if today is a weekday
    $today_day = date('l'); // Get the current day in full text
    if ($today_day == 'Saturday' || $today_day == 'Sunday') {
        echo "<script>showAlert('Check-in and check-out are only allowed on weekdays.');</script>";
        exit(); // Stop further execution
    }

    if ($result->num_rows > 0) {
        // attache exists
        $attache = $result->fetch_assoc();
        $attache_id = $attache['id'];
        $attache_name = $attache['name'];
        $supervisor = $attache['Supervisor'];
        $department = $attache['Department'];

      

        // Today's date
        $today = date('Y-m-d');

        if ($action == 'checkin') {
            // Check if the attache has already checked in today
            $sql = "SELECT * FROM checkins WHERE attache_id = ? AND DATE(checkin_time) = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("is", $attache_id, $today);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "<script>showAlert('You have already checked in today.');</script>";
            } else {
                // Proceed with the check-in process
                // Check if the attache has already answered questions
                $sql = "SELECT question_id FROM answers WHERE attache_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $attache_id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    // attache has answered questions before, select one randomly for verification
                    $answered_questions = [];
                    while ($row = $result->fetch_assoc()) {
                        $answered_questions[] = $row['question_id'];
                    }

                    $random_question_id = $answered_questions[array_rand($answered_questions)];

                    $sql = "SELECT question FROM questions WHERE id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $random_question_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $question = $result->fetch_assoc()['question'];

                    echo "<form method='post' action='check_answers.php'>";
                    echo "<label for='question_$random_question_id'>$question</label>";
                    echo "<input type='text' id='question_$random_question_id' name='question_$random_question_id' required autocomplete='off'>";
                    echo "<input type='hidden' name='email' value='$email'>";
                    echo "<input type='hidden' name='action' value='checkin' autocomplete='off'>";
                    echo "<button type='submit'>Submit</button>";
                    echo "</form>";
                } else {
                    // attache has not answered questions before, display all questions for selection
                    $sql = "SELECT id, question FROM questions";
                    $result = $conn->query($sql);

                    echo "<form method='post' action='select_questions.php' class='question-form'>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<input type='checkbox' name='questions[]' value='" . $row['id'] . "'>" . $row['question'] . "<br>";
                    }
                    echo "<input type='hidden' name='email' value='$email' autocomplete='off'>";
                    echo "<button type='submit'>Submit</button>";
                    echo "</form>";
                }
            }
        } elseif ($action == 'checkout') {
            // Check if the attache has already checked out today
            $sql = "SELECT * FROM checkins WHERE attache_id = ? AND DATE(checkin_time) = ? AND checkout_time IS NOT NULL";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("is", $attache_id, $today);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "<script>showAlert('You have already checked out today.');</script>";
            } else {
                // Check if the attache has checked in today and not checked out
                $sql = "SELECT * FROM checkins WHERE attache_id = ? AND DATE(checkin_time) = ? AND checkout_time IS NULL";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("is", $attache_id, $today);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    // attache has checked in today, select a random question for verification
                    $sql = "SELECT question_id FROM answers WHERE attache_id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $attache_id);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    $answered_questions = [];
                    while ($row = $result->fetch_assoc()) {
                        $answered_questions[] = $row['question_id'];
                    }

                    $random_question_id = $answered_questions[array_rand($answered_questions)];

                    $sql = "SELECT question FROM questions WHERE id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $random_question_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $question = $result->fetch_assoc()['question'];

                    echo "<form method='post' action='check_answers.php'>";
                    echo "<label for='question_$random_question_id'>$question</label>";
                    echo "<input type='text' id='question_$random_question_id' name='question_$random_question_id' required autocomplete='off'>";
                    echo "<input type='hidden' name='email' value='$email'>";
                    echo "<input type='hidden' name='action' value='checkout'>";
                    echo "<button type='submit'>Submit</button>";
                    echo "</form>";
                } else {
                    echo "<script>showAlert('You have not checked in today or have already checked out.');</script>";
                }
            }
        }
    } else {
        echo "<script>showAlert('Attache does not exist!');</script>";
    }

    $stmt->close();
}

$conn->close();
?>
</body>
</html>
