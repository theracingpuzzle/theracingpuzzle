<?php 
// Start the session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection code
include '../helpers/db_connection.php'; // Adjust the path as needed

// Check if the user is logged in
if(isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

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
} else {
    // Handle the case when the user is not logged in
    // You can redirect the user to a login page or display an error message
    header("Location: login.php"); // Example redirection to a login page
    exit();
}

// Step 1: Fetch odds from the database for the logged-in user
$user_id = $_SESSION['user_id']; // Assuming you have the user ID stored in session

$query = "SELECT Odds FROM Results WHERE User_ID = :user_id";
$stmt = $pdo->prepare($query);
$stmt->execute(['user_id' => $user_id]);

$odds = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $odds[] = $row['Odds'];
}

// Step 2: Calculate the average odds
$average_odds = 0;
if (!empty($odds)) {
    $average_odds = array_sum($odds) / count($odds);
}

// Query to count the number of entries in the Tracker table for the logged-in user
$query = "SELECT COUNT(*) AS num_horses FROM Tracker WHERE User_ID = :user_id";
$stmt = $pdo->prepare($query);
$stmt->execute(['user_id' => $user_id]);

// Fetch the number of horses tracked
$num_horses_row = $stmt->fetch(PDO::FETCH_ASSOC);
$num_horses = $num_horses_row['num_horses'];

$timePeriod = isset($_GET['timePeriod']) ? $_GET['timePeriod'] : 'today'; // Get the selected time period from the request

// Modify your SQL queries to fetch data based on the selected time period
// For example:
// $query = "SELECT SUM(profit) AS total_profit FROM Results WHERE User_ID = :user_id AND DATE(created_at) = CURDATE()";
// You can adjust the SQL queries based on your database schema and requirements
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Racing Puzzle</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../fontawesome-free-6.4.0-web/css/all.css">
    <link rel="icon" type="image/x-icon" href="../favicon.ico">
    <style>
        /* Your CSS styles */
body {
    background-image: url('../puzzle.png'); /* Set the background image for the grass */
    background-color: #f0f0f0; /* Grey background color */
    background-size: 30%;
}

.title {
    display: block; /* Ensure the title is always displayed */
}

.sidebar {
    background-color: white; /* White background color */
    border-right: 2px solid #26334e; /* Blue right border */
    color: #26334e; /* Blue text color */
    height: 100%;
    width: 250px;
    position: fixed;
    top: 0;
    left: 0;
    padding-top: 20px;
    transition: all 0.3s;
}

.sidebar.collapsed {
    width: 80px;
    overflow-x: hidden;
}

.sidebar.collapsed #sidebarTitle {
    display: none; /* Hide the title when the sidebar is collapsed */
}

.sidebar a {
    padding: 15px 10px; /* Adjusted padding to add more space */
    text-decoration: none;
    color: #26334e; /* Blue text color */
    display: flex; /* Make sidebar links flex containers */
    align-items: center; /* Center vertically */
    justify-content: flex-start; /* Center horizontally */
    margin-bottom: 10px; /* Added margin to create space */
}

.sidebar.collapsed a {
    justify-content: center; /* Center content horizontally when collapsed */
}

.sidebar a i {
    margin-right: 10px; /* Add spacing between icon and title */
}

.sidebar a .title {
    display: block; /* Always display the title */
}

.sidebar.collapsed a .title {
    display: none; /* Hide the title only when the sidebar is collapsed */
}

.sidebar.collapsed a i {
    margin-right: 0; /* Remove margin when collapsed */
}

.sidebar a:hover {
    background-color: #edf2f7; /* Light blue background color on hover */
}

.sidebar .active {
    background-color: #edf2f7; /* Light blue background color for active link */
}

.content {
    padding: 20px;
    margin-left: 250px; /* Adjusted margin-left */
    transition: margin-left 0.3s; /* Add transition for smooth effect */
}

.content-collapsed {
    margin-left: 80px; /* Adjusted margin-left for collapsed state */
}

.username-label {
    color: #edf2f7;
}

/* Header styles */
.header {
    background-image: url('../grass.jpeg'); /* Set the background image for the grass */
    background-size: 10%;
    background-color: #26334e;
    color: white;
    padding: 10px 0;
    padding-top: 20px;
}

.header .container {
    padding-left: 20px;
    padding-right: 20px;
}

.notification-icon {
    margin-right: auto; /* Pushes the notification icon to the left */
}

.user-info {
    margin-left: auto; /* Pushes the username and logout button to the right */
}

.toggle-btn {
    color: white;
}

.logout-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%; /* Make it circular */
    background-color: white; /* White background color */
    border: 5px solid red; /* Thick red border */
    display: flex;
    justify-content: center;
    align-items: center;
}

