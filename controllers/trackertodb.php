<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Racing Puzzle Tracker</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        /* Header */
        .header {
            background: linear-gradient(45deg, #333, #555); /* Gradient background */
            color: #fff;
            padding: 15px 0; /* Increased padding */
            z-index: 1000; /* Ensure header is above sidebar */
        }

        .header .logo img {
            max-width: 150px; /* Adjust logo size */
        }

        /* Hide the form initially */
        .hidden {
            display: none;
        }

        /* Center the horse tracker */
        #horse-table-container {
            display: flex;
            justify-content: center;
        }

        #horse-table-container table {
            width: 100%; /* Make the table take full width */
        }

        /* Adjust the table columns */
        #horse-table-container th, #horse-table-container td {
            text-align: center; /* Center align text */
        }
        /* CSS for Table Styling */
        .table-container {
            margin: 20px auto;
            max-width: 1200px;
            overflow-x: auto;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            table-layout: fixed;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
            width: auto;
        }

        th {
            background-color: #f2f2f2;
        }

        tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tfoot {
            font-weight: bold;
        }
    </style>
</head>

<body>
<header class="header bg-dark text-white py-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="title">The Racing <span class="title-p">P</span>uzzle</h1>
            <nav class="navigation">
                <ul class="list-inline mb-0">
                    <li class="list-inline-item"><a href="racinghubhome.php" class="text-white">Racing Hub</a></li>
                    <li class="list-inline-item"><a href="trackertodb.php" class="text-white active">Tracker</a></li>
                    <li class="list-inline-item"><a href="hrform.php" class="text-white">Record</a></li>
                    <li class="list-inline-item dropdown">
                        <a href="#" class="text-white dropdown-toggle" id="tools-dropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Tools
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="tools-dropdown">
                            <li><a class="dropdown-item" href="calculator.html">Bet Calculator</a></li>
                            <li><a class="dropdown-item" href="#">Predictor</a></li>
                            <li><a class="dropdown-item" href="testing.php">Testing Page</a></li>
                        </ul>
                    </li>
                    <!-- User credentials -->
                    <?php
                        // Include the auth.php file for session check
                        include('auth.php');

                        // Start the session (ensure this is called only once)
                        if (session_status() === PHP_SESSION_NONE) {
                            session_start();
                        }

                        // Check if the username session variable is set
                        if (isset($_SESSION['username'])) {
                            // Display the username in the top right corner and a Log Out button
                            echo '<li class="list-inline-item"><div class="username-label text-white">Welcome, ' . $_SESSION['username'] . '</div></li>';
                            echo '<li class="list-inline-item"><a href="logout.php" class="btn btn-danger ml-2">Log Out</a></li>';
                        } else {
                            // Display the Sign Up button
                            echo '<li class="list-inline-item"><a href="registration.php" class="btn btn-primary">Sign Up</a></li>';
                        }
                    ?>
                    <!-- End Username label -->
                </ul>
            </nav>
        </div>
    </div>
</header>

<div class="container mt-4">
    <button type="button" class="btn btn-primary mr-2" data-toggle="modal" data-target="#addHorseModal"><i class="fas fa-plus"></i> Add Horse</button>
    <button class="btn btn-success" onclick="exportTableToCSV()"><i class="fas fa-download"></i> Export to CSV</button>

    <div class="table-container">
        <h1 class="mb-3">Horse Tracker</h1>
        <table id="horse-table" class="table table-bordered">
            <thead>
                <tr>
                    <th>Horse</th>
                    <th>Trainer</th>
                    <th>Comments</th>
                    <th>Last Run</th>
                    <th>Next Run</th>
                    <th>Options</th>
                </tr>
            </thead>
            <tbody id="horse-table-body">
                <!-- Data will be dynamically inserted here -->
            </tbody>
        </table>
    </div>

    <!-- Droppable area -->
<div id="droppable-area" class="hidden">
    <!-- Information to be displayed -->
    <p>This is some information about the selected horse.</p>
