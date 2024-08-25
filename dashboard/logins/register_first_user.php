<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = 'main_admin'; // First user is always main admin
    
    $conn = get_db_connection();
    $stmt = $conn->prepare("INSERT INTO users_attachee (username, password, role) VALUES (:username, :password, :role)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':role', $role);
    $stmt->execute();

    session_start();
    $_SESSION['user_id'] = $conn->lastInsertId();
    $_SESSION['role'] = $role;

    header("Location: ../admin_dasboard.php");
}
?>
