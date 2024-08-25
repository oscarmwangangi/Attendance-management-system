
<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['role'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.3.2 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link rel="stylesheet" href="style.css">
    
    <script src="script.js"></script>

</head>

<body>
    
    <header>
        <!-- place navbar here -->
    </header>
    <div class="header">
        <img src="../images/user.png" alt=" logo" class="user">Admin
        </div>
        <div>
                <form method="POST" action="index.php">
              <input type="submit" value="Logout" id="logout">
              </form>
             
        </div>
    </div>
    
    <div class="sidebar">
        <div class="admin1">
            <img src="../images/admin.png" alt="admin icon" class="admin">
            <p class="admin2">Admin</p>
        </div>
        <div class="side-content">
            <div class="Dashbord" onclick="showContent('Dashboard')">Dashboard 
                <img src="../images/dashboard.png" alt="dashboard image" class="icons">
                <hr>
            </div>
           <div class="Department" onclick="showContent('Department')">Department
                <img src="../images/department.png" alt="department image" class="icons">
            </div> 
            <div class="Attachies" onclick="showContent('Attachies')">Attachies
                <img src="../images/attachie.png" alt="attachee icon" class="icons">
            </div>
            <!-- <div class="logins" onclick="showContent('Logins')">Logins
                <img src="../images/logins.png" alt="login icon" class="icons">
            </div> -->
           
            <div class="Print_report" onclick="showContent('Print report')"> <hr>Print report
                <img src="../images/print.png" alt="print icon" class="icons" >
                <hr>
            </div>
            <div class="register" > 
                <a href="./logins/register.php" class="register">Register</a>
                <img src="../images/register.png" alt="attachee icon" class="icons">
                
            </div>
            <div class="updates" onclick="showContent('updates')">Updates
            <img src="../images/update.png" alt="attachee icon" class="icons">
            </div>
        </div>
    </div>
    <div class="sideview" id="sideview">
        <h1>Welcome to ICT attachie registration</h1>
        <!-- Content will be displayed here -->
        <h2>Search for Attache</h2>
    <form method="post" action="search/search.php">
        <label for="ID_number">ID_number</label>
        <input type="text" id="ID_number" name="ID_number" required autocomplete="off">
        <input type="submit" value="Search">
    </form>
    <div id="resultsContainer">
        <!-- Results will be displayed here -->
    </div> 
    <!-- for the search box -->
  
    </div>

    <!-- Content Template -->
    <div id="content-template" style="display: none;">
        <div class="display-area">
            
            <div class="container">
                <div class="content">
                    <!-- Your content here -->
                    
                </div>
            </div>
        </div>
    </div>
   
    <script>
        function showContent(contentName) {
            var sideview = document.getElementById('sideview');
            var contentTemplate = document.getElementById('content-template').innerHTML;
            var content = '';

            switch(contentName) {
                 // what appears when you click dashboard
                case 'Dashboard':
                    content = `
                        
                        <div class="container">
                        <?php
                            $servername = "localhost";
                            $username = "root"; // Replace with your database username
                            $password = ""; // Replace with your database password
                            $dbname = "attache_management";

                            // Create connection
                            $conn = new mysqli($servername, $username, $password, $dbname);

                            // Check connection
                            if ($conn->connect_error) {
                                die("Connection failed: " . $conn->connect_error);
                            }

                            // Fetch attachés who have not been verified yet
                            $sql = "SELECT id, name, ID_number, email, Supervisor, Department FROM attache WHERE verified_at IS NULL";
                            $result = $conn->query($sql);
                            ?>
                        </div>

                        <!DOCTYPE html>
                        <html lang="en">
                        <head>
                            <meta charset="UTF-8">
                            <title>Admin Dashboard</title>
                            

                        </head>
                        <body>

                            <h1>Admin Dashboard</h1>
                            <table >
                                <tr>
                                    <th>Name</th>
                                    <th>ID Number</th>
                                    <th>Email</th>
                                    <th>Supervisor</th>
                                    <th>Department</th>
                                    <th>Actions</th>
                                </tr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['ID_number']) . "</td>";
                echo "<td>" . htmlspecialchars($row['email']) . "</td>"; 
                echo "<td>" . htmlspecialchars($row['Supervisor']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Department']) . "</td>";
              
                echo "<td>";
                echo "<form action='verify_attache.php' method='post' style='display:inline;'>";
                echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";
                echo "<input type='submit' name='action' value='Verify' style='background-color:lightgreen; border:none; border-radius:10px; height:30px; width:80px;'>";
                echo "</form> ";
                echo "<form action='reject_attache.php' method='post' style='display:inline;'>";
                echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";
                echo "<input type='submit' name='action' value='Reject' style='background-color:red; border:none; color:white; border-radius:10px; height:30px; width:80px;'>";
                echo "</form>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No attachés awaiting verification</td></tr>";
        }
        ?>
    </table>
</body>
</html>
 
<?php
$conn->close();
?>

                          
                    `;
                     // what appears when you click attachie
                    break;
                case 'Attachies':
                    content = `
                       
                                <?php
                                date_default_timezone_set('Africa/Nairobi');
                                $servername = "localhost";
                                $username = "root"; // Replace with your database username
                                $password = ""; // Replace with your database password
                                $dbname = "attache_management";

                                // Create connection
                                $conn = new mysqli($servername, $username, $password, $dbname);

                                // Check connection
                                if ($conn->connect_error) {
                                    die("Connection failed: " . $conn->connect_error);
                                }

                                // Check if current_date and date_comparison parameters are set
                                $currentDate = isset($_GET['current_date']) ? $_GET['current_date'] : date('Y-m-d');
                                $dateComparison = isset($_GET['date_comparison']) ? $_GET['date_comparison'] : '>';

                                // Validate date_comparison to be either '<' or '>'
                                if ($dateComparison !== '<' && $dateComparison !== '>') {
                                    die("Invalid date comparison operator.");
                                }

                                // SQL query based on date comparison
                                $sql = "SELECT name, ID_number, school, course, date_joined, date_leaving, email, phone_number, Department, Supervisor
                                        FROM attache
                                        WHERE date_leaving $dateComparison ?";

                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("s", $currentDate);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                ?>
                                <!DOCTYPE html>
                                <html>
                                <head>
                                    <title>Attache Table</title>
                                    <style>
                                        table {
                                            font-size: 12px;
                                            width: 100%;
                                            border-collapse: collapse;
                                        }
                                        table, th, td {
                                            border: 1px solid black;
                                        }
                                        th, td {
                                            padding: 10px;
                                            text-align: left;
                                        }
                                        th {
                                            background-color: #f2f2f2;
                                        }
                                        .pprint {
                                            margin-top: 10px;
                                            box-shadow: 20px;
                                            padding: 4px;
                                            border-left: solid #f0f0f0;
                                            border-top: solid #f0f0f0;
                                            border-bottom: solid #6c757d;
                                            border-right: solid #6c757d;
                                        }
                                        button {
                                            box-shadow: 20px;
                                            padding: 4px;
                                            border-left: solid #f0f0f0;
                                            border-top: solid #f0f0f0;
                                            border-bottom: solid #6c757d;
                                            border-right: solid #6c757d;
                                        }
                                        button:hover {
                                            background-color: #dee2e6;
                                            cursor: pointer;
                                        }
                                    </style>
                                </head>
                                <body>
                                    <h2>Attachee Table</h2>
                                    <button onclick="showOngoing()">Show Ongoing</button>
                                    <button onclick="showFinished()">Show Finished</button>
                                    <button onclick="printTableDiv()" class="pprint">Print Table</button>
                                    <div id="attacheTable">
                                        <table>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>ID Number</th>
                                                <th>School</th>
                                                <th>Course</th>
                                                <th>Date Joined</th>
                                                <th>Date Leaving</th>
                                                <th>Email</th>
                                                <th>Phone Number</th>
                                                <th>Department</th>
                                                <th>Supervisor</th>
                                            </tr>
                                            <?php
                                            if ($result->num_rows > 0) {
                                                $count = 1;
                                                // Output data of each row
                                                while($row = $result->fetch_assoc()) {
                                                    echo "<tr>
                                                        <td>{$count}</td>
                                                        <td>{$row['name']}</td>
                                                        <td>{$row['ID_number']}</td>
                                                        <td>{$row['school']}</td>
                                                        <td>{$row['course']}</td>
                                                        <td>{$row['date_joined']}</td>
                                                        <td>{$row['date_leaving']}</td>
                                                        <td>{$row['email']}</td>
                                                        <td>{$row['phone_number']}</td>
                                                        <td>{$row['Department']}</td>
                                                        <td>{$row['Supervisor']}</td>
                                                    </tr>";
                                                    $count++;
                                                }
                                            } else {
                                                echo "<tr><td colspan='11'>No record found</td></tr>";
                                            }

                                            $stmt->close();
                                            $conn->close();
                                            ?>
                                        </table>
                                    </div>
                                </body>
                                </html>




                                            `;
                    // what appears when you click Department
                    break;
                case 'Department':
                    content = `
                            <?php

                            require '../dashboard/db_connection.php'; // Adjust the path as necessary

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
                                                
                                                                        <!DOCTYPE html>
                            <html lang="en">
                            <head>
                                <meta charset="UTF-8">
                                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                                <title>Department Data</title>
                                <link rel="stylesheet" href="./styles.css">
                            </head>
                            <body>
                                <style>
                                    table {
                                        font-size: 12px;
                                    }
                                    button {
                                        box-shadow: 20px;
                                        padding: 4px;
                                        border-left: solid #f0f0f0;
                                        border-top: solid #f0f0f0;
                                        border-bottom: solid #6c757d;
                                        border-right: solid #6c757d;
                                    }
                                    button:hover {
                                        background-color: #dee2e6;
                                        cursor: pointer;
                                    }
                                </style>
                                <div class="container">
                                    <h1>Select State Department</h1>
                                    <select id="department" name="department">
                                        <!-- Options will be populated here by JavaScript -->
                                        <option value="" disabled selected>Select Department</option>
                                        <?php echo $departmentOptions; ?>
                                    </select>
                                    <button onclick="fetchData()">Show Data</button>
                                </div>
                                <div id="table-container"></div>
                                <button onclick="printTableContainer()">Print</button><br>

                                
                                <input type="text" id="new-department" placeholder="New Department"  name="New Department" required autocomplete="off">
                                <button onclick="addDepartment()">Add Department</button>

                                <h4>Manage Departments</h4>
                                <table id="departments-table">
                                    <thead>
                                        <tr>
                                            <th>Department</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Departments will be populated here -->
                                    </tbody>
                                </table>
                            </body>
                            </html>



                    `;
                    break;
                case 'Print report':
                    content = `
                    <?php

                    require '../dashboard/db_connection.php'; // Adjust the path as necessary

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

                        <!DOCTYPE html>
                        <html lang="en">
                        <head>
                            <meta charset="UTF-8">
                            <meta name="viewport" ="width=device-width, initial-scale=1.0">
                            <title>Check-ins Report</title>
                            <style>
                                body {
                                    font-family: Arial, sans-serif;
                                }
                                table {
                                    width: 100%;
                                    border-collapse: collapse;
                                }
                                th, td {
                                    border: 1px solid black;
                                    padding: 8px;
                                    text-align: left;
                                }
                                th {
                                    background-color: #f2f2f2;
                                }
                                button {
                                    box-shadow: 20px;
                                    padding: 4px;
                                    border-left: solid #f0f0f0;
                                    border-top: solid #f0f0f0;
                                    border-bottom: solid #6c757d;
                                    border-right: solid #6c757d;
                                }
                                button:hover {
                                    background-color: #dee2e6;
                                    cursor: pointer;
                                }
                                @media print {
                                    body * {
                                        visibility: hidden;
                                    }
                                    .printableArea, .printableArea * {
                                        visibility: visible;
                                    }
                                    .printableArea {
                                        position: absolute;
                                        left: 0;
                                        top: 0;
                                    }
                                }
                            </style>
                        </head>
                        <body>
                            <h1>Check-ins Report</h1>
                            <form id="reportForm" method="GET" onsubmit="getReport(event)">
                                <label for="department">Select Department:</label>
                                <select id="department" name="department" required>
                                    <option value="" disabled selected>Select Department</option>
                                    <?php echo $departmentOptions; ?>
                                </select>
                                <br><br>
                                <label for="start_date">Start Date:</label>
                                <input type="date" id="start_date" name="start_date" required>
                                <br><br>
                                <label for="end_date">End Date:</label>
                                <input type="date" id="end_date" name="end_date" required>
                                <br><br>
                                <button type="submit">Get Report</button>
                            </form>

                            <!-- Report area -->
                            <div id="report" class="printableArea">
                                <!-- Report will be displayed here -->
                            </div>

                            <br>
                            <button onclick="window.print()">Print Report</button>

                            
                                
                        
                        </body>
                        </html>




                    `;
                     
                    break;
                    case 'Register':
                    content = `
                        
                     `; 
                     case 'updates':
                    content = `
                           <?php

require './config.php'; // Include the database connection

// Fetch departments for the dropdown
$departmentOptions = '';
$query = "SELECT name FROM departments";
$stmt = $pdo->prepare($query);
$stmt->execute();
$departments = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($departments as $row) {
    $departmentOptions .= '<option value="' . htmlspecialchars($row['name']) . '">' . htmlspecialchars($row['name']) . '</option>';
}

// Fetch existing data if ID is set
$data = [];
if (isset($_GET['ID_number'])) {
    $ID_number = $_GET['ID_number'];
    $query = "SELECT * FROM attache WHERE ID_number = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$ID_number]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
}

?>

<!doctype html>
<html lang="en">
<head>
    <title>Title</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
   
</head>

<body>
    <header>
        <!-- place navbar here -->
    </header>

    <form method="POST" id="attacheForm" action="insert.php" onsubmit="return validateForm()">
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
        
        <div class="container">
            <div class="content">
                <!-- Form fields -->
                <div class="row mb-3">
                    <div class="col-12 col-sm-10 col-md-4">
                        <label for="searchID" class="form-label">Search by ID Number</label>
                        <input type="number" class="form-control" id="searchID" placeholder="Enter your ID" autocomplete="off"></div>
                        <div class="col-12 col-sm-10 col-md-4">
                        <button type="button" class="btn btn-primary searchbutton"  onclick="searchEntry()">Search</button>
                         </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12 col-sm-10 col-md-4">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control capitalize" id="name" required name="name" placeholder="Enter Name" autocomplete="off" value="<?php echo htmlspecialchars($data['name'] ?? ''); ?>">
                    </div>
                    <div class="col-12 col-sm-10 col-md-4">
                        <label for="ID_number" class="form-label">ID Number</label>
                        <input type="number" class="form-control" id="ID_number" required name="ID_number" placeholder="Enter ID" autocomplete="off" value="<?php echo htmlspecialchars($data['ID_number'] ?? ''); ?>" <?php echo isset($data['ID_number']) ? 'readonly' : ''; ?>>
                    </div>
                    <div class="col-12 col-sm-10 col-md-4">
                        <label for="school" class="form-label">College/University</label>
                        <input type="text" class="form-control capitalize" id="school" required name="school" placeholder="school" autocomplete="off" value="<?php echo htmlspecialchars($data['school'] ?? ''); ?>">
                    </div>
                </div>
                <!-- Other fields here -->
                <div class="row mb-3">
                    <div class="col-12 col-sm-10 col-md-4">
                        <label for="course" class="form-label">Course/Cert/Dip/Degree</label>
                        <input type="text" class="form-control capitalize" id="course" required name="course" placeholder="Enter course" autocomplete="off" value="<?php echo htmlspecialchars($data['course'] ?? ''); ?>">
                    </div>
                    <div class="col-12 col-sm-10 col-md-4">
                        <label for="date_joined" class="form-label">Date Joined</label>
                        <input type="date" class="form-control" id="date_joined" required name="date_joined" autocomplete="off" value="<?php echo htmlspecialchars($data['date_joined'] ?? ''); ?>">
                    </div>
                    <div class="col-12 col-sm-10 col-md-4">
                        <label for="date_leaving" class="form-label">Date Leaving</label>
                        <input type="date" class="form-control" id="date_leaving" required name="date_leaving" autocomplete="off" value="<?php echo htmlspecialchars($data['date_leaving'] ?? ''); ?>">
                    </div>
                </div>
                <!-- Other fields here -->
                <div class="row mb-3">
                    
                    <div class="col-12 col-sm-10 col-md-4">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" required name="email" placeholder="name@example.com" autocomplete="off" value="<?php echo htmlspecialchars($data['email'] ?? ''); ?>">
                    </div>
                    <div class="col-12 col-sm-10 col-md-4">
                        <label for="phone_number" class="form-label">Phone Number</label>
                        <input type="number" class="form-control" id="phone_number" required name="phone_number" placeholder="+254712345678" autocomplete="off" value="<?php echo htmlspecialchars($data['phone_number'] ?? ''); ?>">
                    </div>
                    <div class="col-12 col-sm-10 col-md-4">
                        <label for="Department" class="form-label">Department</label>
                        <select class="form-control" id="Department" name="Department" required>
                            <option value="" disabled>Select Department</option>
                            <?php echo $departmentOptions; ?>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    
                    <div class="col-12 col-sm-10 col-md-4">
                        <label for="Supervisor" class="form-label">Supervisor</label>
                        <input type="text" class="form-control capitalize" id="Supervisor" name="Supervisor" required placeholder="Supervisor's name" autocomplete="off" value="<?php echo htmlspecialchars($data['Supervisor'] ?? ''); ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <input type="submit" class="btn btn-dark w-100" id="submit" required name="submit" value="Submit">
                    </div>
                </div>
            </div>
        </div>
    </form>

    <footer>
        <!-- place footer here -->
    </footer>
    
   
</body>
</html>

                    `;
                    
            }

            sideview.innerHTML = contentTemplate.replace('<!-- Your content here -->', content);
        }
    </script>
</body>
</html>