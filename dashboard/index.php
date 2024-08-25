<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    session_destroy();
    header("Location: index.php");
    exit();
}

require './logins/db.php';

function is_first_user() {
    $conn = get_db_connection();
    $stmt = $conn->query("SELECT * FROM users_attachee");
    $user = $stmt->fetch();
    return $user === false;
}

$is_first_user = is_first_user();

if (isset($_SESSION['role'])) {
    switch ($_SESSION['role']) {
        case 'main_admin':
            header("Location: ./admin_dasboard.php");
            exit();
        case 'second_admin':
            header("Location: ./admin_dasboard.php");
            exit();
        case 'user':
            header("Location: ./admin_dasboard.php");
            exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $is_first_user ? 'Register' : 'Login'; ?> Page</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <div class="form">
    <h2><?php echo $is_first_user ? 'Register' : 'Login'; ?></h2>
    
    <?php if ($is_first_user): ?>
        <form method="POST" action="./logins/register_first_user.php">
           <div class="username"> 
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username" required placeholder="username" autocomplete="off"><br><br>
        </div>
        <div class="password">
            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required autocomplete="off" placeholder="password"><br><br>
        </div>
            <div class="login">
                <input type="submit" value="Register">
            </div>
        
        </form>
    <?php else: ?>
        <form method="POST" action="./logins/login.php">
          <div class="username">
            <label for="username">UserName</label><br>
            <input type="text" id="username" name="username" required placeholder="username" autocomplete="off"><br><br>
        </div>
        <div class="password">
            <label for="password">Password</label><br>
            <input type="password" id="password" name="password" required placeholder="password" autocomplete="off"><br><br>
        </div>
            <div class="login">
                <input type="submit" value="Login">
            </div>
            
        </form>
    <?php endif; ?>
</div>
</body>
</html>
