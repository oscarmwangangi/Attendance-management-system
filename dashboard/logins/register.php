<?php
session_start();

if ($_SESSION['role'] !== 'main_admin' && $_SESSION['role'] !== 'second_admin') {
    header("Location: ../index.php");
    exit();
}

require 'db.php';

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : null;
    $password = isset($_POST['password']) ? trim($_POST['password']) : null;
    $role = isset($_POST['role']) ? trim($_POST['role']) : null;

    // Validate form data
    if ($username && $password && $role) {
        try {
            $conn = get_db_connection();

            // Check if username already exists
            $check_stmt = $conn->prepare("SELECT COUNT(*) FROM users_attachee WHERE username = :username");
            $check_stmt->bindParam(':username', $username);
            $check_stmt->execute();
            $count = $check_stmt->fetchColumn();

            if ($count > 0) {
                $message = "Username already exists. Please choose a different username.";
                $message_type = "error";
            } else {
                // Proceed to register user
                $password_hash = password_hash($password, PASSWORD_BCRYPT);
                $stmt = $conn->prepare("INSERT INTO users_attachee (username, password, role) VALUES (:username, :password, :role)");
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':password', $password_hash);
                $stmt->bindParam(':role', $role);
                $stmt->execute();

                $message = "User registered successfully.";
                $message_type = "success";
            }
        } catch (PDOException $e) {
            $message = "Failed to register " . $e->getMessage();
            $message_type = "error";
        }
    } else {
        $message = "All fields are required.";
        $message_type = "error";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register User</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="register_first_user.css">
</head>
<body>
<div class="button-container">
                <a href="../admin_dasboard.php" class="btn btn-secondary btn-lg">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
    <div class="body">
    <div class="form">
        <h2>Register Admin</h2>
        <form method="POST" action="">
            <div class="username">
                <label for="username">UserName:</label><br>
                <input type="text" id="username" name="username" required autocomplete="off"><br><br>
            </div>
            <div class="password">
                <label for="password">Password:</label><br>
                <input type="password" id="password" name="password" required autocomplete="off"><br><br>
            </div>
            <div class="role">
                <label for="role">Role:</label><br>
                <select id="role" name="role">
                    <?php if ($_SESSION['role'] == 'main_admin'): ?>
                        <option value="main_admin">Main Admin</option>
                    <!-- <?php endif; ?>
                    <option value="second_admin">Second Admin</option>
                    <option value="user">User</option> -->
                </select><br><br>
            </div>
            <div class="login">
                <input type="submit" value="Register">
            </div>
        </form>
    </div>

    <!-- Bootstrap Modal -->
    <div class="modal fade" id="messageModal" tabindex="-1" role="dialog" aria-labelledby="messageModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="messageModalLabel"><?php echo ucfirst($message_type); ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php echo $message; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        <?php if ($message): ?>
            $(document).ready(function() {
                $('#messageModal').modal('show');
            });
        <?php endif; ?>
    </script>
</body>
</html>