.logout-icon i {
    color: #000; /* Black color for the icon */
}

.custom-card-orange {
    background-color: #26334e; /* changed to blue background color */
    color: white; /* White text color */
}

.custom-icon-orange {
    color: #34475e; /* Light blue color for icon */
}

.fa-3x {
    font-size: 3em; /* Adjust size as needed */
}

.card-body {
    padding: 20px; /* Adjust padding as needed */
    
}

.card-title {
    margin-top: 10px; /* Add margin between the echoed value and the title */
    font-size: 18px; /* Adjust font size as needed */
}

/* CSS for filter section */
.filter-section {
    display: none; /* Hide filters section by default */
    margin-bottom: 20px; /* Add margin below the filters */
}

.filter-section.show {
    display: block; /* Show filters section when 'show' class is added */
}

/* Rolling bar */
.rolling-bar {
            overflow: hidden;
            white-space: nowrap; /* Prevent line breaks */
            background-color: #26334e; /* Set background color */
            color: #ede2c9; /* Set text color */
            padding: 10px; /* Add some padding for better appearance */
            position: absolute; /* Position the rolling bar absolutely */
            z-index: -5; /* Set a lower z-index to ensure the rolling bar is behind other content */
            border: 1px solid white; /* Add a thin white border */
            border-radius: 5px; /* Add border radius for rounded corners */
        }

#rolling-content {
            list-style-type: none;
            padding: 0;
            margin: 0;
            animation: roll 30s linear infinite; /* Adjust duration as needed */
            display: inline-block; /* Make the list items appear inline */
        }

        #rolling-content li {
            display: inline-block; /* Make the list items appear inline */
            padding-right: 20px; /* Adjust spacing between items as needed */
        }

        @keyframes roll {
            0% { transform: translateX(100%); } /* Start off screen */
            100% { transform: translateX(-100%); } /* Move to the left */
        }

        .toggle-yellow {
    background-color: yellow;
    /* Add any other styles you want for the yellow toggle button */
}

.toggle-yellow .fa-bars {
    color: black;
    /* Add any other styles you want for the bars */
}

.footer {
    background-color: #26334e; /* Set the background color */
    color: white; /* Set the text color to white */
    width: 100%; /* Make the footer stretch across the whole page */
}

.top-table-container {
    margin-top: 20px;
}

.top-table-container .card {
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.top-table-container .card-header {
    background-color: #26334e;
    color: #fff;
    border-radius: 10px 10px 0 0;
}

.top-table-container .card-header h3 {
    margin: 0;
}

.top-table-container .table {
    margin-bottom: 0;
}

.top-table-container .table thead th {
    background-color: #26334e;
    color: #fff;
    border-color: #26334e;
}

.top-table-container .table tbody td {
    border-color: #e9ecef;
}

.top-table-container .table-striped tbody tr:nth-of-type(odd) {
    background-color: rgba(38, 51, 78, 0.05);
}

.top-table-container .table-striped tbody tr:nth-of-type(even) {
    background-color: rgba(38, 51, 78, 0.1);
}

.top-table-container .table tbody tr:last-of-type td {
    border-bottom: 2px solid #dee2e6;
}

    </style>
</head>

<body>

<!-- Header -->
<header class="header">
    <div class="container-fluid">
        <div class="row justify-content-between align-items-center">
            <!-- Username and logout button -->
            <div class="col-auto ml-auto">
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
            echo '<li class="list-inline-item"><a href="index.php"><div class="logout-icon">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i> 
                    </div></a></li>';
        } else {
            // Display the Sign Up button
            echo '<li class="list-inline-item"><a href="registration.php" class="btn btn-primary">Sign Up</a></li>';
        }
    ?>
    <!-- End Username label -->
</div>
            <!-- Sidebar toggle button -->
            <div class="col-auto">
            <button class="btn btn-dark toggle-btn toggle-yellow" id="toggleSidebar">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </div>
</header>

<!-- Sidebar and Content Wrapper -->
<div class="wrapper">
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="text-center">
            <!-- Logo -->
            <div class="mb-4">
                <img src="../test.png" alt="Logo" class="logo">
                <span class="title" id="sidebarTitle">The Racing Puzzle</span> <!-- Title -->
            </div>
            <!-- Sidebar links -->
            <a href="racinghubhome.php" class="active">
                <i class="fas fa-home"></i> <!-- Icon -->
                <span class="title">Dashboard</span> <!-- Title -->
            </a>
            <a href="trackertodb.php">
            <i class="fa-solid fa-binoculars"></i> <!-- Icon -->
                <span class="title">Tracker</span> <!-- Title -->
            </a>
            <a href="hrform.php">
                <i class="fas fa-edit"></i> <!-- Icon -->
                <span class="title">Record</span> <!-- Title -->
            </a>
            <a href="leagues.php">
                <i class="fas fa-trophy"></i> <!-- Icon -->
                <span class="title">Leagues</span> <!-- Title -->
            </a>
            <a href="racecard.html">
        <i class="fa-solid fa-book-open"></i> <!-- Icon -->
        <span class="title">Racecards</span> <!-- Title -->
    </a>
