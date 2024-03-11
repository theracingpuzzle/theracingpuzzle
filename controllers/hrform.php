<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection code
include '../helpers/db_connection.php';

// Get the logged-in user's ID from the session
if(isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

}

// Query to calculate the total profit for the logged-in user
$query = "SELECT SUM(profit) AS total_profit FROM Results WHERE User_ID = :user_id";
$stmt = $pdo->prepare($query);
$stmt->execute(['user_id' => $user_id]);

// Fetch the total profit
$total_profit_row = $stmt->fetch(PDO::FETCH_ASSOC);
$total_profit = $total_profit_row['total_profit'];

// Ensure total profit has two decimal places
if(isset($total_profit)) {
    $total_profit = number_format($total_profit, 2);

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Racing Puzzle Record</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="fontawesome-free-6.4.0-web/css/all.css">

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
                        <li class="list-inline-item"><a href="racinghubhome.php" class="text-white">Racing
                                Hub</a></li>
                        <li class="list-inline-item"><a href="trackertodb.php" class="text-white">Tracker</a></li>
                        <li class="list-inline-item"><a href="hrform.php" class="text-white active">Record</a></li>
                        <li class="list-inline-item dropdown">
                            <a href="#" class="text-white dropdown-toggle" id="tools-dropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Tools</i>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="tools-dropdown">
                <li><a class="dropdown-item" href="calculator.html">Bet Calculator</a></li>
                <li><a class="dropdown-item" href="leagues.php">Leagues</a></li>
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
        <!-- Add dropdown for month filtering -->
        <div class="form-group mr-2">
            <label for="monthFilter">Filter by Month:</label>
            <select class="form-control" id="monthFilter">
                <option value="">All Months</option>
                <option value="01">January</option>
                <option value="02">February</option>
                <option value="03">March</option>
                <option value="04">April</option>
                <option value="05">May</option>
                <option value="06">June</option>
                <option value="07">July</option>
                <option value="08">August</option>
                <option value="09">September</option>
                <option value="10">October</option>
                <option value="11">November</option>
                <option value="12">December</option>
            </select>
        </div>

       <!-- Search input -->
<div class="input-group" style="max-width: 500px;"> <!-- Adjust max-width as needed -->
    <input type="text" class="form-control" id="searchInput" placeholder="Search jockey, trainer, or horse name">
    <div class="input-group-append">
        <button class="btn btn-outline-light" type="button" id="searchButton"><i class="fas fa-search"></i></button>
    </div>
</div>
    
                        <!-- Button to trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#formModal"><i class="fas fa-plus"></i>
  Add Record</button>
            <button class="btn btn-success" onclick="exportTableToCSV()"><i class="fas fa-download"></i> Export to
                CSV</button>


            <div class="table-container">
                <h1 class="mb-3">Results Table</h1>
                <table class="table table-bordered" id="resultsTable">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Racecourse</th>
                            <th>Selection</th>
                            <th>Jockey</th>
                            <th>Trainer</th>
                            <th>Stake</th>
                            <th>Odds</th>
                            <th>Outcome</th>
                            <th>Return</th>
                            <th>Profit</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <!-- Existing rows will be dynamically added here -->
                    </tbody>
                    <tfoot>
    <tr>
        <td colspan="9"><h2>Total Profit</h2></td>
        <td id="totalProfitValue"><?php echo $total_profit; ?></td>
    </tr>
</tfoot>

                </table>

            </div>



<!-- Modal -->
<div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-labelledby="formModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formModalLabel">Add New Record</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="recordForm">
                    <div class="form-group">
                        <label for="date">Date:</label>
                        <input type="date" class="form-control" id="date" name="date" required>
                    </div>
          <div class="form-group">
            <label for="racecourse">Racecourse:</label>
            <select class="form-control" id="racecourse" name="racecourse" required>
              <option value="">Select Racecourse</option>

              <?php
              // SQLite database file path
              $db_file = "../theracinghub.db";

              try {
                  // Connect to SQLite database
                  $conn = new PDO("sqlite:$db_file");
                  // Set errormode to exceptions
                  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                  // SQL query to select all racecourses from the Racecourses table
                  $sql = "SELECT Racecourse FROM Racecourses";

                  // Execute the query
                  $result = $conn->query($sql);

                  // Check if there are any rows returned
                  if ($result !== false) {
                      // Fetch all rows as an associative array
                      $racecourses = $result->fetchAll(PDO::FETCH_ASSOC);
                      
                      // Output an <option> element for each racecourse
                      foreach ($racecourses as $row) {
                          echo '<option value="' . $row["Racecourse"] . '">' . $row["Racecourse"] . '</option>';
                      }
                  } else {
                      echo "0 results";
                  }
              } catch (PDOException $e) {
                  echo "Connection failed: " . $e->getMessage();
              }
              ?>
            </select>
          </div>
          
        <div class="form-group">
            <label for="selection">Selection:</label>
            <input type="text" class="form-control" id="selection" name="selection" required>
        </div>
        <div class="form-group">
            <label for="jockey">Jockey:</label>
            <input type="text" class="form-control" id="jockey" name="jockey" required>
        </div>
        <div class="form-group">
            <label for="trainer">Trainer:</label>
            <input type="text" class="form-control" id="trainer" name="trainer" required>
        </div>
        <div class="form-group">
            <label for="stake">Stake:</label>
            <input type="number" class="form-control" id="stake" name="stake" onchange="calculateReturn()" required>
        </div>
        <div class="form-group">
            <label for="odds">Odds:</label>
            <input type="text" class="form-control" id="odds" name="odds" onchange="calculateReturn()" required>
        </div>
        <div class="form-group">
            <label for="outcome">Outcome:</label>
            <select class="form-control" id="outcome" name="outcome" onchange="calculateReturn()" required>
                <option value="0">Lost</option>
                <option value="1">Won</option>
            </select>
        </div>
        <div class="form-group">
            <label for="return">Return:</label>
            <input type="text" class="form-control" id="return" name="return" readonly>
        </div>
        <div class="form-group">
            <label for="profit">Profit:</label>
            <input type="text" class="form-control" id="profit" name="profit" readonly>

          <button type="button" class="btn btn-primary" onclick="sendDataToServer()">Submit</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </form>
      </div>
    </div>
  </div>
</div>


        <!-- Bootstrap CSS -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>

function calculateReturn() {
    var stakeInput = document.getElementById("stake");
    var stake = parseFloat(stakeInput.value);
    if (isNaN(stake)) {
        stake = 0; // Set stake to 0 if it's not a valid number
    }
    // Round stake to two decimal places
    var roundedStake = stake.toFixed(2);
    // Update the value of the stake input field
    stakeInput.value = roundedStake;

    var oddsInput = document.getElementById("odds").value;
    var outcome = parseFloat(document.getElementById("outcome").value);

    var odds;
    if (oddsInput.indexOf('/') > -1) { // check if odds are in fraction format
        var parts = oddsInput.split('/');
        odds = parseFloat(parts[0]) / parseFloat(parts[1]);
    } else {
        odds = parseFloat(oddsInput);
    }

    var total_return;
    var profit;

    if (outcome === 0) { // Outcome is "Lost"
        total_return = 0;
        profit = 0 - stake;
    } else { // Outcome is "Won"
        total_return = stake * (odds * outcome + 1);
        profit = total_return - stake;
    }

    // Update the value of the return input field
    document.getElementById("return").value = total_return.toFixed(2);

    // Update the value of the profit input field
    document.getElementById("profit").value = profit.toFixed(2);
}



                
              function calculateTotalProfit() {
                        var table = document.getElementById('resultsTable');
                        var rows = table.getElementsByTagName('tr');
                        var totalProfit = 0;
                
                        for (var i = 1; i < rows.length; i++) { // Start from index 1 to skip header row
                            var profitCell = rows[i].cells[7]; // Assuming profit is in the last cell (index 7)
                            if (profitCell) { // Check if profitCell is defined
                                var profit = parseFloat(profitCell.textContent);
                                if (!isNaN(profit)) { // Check if profit is a valid number
                                    totalProfit += profit;
                                }
                            }
                        }
                
                        // Update the total profit cell
                        var totalProfitCell = document.getElementById('totalProfit');
                        if (totalProfitCell) { // Check if totalProfitCell is defined
                            totalProfitCell.textContent = totalProfit.toFixed(2);
                        }
                    }
                
                    document.addEventListener("DOMContentLoaded", function () {
                        // Event listener for the "Add Record" button
                        var addRecordButton = document.getElementById('showFormButton');
                        addRecordButton.addEventListener('click', function (event) {
                            toggleFormVisibility(); // Show the form
                        });
                
                        // Attach submitForm function to the form submit event
                        var form = document.querySelector('form');
                        form.addEventListener("submit", function (event) {
                            event.preventDefault(); // Prevent the default form submission behavior
                            addRow(); // Call the addRow function after submitting the form
                            calculateReturn(); // Calculate return after adding row
                            toggleFormVisibility(); // Hide the form after submitting
                        });
                
                        // Event listener for the "Calculate Return" button
                        var calculateReturnButton = document.getElementById("calculateReturnButton");
                        calculateReturnButton.addEventListener("click", calculateReturn);
                
                        // Event listener for the "Export to CSV" button
                        var exportCSVButton = document.getElementById("exportCSVButton");
                        exportCSVButton.addEventListener("click", exportTableToCSV);
                
                        // Event listener for toggling dropdown
                        var dropdown = document.getElementById('tools-dropdown');
                        dropdown.addEventListener('click', function () {
                            dropdown.classList.toggle('active');
                        });
                    });
                
                    // Function to toggle form visibility
                    function toggleFormVisibility() {
                        var formContainer = document.getElementById("formContainer");
                        if (formContainer.style.display === "none" || formContainer.style.display === "") {
                            formContainer.style.display = "block";
                        } else {
                            formContainer.style.display = "none";
                        }
                    }
                
                    // Function to export table data to CSV
                    function exportTableToCSV() {
                        var csv = [];
                        var rows = document.querySelectorAll("#resultsTable tr");
                
                        for (var i = 0; i < rows.length; i++) {
                            var row = [], cols = rows[i].querySelectorAll("td, th");
                
                            for (var j = 0; j < cols.length; j++)
                                row.push(cols[j].innerHTML);
                
                            csv.push(row.join(","));
                        }
                
                        // Download CSV file
                        downloadCSV(csv.join("\n"));
                    }
                
                    // Function to download CSV
                    function downloadCSV(csv) {
                        var csvFile = new Blob([csv], { type: "text/csv" });
                
                        var downloadLink = document.createElement("a");
                        downloadLink.download = "results.csv";
                        downloadLink.href = window.URL.createObjectURL(csvFile);
                        downloadLink.style.display = "none";
                
                        document.body.appendChild(downloadLink);
                        downloadLink.click();
                    }

                    function fetchDataFromResultsTable() {
            var data = [];
            var tableRows = document.querySelectorAll("#resultsTable tbody tr");
            
            tableRows.forEach(function(row) {
                var rowData = {
                    date: row.cells[0].textContent,
                    racecourse: row.cells[1].textContent,
                    selection: row.cells[2].textContent,
                    jockey: row.cells[3].textContent,
                    trainer: row.cells[4].textContent,
                    stake: parseFloat(row.cells[5].textContent),
                    odds: parseFloat(row.cells[6].textContent),
                    outcome: parseFloat(row.cells[7].textContent),
                    return: parseFloat(row.cells[8].textContent),
                    profit: parseFloat(row.cells[9].textContent)
                };
                data.push(rowData);
            });
            
            return data;
        }

        fetch('fetch_data_results.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Failed to fetch data (status code: ${response.status})`);
                }
                return response.json();
            })
            .then(data => {
                // Process the fetched data
                console.log(data);

                // Get a reference to the table body
                const tableBody = document.getElementById('tableBody');

                // Clear existing table rows
                tableBody.innerHTML = '';

                // Iterate over the fetched data and create table rows
                data.forEach(row => {
                    const newRow = document.createElement('tr');
                    newRow.innerHTML = `
                        <td>${row.Date}</td>
                        <td>${row.Racecourse}</td>
                        <td>${row.Selection}</td>
                        <td>${row.Jockey}</td>
                        <td>${row.Trainer}</td>
                        <td>${row.Stake}</td>
                        <td>${row.Odds}</td>
                        <td>${row.Outcome}</td>
                        <td>${row.Return}</td>
                        <td>${row.Profit}</td>
                    `;
                    tableBody.appendChild(newRow);
                });

                // Calculate total profit after displaying the data
                calculateTotalProfit();
            })
            .catch(error => console.error("Error fetching data:", error));

