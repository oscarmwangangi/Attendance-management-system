
<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $conn = get_db_connection();
    $stmt = $conn->prepare("SELECT * FROM users_attachee WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];

        switch ($user['role']) {
            case 'main_admin':
                header("Location: ../admin_dasboard.php");
                break;
            case 'second_admin':
                header("Location: ../admin_dasboard.php");
                break;
            case 'user':
                header("Location: ../admin_dasboard.php");
                break;
        }
        exit();
    } else {
        echo "Invalid username or password: please try again";
    }
}
?>