</div>
    <a href="settings.php">
        <i class="fas fa-cog"></i> <!-- Icon -->
        <span class="title">Settings</span> <!-- Title -->
    </a>
</div>
            <a href="#" class="dropdown-toggle" id="tools-dropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-tools"></i> <!-- Icon -->
                <span class="title">Tools</span> <!-- Title -->
            </a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="calculator.html"><i class="fas fa-calculator"></i> Bet Calculator</a>
                <a class="dropdown-item" href="leagues.php"><i class="fas fa-trophy"></i> Leagues</a>
                <a class="dropdown-item" href="testing.php"><i class="fas fa-cogs"></i> Testing Page</a>
            </div>
        </div>
    </div>

    <div class="rolling-bar">
        <ul id="rolling-content">
            <li>CHELTENHAM FESTIVAL DAY ONE:</li>
            <li>13:30 - Sky Bet Supreme Novices' Hurdle</li>
            <li>14:10 - My Pension Expert Arkle Challenge Trophy Novices' Chase</li>
            <li>14:50 - Ultima Handicap Chase</li>
            <li>15:30 - Unibet Champion Hurdle Challenge Trophy</li>
            <li>16:10 - Close Brothers Mares' Hurdle</li>
            <li>16:50 - Boodles Juvenile Handicap Hurdle</li>
            <li>17:30 - National Hunt Challenge Cup Amateur Jockeys' Novices' Chase</li>
        </ul>
    </div>
    </div>

    <!-- Main Content -->
    <div class="content" id="mainContent">

      <!-- Show Filters Button -->
<button class="btn btn-primary mb-3" type="button" data-toggle="collapse" data-target="#filtersSection" aria-expanded="false" aria-controls="filtersSection">
    Show Filters
</button>


<!-- Filters Section -->
<div class="container collapse filter-section" id="filtersSection">
    <div class="row">
        <div class="col-md-3 mb-3">
            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                <label class="btn btn-secondary active">
                    <input type="radio" name="options" id="todayOption" autocomplete="off" checked> Today
                </label>
                <label class="btn btn-secondary">
                    <input type="radio" name="options" id="weekOption" autocomplete="off"> Week
                </label>
                <label class="btn btn-secondary">
                    <input type="radio" name="options" id="monthOption" autocomplete="off"> Month
                </label>
                <label class="btn btn-secondary">
                    <input type="radio" name="options" id="yearOption" autocomplete="off"> Year
                </label>
            </div>
        </div>
    </div>
</div>
     

        <div class="container">
    <h3 style="color: white;">Paddock Portfolio</h3>
        <div class="row">

        <div class="col-md-4 mb-4">
    <div class="card rounded custom-card-orange">
        <div class="card-body d-flex align-items-center">
            <!-- Larger icon on the left -->
            <i class="fa-solid fa-sterling-sign custom-icon-orange fa-3x mr-3"></i>
            <div>
                <!-- Echoed value with larger font size -->
                <h2><?php echo $total_profit; ?></h2>
                <!-- Title with smaller font size -->
                <h5 class="card-title">Total Profit</h5>
            </div>
        </div>
    </div>
</div>
<div class="col-md-4 mb-4">
    <div class="card rounded custom-card-orange">
        <div class="card-body d-flex align-items-center">
            <!-- Larger icon on the left -->
            <i class="fa-solid fa-calculator custom-icon-orange fa-3x mr-3"></i>
            <div>
                <!-- Echoed value with larger font size -->
                <h2>10/1</h2>
                <!-- Title with smaller font size -->
                <h5 class="card-title">Average Price</h5>
            </div>
        </div>
    </div>