// Function to send form data to PHP script for insertion
function sendDataToServer() {
    // Get form data
    const form = document.querySelector('form');
    const formData = new FormData(form);

    // Get the date input value
    const dateInput = formData.get('date');

    // Split the date into day, month, and year parts
    const parts = dateInput.split('-');
    const day = parts[2];
    const month = parts[1];
    const year = parts[0];

    // Create the formatted date string in DD-MM-YYYY format
    const formattedDate = `${day}-${month}-${year}`;

    // Set the formatted date back into the form data
    formData.set('date', formattedDate);

    // Convert outcome value to either "Lost" or "Won"
    const outcome = formData.get('outcome');
    const outcomeText = outcome === '0' ? 'Lost' : 'Won';
    formData.set('outcome', outcomeText);

    // Log form data
    for (var pair of formData.entries()) {
        console.log(pair[0] + ': ' + pair[1]);
    }

    // Send form data to server
    fetch('insert_data_results.php', {
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
    // Log the server response
    console.log(data);

    // If insertion is successful, update the table with new data
    addRow(formData); // Add new row to table
    calculateTotalProfit(); // Recalculate total profit
    toggleFormVisibility(); // Hide the form after submission

    // Close the modal after successful submission
    $('#formModal').modal('hide');
})
.catch(error => console.error("Error inserting data:", error));
}



