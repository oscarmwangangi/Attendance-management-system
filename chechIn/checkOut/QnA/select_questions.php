<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Questions</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./select_questions.css">
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
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['questions']) && count($_POST['questions']) == 5) {
        $questions = $_POST['questions'];
        $email = strtolower($_POST['email']);  // Convert email to lowercase

        echo "<form method='post' action='save_answers.php'>";
        foreach ($questions as $question_id) {
            // Fetch the question text from the database
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "attache_management";

            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                echo "<script>showAlert('Connection failed: " . $conn->connect_error . "');</script>";
                exit();
            }

            $sql = "SELECT question FROM questions WHERE id = ?";
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                echo "<script>showAlert('Prepare failed: " . $conn->error . "');</script>";
                exit();
            }
            $stmt->bind_param("i", $question_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $question_text = $result->fetch_assoc()['question'];

            echo "<input type='hidden' name='questions[]' value='$question_id' autocomplete='off'>";
            echo "<label for='question_$question_id'>$question_text:</label>";
            echo "<input type='text' id='question_$question_id' name='answers[$question_id]' required autocomplete='off'>";
            echo "<br>";
        }
        echo "<input type='hidden' name='email' value='$email'>";
        echo "<button type='submit'>Submit</button>";
        echo "</form>";
    } else {
        echo "<script>showAlert('Please select exactly 5 questions.');</script>";
    }
}
?>
</body>
</html>
