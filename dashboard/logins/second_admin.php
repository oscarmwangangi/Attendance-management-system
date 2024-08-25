<?php
session_start();
if ($_SESSION['role'] !== 'second_admin') {
    header("Location: ../index.php");
    exit();
}
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $content = $_POST['content'];

    $conn = get_db_connection();
    $stmt = $conn->prepare("INSERT INTO data (user_id, content) VALUES (:user_id, :content)");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':content', $content);
    $stmt->execute();
    
}

$conn = get_db_connection();
$data = $conn->query("SELECT * FROM data WHERE user_id = {$_SESSION['user_id']}")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Page</title>
    <link rel="stylesheet" href="second_admin.css">
</head>
<body>

<div class="form">
    <div class="heading">
        <h2> Admin Dashboard</h2>
    </div>
        <div class="register">
        <a href="register.php">Register New User</a>
        </div>
      <div>
          <a href="./log/fill.php">Data Entry</a>
      </div>
        <div>
          <form method="POST" action="logout.php">    
        <input type="submit" value="Logout">
        </form>
      </div>
     
    <!-- <h3>Enter Data</h3>
     <form method="POST" action="">
        <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
        <label for="content">Content:</label><br>
        <textarea id="content" name="content" required></textarea><br><br>
        <input type="submit" value="Submit">
    </form> -->

    <!-- <h3>Your Data</h3> --> 
    <!-- <ul>
        <?php foreach ($data as $row) : ?>
            <li><?php echo htmlspecialchars($row['content']); ?></li>
        <?php endforeach; ?> -->
    </ul>
</div>
</body>
</html>