</div>
    
    <!-- Add Horse Modal -->
    <div class="modal fade" id="addHorseModal" tabindex="-1" role="dialog" aria-labelledby="addHorseModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addHorseModalLabel">Add Horse</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Add Horse Form -->
                    <form id="addHorseForm">
                        <div class="form-group">
                            <label for="horseName">Horse Name:</label>
                            <input type="text" class="form-control" id="horseName" name="horseName">
                        </div>
                        <div class="form-group">
                            <label for="trainerName">Trainer Name:</label>
                            <input type="text" class="form-control" id="trainerName" name="trainerName">
                        </div>
                        <div class="form-group">
                            <label for="comment">Comment:</label>
                            <input type="text" class="form-control" id="comment" name="comment">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="addHorse()">Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>


<script>
       // Function to toggle modal visibility when Add Horse button is clicked
    document.querySelector('.btn-primary').addEventListener('click', function() {
        $('#addHorseModal').modal('toggle');
    });

    // Function to fetch updated data and update the table
    function updateTable() {
        fetch('fetch_data.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Failed to fetch data (status code: ${response.status})`);
                }
                return response.json();
            })
            .then(data => {
                // Get a reference to the table body
                const tableBody = document.getElementById('horse-table-body');

                // Clear existing table rows
                tableBody.innerHTML = '';

                // Iterate over the fetched data and create table rows
                data.forEach(row => {
                    const newRow = document.createElement('tr');
                    newRow.innerHTML = `
                        <td>${row.Horse}</td>
                        <td>${row.Trainer}</td>
                        <td>${row.Comment}</td>
                        <td></td>
                        <td></td>
                        <td>
                            <button class="btn btn-sm btn-primary" onclick="editRow(${row.id})"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-sm btn-danger" onclick="deleteRow(${row.id})"><i class="fas fa-trash"></i></button>
                        </td>
                    `;
                    tableBody.appendChild(newRow);
                });
            })
            .catch(error => console.error("Error fetching data:", error));
    }


    function addHorse() {
    // Get form data
    const form = document.getElementById('addHorseForm');
    const formData = new FormData(form);

    // Send form data to PHP script for insertion
    fetch('insert_data.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Failed to insert data (status code: ${response.status})`);
        }
        return response.json();
    })
    .then(data => {
        // Update the table with the new data
        updateTable();

        // Reset the form fields
        form.reset();

        // Manually trigger the click event on the close button of the modal
        $('#addHorseModal').modal('hide');
    })
    .catch(error => console.error("Error inserting data:", error));
}




    // Call updateTable function initially to populate the table
    updateTable();

    document.addEventListener("DOMContentLoaded", function () {
        var dropdown = document.querySelector('.dropdown');
        dropdown.addEventListener('mouseenter', function () {
            dropdown.querySelector('.dropdown-menu').style.display = 'block';
        });
        dropdown.addEventListener('mouseleave', function () {
            dropdown.querySelector('.dropdown-menu').style.display = 'none';
        });
    });

    // Function to edit a row
function editRow(id) {
    // Redirect or open a modal for editing based on the id
    // For example, redirect to edit.php?id=id or open a modal with form fields populated with the data for editing
    window.location.href = `edit_tracker.php?id=${id}`;
}

// Function to delete a row
function deleteRow(id) {
    // Confirm deletion with the user
    if (confirm("Are you sure you want to delete this row?")) {
        // Send an AJAX request to delete the row from the database
        fetch(`delete.php?id=${id}`, {
            method: 'DELETE'
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Failed to delete row (status code: ${response.status})`);
            }
            // Remove the corresponding row from the HTML table
            document.getElementById(`row-${id}`).remove();
        })
        .catch(error => console.error("Error deleting row:", error));
    }
}

$(document).ready(function() {
    // Event listener for table row click
    $('#horse-table-body').on('click', 'tr', function() {
        var horseId = $(this).data('horse-id');

        // Check if droppable area already exists for this row
        var droppableArea = $(this).next('.droppable-area');

        // If droppable area already exists and belongs to the same horse, toggle its visibility
        if (droppableArea.length && droppableArea.data('horse-id') === horseId) {
            droppableArea.toggle();
            return;
        }

        // Remove any existing droppable area before inserting a new one
        $('.droppable-area').remove();

        // Create droppable area HTML
        var droppableHtml = '<tr class="droppable-area" data-horse-id="' + horseId + '">' +
                                '<td colspan="3">' +
                                    '<div class="droppable-content">' +
                                        '<p>This is some information about the selected horse.</p>' +
                                    '</div>' +
                                '</td>' +
                            '</tr>';

        // Insert droppable area below the clicked row
        $(this).after(droppableHtml);
    });
});
</script>
</body>
</html>
