 <?php 
// Include PHPMailer classes at the top of the file
// require 'C:\wamp64\www\attendance managemnt\PHPMailer 6.9.1 source code\PHPMailer-PHPMailer-2128d99\src\Exception.php';
// require 'C:\wamp64\www\attendance managemnt\PHPMailer 6.9.1 source code\PHPMailer-PHPMailer-2128d99\src\PHPMailer.php';
// require 'C:\wamp64\www\attendance managemnt\PHPMailer 6.9.1 source code\PHPMailer-PHPMailer-2128d99\src\SMTP.php';

// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;

// $servername = "localhost";
// $username = "root"; // Replace with your database username
// $password = ""; // Replace with your database password
// $dbname = "attache_management";

// // Create connection
// $conn = new mysqli($servername, $username, $password, $dbname);

// // Check connection
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $name = $_POST['name'];
//     $ID_number = $_POST['ID_number'];
//     $school = $_POST['school'];
//     $course = $_POST['course'];
//     $date_joined = $_POST['date_joined'];
//     $date_leaving = $_POST['date_leaving'];
//     $date_of_birth = $_POST['date_of_birth'];
//     $email = $_POST['email'];
//     $phone_number = $_POST['phone_number'];
//     $Department = isset($_POST['Department']) ? $_POST['Department'] : '';
//     $Supervisor = $_POST['Supervisor'];

//     // Check if ID_number already exists
//     $stmt_check_id = $conn->prepare("SELECT ID_number FROM attache WHERE ID_number = ?");
//     $stmt_check_id->bind_param("s", $ID_number);
//     $stmt_check_id->execute();
//     $stmt_check_id->store_result();
    
//     if ($stmt_check_id->num_rows > 0) {
//         echo "<script>alert('Error: ID number already exists'); window.location.href='register.php';</script>";
//     } else {
//         // Check if email already exists
//         $stmt_check_email = $conn->prepare("SELECT email FROM attache WHERE email = ?");
//         $stmt_check_email->bind_param("s", $email);
//         $stmt_check_email->execute();
//         $stmt_check_email->store_result();
        
//         if ($stmt_check_email->num_rows > 0) {
//             echo "<script>alert('Error: Email already exists'); window.location.href='register.php';</script>";
//         } else {
//             // Insert data into database
//             $stmt = $conn->prepare("INSERT INTO attache (name, ID_number, school, course, date_joined, date_leaving, date_of_birth, email, phone_number, Department, Supervisor) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
//             if ($stmt) {
//                 $stmt->bind_param("sssssssssss", $name, $ID_number, $school, $course, $date_joined, $date_leaving, $date_of_birth, $email, $phone_number, $Department, $Supervisor);
//                 if ($stmt->execute()) {
//                     // Send email notification using PHPMailer
//                     $mail = new PHPMailer(true);
//                     try {
//                         // Server settings
//                         $mail->SMTPDebug = 2; // Enable verbose debug output
//                         $mail->isSMTP();
//                         $mail->Host = 'smtp.gmail.com';
//                         $mail->SMTPAuth = true;
//                         $mail->Username = 'mwangangioscar2016@gmail.com';
//                         $mail->Password = '8964mutura';
//                         $mail->SMTPSecure = 'tls';
//                         $mail->Port = 587;

//                         // Recipients
//                         $mail->setFrom('your-email@gmail.com', 'Mailer');
//                         $mail->addAddress($email, $name);

//                         // Content
//                         $mail->isHTML(true);
//                         $mail->Subject = 'Submission Received - Awaiting Verification';
//                         $mail->Body = "Hello $name,<br><br>Your submission has been received and is awaiting verification by the admin.<br><br>Thank you.";
                        
//                         $mail->send();
//                         echo "<script>alert('Submission successful. A notification email has been sent.'); window.location.href='register.php';</script>";
//                     } catch (Exception $e) {
//                         echo "<script>alert('Submission successful, but failed to send email notification. Mailer Error: {$mail->ErrorInfo}'); window.location.href='register.php';</script>";
//                     }
//                 } else {
//                     echo "<script>alert('Error: " . $stmt->error . "'); window.location.href='register.php';</script>";
//                 }
//                 $stmt->close();
//             } else {
//                 echo "<script>alert('Prepare failed: " . $conn->error . "'); window.location.href='register.php';</script>";
//             }
//         }
//         $stmt_check_email->close();
//     }
//     $stmt_check_id->close();
// } else {
//     echo "<script>alert('Invalid request.'); window.location.href='register.php';</script>";
// }

// $conn->close();
?> 
