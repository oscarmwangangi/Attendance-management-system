// fetchData that appears when you click department
// function fetchData() {
//     var department = document.getElementById('department').value;

//     // Send an AJAX request to fetch attachies for the selected department and status=ongoing
//     fetchAttachies(department, 'ongoing');
// }
function fetchData() {
    var department = document.getElementById('department').value;
    var currentDate = new Date().toISOString().split('T')[0]; // Get the current date in YYYY-MM-DD format

    // Send an AJAX request to fetch attache for the selected department where date_leaving > current_date
    fetchAttachies(department, currentDate);
}

function fetchAttachies(department, currentDate) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'your_backend_endpoint?department=' + encodeURIComponent(department) + '&current_date=' + encodeURIComponent(currentDate), true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            // Process the response here
            var response = xhr.responseText;
            document.getElementById('results').innerHTML = response;
        }
    };
    xhr.send();
}


function fetchAttachies(department, status) {
    // Create a new XMLHttpRequest object
    var xhr = new XMLHttpRequest();

    // Configure the request
    xhr.open('GET', 'fetch_data.php?department=' + department, true);

    // Setup onload function to handle response
    xhr.onload = function() {
        if (xhr.status == 200) {
            // Update the HTML content of table-container div
            document.getElementById('table-container').innerHTML = xhr.responseText;
        } else {
            // Handle error
            alert('Error fetching data: ' + xhr.statusText);
        }
    };

    // Send the request
    xhr.send();
}

// prints the department data
function printTableContainer() {
    const tableContainer = document.getElementById('table-container').innerHTML;
    const printWindow = window.open('', '', 'width=800,height=600');
    printWindow.document.write(`
        <html>
        <head>
            <title>Print</title>
            <style>
                table {
                    width: 100%;
                    border-collapse: collapse;
                }
                table, th, td {
                    border: 1px solid black;
                }
                th, td {
                    padding: 15px;
                    text-align: left;
                }
                th {
                    background-color: #f2f2f2;
                }
            </style>
        </head>
        <body>
            ${tableContainer}
        </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
    printWindow.close();
}

// displays print report data, when you set the date
function getReport(event) {
    event.preventDefault();
    const form = document.getElementById('reportForm');
    const formData = new FormData(form);

    fetch('display_checkins.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        document.getElementById('report').innerHTML = data;
    })
    .catch(error => console.error('Error:', error));
}

// fetch data when you click atachee
function showFinished() {
    var currentDate = new Date().toISOString().split('T')[0]; // Get the current date in YYYY-MM-DD format
    // Send an AJAX request to fetch attache where date_leaving <= current_date
    fetchTableContent(currentDate, '<=');
}

function showOngoing() {
    var currentDate = new Date().toISOString().split('T')[0]; // Get the current date in YYYY-MM-DD format
    // Send an AJAX request to fetch attache where date_leaving > current_date
    fetchTableContent(currentDate, '>');
}

function fetchTableContent(currentDate, dateComparison) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'fetch_attachies.php?date_comparison=' + encodeURIComponent(dateComparison) + '&current_date=' + encodeURIComponent(currentDate), true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            // Process the response here
            document.getElementById('attacheTable').innerHTML = xhr.responseText;
        } else if (xhr.readyState == 4) {
            console.error('Error fetching data: ' + xhr.status + ' ' + xhr.statusText);
        }
    };
    xhr.send();
}


// prints the attachie data
function printTableDiv() {
    var divToPrint = document.getElementById('attacheTable');
    var newWin = window.open('', 'Print-Window');
    newWin.document.open();
    newWin.document.write(`
        <html>
        <head>
            <title>Print</title>
            <style>
                table {
                    width: 100%; 
                    border-collapse: collapse;
                } 
                table, th, td {
                    border: 1px solid black;
                }
                th, td {
                    padding: 15px; 
                    text-align: left;
                }
                th {
                    background-color: #f2f2f2;
                }
            </style>
            </head>
            <body>
                ${divToPrint.outerHTML}
            </body>
            </html>
        `);
        newWin.document.close();
        newWin.focus();
        newWin.onload = function() {
            newWin.print();
            newWin.close();
        };
    }

// script.js

function fetchDepartments() {
    fetch('fetch_departments.php')
        .then(response => response.json())
        .then(departments => {
            const selectElements = document.querySelectorAll('select[name="department"]');
            const tableBody = document.querySelector('#departments-table tbody');

            selectElements.forEach(selectElement => {
                selectElement.innerHTML = '<option value="" disabled selected>Select Department</option>';

                departments.forEach(department => {
                    const option = document.createElement('option');
                    option.value = department.name;
                    option.textContent = department.name;
                    selectElement.appendChild(option);
                });
            });

            if (tableBody) {
                tableBody.innerHTML = '';
                departments.forEach(department => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${department.name}</td>
                        <td><button onclick="deleteDepartment('${department.id}')">Delete</button></td>
                    `;
                    tableBody.appendChild(row);
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function deleteDepartment(departmentId) {
    fetch('delete_department.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({
            'id': departmentId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert(data.message);
            fetchDepartments();
        } else {
            alert('Failed to delete department: ' + data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}
// function addDepartmenrequired() {
//     const inputField = document.getElementById('new-department');
//     const errorMessage = document.getElementById('error-message');

//     if (inputField.value.trim() === '') {
//         // Show error message
//         errorMessage.textContent = 'Please fill out this field.';
//         return false;
//     } else {
//         // Clear error message and proceed with the actual logic
//         errorMessage.textContent = '';
//         // Place your code to add department here

//         // For demonstration, just showing the department name
//         console.log('Department to add:', inputField.value);

//         // Optionally, you might want to clear the input field after adding the department
//         inputField.value = '';
//     }
// }

function addDepartment() {
    var departmentName = document.getElementById('new-department').value;

    fetch('add_department.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({
            'department': departmentName
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert(data.message);
            fetchDepartments();
        } else {
            alert('Failed to add department: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}


// Fetch departments when the page loads
window.onload = fetchDepartments;

// Function to search for an entry by ID number
function searchEntry() {
    var searchID = document.getElementById('searchID').value;

    if (searchID === '') {
        alert("Please enter an ID number to search.");
        return;
    }

    // Make an AJAX request to search for the ID in the database
    fetch('search_entry.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            'Entry_no': searchID
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // Fill the form with the retrieved data
            document.getElementById('name').value = data.data.name;
            document.getElementById('ID_number').value = data.data.ID_number;
            document.getElementById('school').value = data.data.school;
            document.getElementById('course').value = data.data.course;
            document.getElementById('date_joined').value = data.data.date_joined;
            document.getElementById('date_leaving').value = data.data.date_leaving;
            // document.getElementById('school_supervisor_name').value = data.data.school_supervisor_name;
            // document.getElementById('school_supervisor_number').value = data.data.school_supervisor_number;
            document.getElementById('email').value = data.data.email;
            document.getElementById('phone_number').value = data.data.phone_number;
            document.getElementById('Department').value = data.data.Department;
            document.getElementById('Supervisor').value = data.data.Supervisor;
        } else {
            alert(data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}
