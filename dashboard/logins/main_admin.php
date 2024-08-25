<?php
session_start();
if ($_SESSION['role'] !== 'main_admin') {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Main Admin Page</title>
    <link rel="stylesheet" href="main_admin.css">
</head>
<body>
     
<div class="form">
    <!-- adding new user -->
   <div class="heading"><h2><marquee>Main Admin Dashboard</marquee> </h2><br></div>
   <div class="form-group">
    <div class="register">
        
            <a href="register.php">Register New User</a>
        </div>
             <div>
                <form method="POST" action="logout.php">
              <input type="submit" value="Logout">
              </form>
        </div>
    


        <div class="roles">
            <a href="">Change roles</a>
        </div>
    </div>
</div>
</body>
</html>