</div>
<div class="col-md-4 mb-4">
    <div class="card rounded custom-card-orange">
        <div class="card-body d-flex align-items-center">
            <!-- Larger icon on the left -->
            <i class="fas fa-horse custom-icon-orange fa-3x mr-3"></i>
            <div>
                <!-- Echoed value with larger font size -->
                <h2><?php echo $num_horses; ?></h2>
                <!-- Title with smaller font size -->
                <h5 class="card-title">Horses Tracked</h5>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">

    <div class="col-md-4 mb-4 top-table-container">
    <div class="card">
        <h3 class="card-header">Top Racecourse</h3>
        <div class="table-responsive">
            <table class="table table-striped">
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Racecourse</th>
                    <th>Total Profit</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Perform a query to retrieve the top racecourses and their total profits for the logged-in user
                $query = "SELECT Racecourse, SUM(Profit) AS TotalProfit FROM Results WHERE User_ID = :user_id GROUP BY Racecourse ORDER BY TotalProfit DESC LIMIT 10";
                $stmt = $pdo->prepare($query);
                $stmt->execute(['user_id' => $_SESSION['user_id']]);
                $rank = 1;
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>{$rank}</td>";
                    echo "<td>{$row['Racecourse']}</td>";
                    echo "<td>£" . number_format($row['TotalProfit'], 2) . "</td>";
                    echo "</tr>";
                    $rank++;
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
</div>
<div class="col-md-4 mb-4 top-table-container">
    <div class="card">
        <h3 class="card-header">Top Jockey</h3>
        <div class="table-responsive">
            <table class="table table-striped">
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Jockey</th>
                    <th>Total Profit</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Perform a query to retrieve the top racecourses and their total profits for the logged-in user
                $query = "SELECT Jockey, SUM(Profit) AS TotalProfit FROM Results WHERE User_ID = :user_id GROUP BY Jockey ORDER BY TotalProfit DESC LIMIT 10";
                $stmt = $pdo->prepare($query);
                $stmt->execute(['user_id' => $_SESSION['user_id']]);
                $rank = 1;
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>{$rank}</td>";
                    echo "<td>{$row['Jockey']}</td>";
                    echo "<td>£" . number_format($row['TotalProfit'], 2) . "</td>";
                    echo "</tr>";
                    $rank++;
                }
                ?>
            </tbody>
        </table>
    </div>
    <div>
            </div>
            </div>
            </div>
            <div class="col-md-4 mb-4 top-table-container">
    <div class="card">
        <h3 class="card-header">Top Trainer</h3>
        <div class="table-responsive">
            <table class="table table-striped">
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Trainer</th>
                    <th>Total Profit</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Perform a query to retrieve the top racecourses and their total profits for the logged-in user
                $query = "SELECT Trainer, SUM(Profit) AS TotalProfit FROM Results WHERE User_ID = :user_id GROUP BY Jockey ORDER BY TotalProfit DESC LIMIT 10";
                $stmt = $pdo->prepare($query);
                $stmt->execute(['user_id' => $_SESSION['user_id']]);
                $rank = 1;
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>{$rank}</td>";
                    echo "<td>{$row['Trainer']}</td>";
                    echo "<td>£" . number_format($row['TotalProfit'], 2) . "</td>";
                    echo "</tr>";
                    $rank++;
                }
                ?>
            </tbody>
        </table>
    </div>
            </div>
            </div>

    <footer class="footer text-white py-3 mt-4">
    <div class="container">
        <div class="row">
            <div class="col-md-6 text-center text-md-left mb-3 mb-md-0">
                <p>&copy; 2024 The Racing Puzzle. All rights reserved.</p>
            </div>
            <div class="col-md-6 text-center text-md-right">
                <!-- Social media icons -->
                <a href="#" class="text-white mr-3"><i class="fab fa-twitter"></i></a>
                <a href="www.instagram.com/theracingpuzzle" class="text-white"><i class="fab fa-instagram"></i></a>
                <!-- End social media icons -->
            </div>
        </div>
    </div>
</footer>
    

    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
    integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
    crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
    integrity="sha384-MTIGF5FusOnTrj7Ffah1rloUwvbefXP/0JKdmh1o2ETxbqk8lEszEhx5DMtPf3G6"
    crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"
    integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shC1q6wws5+8a/iRMVfS4x0g2DObU27milxj4"
    crossorigin="anonymous"></script>
<script>
    document.getElementById('toggleSidebar').addEventListener('click', function () {
        document.getElementById('sidebar').classList.toggle('collapsed');
        document.getElementById('mainContent').classList.toggle('content-collapsed');
    });

    // JavaScript to toggle collapse of filters section
    document.addEventListener('DOMContentLoaded', function () {
        var showFiltersBtn = document.querySelector('[data-target="#filtersSection"]');
        showFiltersBtn.addEventListener('click', function () {
            var filtersSection = document.querySelector('#filtersSection');
            if (filtersSection.classList.contains('show')) {
                filtersSection.classList.remove('show');
            } else {
                filtersSection.classList.add('show');
            }
        });
    });

    document.getElementById('timePeriodFilter').addEventListener('change', function() {
    // Call a function to fetch data and update metrics based on the selected time period
    updateMetrics(this.value);
});

function updateMetrics(timePeriod) {
    // Use AJAX to send a request to the server with the selected time period
    // Fetch data from the server based on the selected time period
    // Calculate total profit and other metrics based on the fetched data
    // Update the displayed metrics on the webpage
}

</script>

</body>

</html>
