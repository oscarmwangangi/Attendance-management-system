<?php
session_start();
require '../dashboard/db_connection.php'; // Include the database connection

// Fetch departments for the dropdown
$departmentOptions = '';
$query = "SELECT name FROM departments";
$result = $conn->query($query);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $departmentOptions .= '<option value="' . htmlspecialchars($row['name']) . '">' . htmlspecialchars($row['name']) . '</option>';
    }
}
$conn->close();
?>



<!doctype html>
<html lang="en">
<head>
    <title>Title</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link rel="stylesheet" href="./register.css">
</head>

<body>
    <header>
        <!-- place navbar here -->
    </header>

    <form method="POST" id="attacheForm" action="insert_attache.php" onsubmit="return validateForm()">
        <?php
        if (isset($_SESSION['success'])) {
            echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
            unset($_SESSION['success']); // Clear the message after displaying it
        }

        if (isset($_SESSION['error'])) {
            echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']); // Clear the message after displaying it
        }
        ?>
        <div class="col-md-2 mb-4">
            <div class="button-container">
                <a href="../index.html" class="btn btn-secondary btn-lg">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
        <div class="container">
            <div class="content">
                <div class="row mb-3">
                    <div class="col-12 col-sm-10 col-md-4">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control capitalize" id="name" required name="name" placeholder="Enter your Name" autocomplete="off">
                    </div>
                    <div class="col-12 col-sm-10 col-md-4">
                        <label for="ID_number" class="form-label">ID Number</label>
                        <input type="number" class="form-control" id="ID_number" required name="ID_number" placeholder="Enter your ID" autocomplete="off">
                    </div>
                    <div class="col-12 col-sm-10 col-md-4">
                        <label for="school" class="form-label">Collage/University</label>
                        <input type="text" class="form-control capitalize" id="school" required name="school" placeholder="Your school" autocomplete="off">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-12 col-sm-10 col-md-4">
                        <label for="course" class="form-label">Course/Cert/Dip/Degree</label>
                        <input type="text" class="form-control capitalize" id="course" required name="course" placeholder="Enter the course" autocomplete="off">
                    </div>
                    <div class="col-12 col-sm-10 col-md-4">
                        <label for="date_joined" class="form-label">Date Joined</label>
                        <input type="date" class="form-control" id="date_joined" required name="date_joined" autocomplete="off">
                    </div>
                    <div class="col-12 col-sm-10 col-md-4">
                        <label for="date_leaving" class="form-label">Date Leaving</label>
                        <input type="date" class="form-control" id="date_leaving" required name="date_leaving" autocomplete="off">
                    </div>
                </div>

                <div class="row mb-3">
                    <!-- <div class="col-12 col-sm-10 col-md-4">
                        <label for="school_supervisor_name" class="form-label">Name of School Supervisor</label>
                        <input type="text" class="form-control capitalize" id="school_supervisor_name" required name="school_supervisor_name" placeholder="school supervisor" autocomplete="off">
                    </div>
                    <div class="col-12 col-sm-10 col-md-4">
                        <label for="school_supervisor_number" class="form-label">Number of School Supervisor</label>
                        <input type="tel" class="form-control" id="school_supervisor_number" required name="school_supervisor_number" placeholder="+254712345678" autocomplete="off">
                    </div>  -->
                     <div class="col-12 col-sm-10 col-md-4">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" required name="email" placeholder="name@example.com" autocomplete="off">
                    </div>
                    <div class="col-12 col-sm-10 col-md-4">
                        <label for="phone_number" class="form-label">Phone Number</label>
                        <input type="number" class="form-control" id="phone_number" required name="phone_number" placeholder="+254712345678" autocomplete="off">
                    </div>
                    <div class="col-12 col-sm-10 col-md-4">
                        <label for="Department" class="form-label">State Department</label>
                        <select class="form-control" id="Department" name="Department" required>
                            <option value="" disabled selected>Select Department</option>
                            <?php echo $departmentOptions; ?>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                  
                   
                    <div class="col-12 col-sm-10 col-md-4">
                        <label for="Supervisor" class="form-label">Supervisor</label>
                        <input type="text" class="form-control capitalize" id="Supervisor" name="Supervisor" required placeholder="Supervisor's name" autocomplete="off">
                    </div>
                </div>
                <div class="row mb-3">
                    
                </div>

                <div class="row mb-3">
                    <div class="col-12">
                        <input type="submit" class="btn btn-primary w-100" id="submit" required name="submit" value="Submit">
                    </div>
                </div>
            </div>
            
        </div>
    </form>

    <footer>
        <!-- place footer here -->
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
    <script>
        function validateForm() {
            var dateJoined = new Date(document.getElementById('date_joined').value);
            var dateLeaving = new Date(document.getElementById('date_leaving').value);
            var today = new Date();
            today.setHours(0, 0, 0, 0);

            if (dateLeaving <= dateJoined) {
                alert("Date Leaving must be greater than Date Joined.");
                return false;
            }

            if (today >= dateLeaving) {
                alert("Date Leaving cannot be today or in the past.");
                return false;
            }

            return confirm('Are you sure you want to submit this record?');
        }

        document.querySelectorAll('.capitalize').forEach(function(element) {
            element.addEventListener('input', function() {
                element.value = element.value.replace(/\b\w+\b/g, function(word) {
                    return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();
                });
            });
        });
    </script>
</body>
</html>