function addRow(formData) {
    var tableBody = document.getElementById('resultsTable').getElementsByTagName('tbody')[0];
    var newRow = tableBody.insertRow();

    var cells = [];
    for (var i = 0; i < 10; i++) {
        cells[i] = newRow.insertCell(i);
    }

    // Populate the cells with data from the form
    cells[0].textContent = formData.get('date');
    cells[1].textContent = formData.get('racecourse');
    cells[2].textContent = formData.get('selection');
    cells[3].textContent = formData.get('jockey');
    cells[4].textContent = formData.get('trainer');
    cells[5].textContent = formData.get('stake');
    cells[6].textContent = formData.get('odds');
    cells[7].textContent = formData.get('outcome');
    cells[8].textContent = formData.get('return');
    cells[9].textContent = formData.get('profit');
}


document.addEventListener("DOMContentLoaded", function () {
        var dropdown = document.querySelector('.dropdown');
        dropdown.addEventListener('mouseenter', function () {
            dropdown.querySelector('.dropdown-menu').style.display = 'block';
        });
        dropdown.addEventListener('mouseleave', function () {
            dropdown.querySelector('.dropdown-menu').style.display = 'none';
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
    // Get reference to the dropdown
    var monthFilter = document.getElementById('monthFilter');

    // Add event listener for change event on the dropdown
    monthFilter.addEventListener('change', function() {
        var selectedMonth = monthFilter.value; // Get the selected month value
        filterTableByMonth(selectedMonth); // Call function to filter table rows
    });
});

function filterTableByMonth(month) {
    var tableRows = document.querySelectorAll('#resultsTable tbody tr');
    
    tableRows.forEach(function(row) {
        var date = row.cells[0].textContent; // Assuming date is in the first column
        var rowMonth = date.split('-')[1]; // Extract month from the date
        
        // If month is not selected or matches the selected month, show the row; otherwise, hide it
        if (!month || rowMonth === month) {
            row.style.display = 'table-row';
        } else {
            row.style.display = 'none';
        }
    });
}

document.getElementById('searchButton').addEventListener('click', function() {
    var searchInput = document.getElementById('searchInput').value.toLowerCase();
    var tableRows = document.querySelectorAll('#resultsTable tbody tr');
    
    tableRows.forEach(function(row) {
        var rowData = row.textContent.toLowerCase();
        if (rowData.includes(searchInput)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});



 </script>
                
                </body>

                </html>