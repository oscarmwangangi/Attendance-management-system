<!-- search bar that update the data for attachee -->
<?php
session_start();
require './config.php'; // Include the database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $ID_number = $_POST['ID_number'];
    $school = $_POST['school'];
    $course = $_POST['course'];
    $date_joined = $_POST['date_joined'];
    $date_leaving = $_POST['date_leaving'];
    // $school_supervisor_name = $_POST['school_supervisor_name'];
    // $school_supervisor_number = $_POST['school_supervisor_number'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $Department = $_POST['Department'];
    $Supervisor = $_POST['Supervisor'];

    // Check if the record exists
    $query = "SELECT ID_number FROM attache WHERE ID_number = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$ID_number]);
    $exists = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($exists) {
        // Update existing record
        $query = "UPDATE attache SET name = ?, school = ?, course = ?, date_joined = ?, date_leaving = ?, email = ?, phone_number = ?, Department = ?, Supervisor = ? WHERE ID_number = ?";
        $stmt = $pdo->prepare($query);
        $result = $stmt->execute([$name, $school, $course, $date_joined, $date_leaving, $email, $phone_number, $Department, $Supervisor, $ID_number]);

        if ($result) {
            $_SESSION['success'] = 'Record updated successfully';
        } else {
            $_SESSION['error'] = 'Failed to update record';
        }
    } else {
        // Insert new record
        $query = "INSERT INTO attache (ID_number, name, school, course, date_joined, date_leaving, email, phone_number, Department, Supervisor) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ? )";
        $stmt = $pdo->prepare($query);
        $result = $stmt->execute([$ID_number, $name, $school, $course, $date_joined, $date_leaving, $email, $phone_number, $Department, $Supervisor]);

        if ($result) {
            $_SESSION['success'] = 'Record added successfully';
        } else {
            $_SESSION['error'] = 'Failed to add record';
        }
    }
    
    header('Location: admin_dasboard.php');
    exit();
}
