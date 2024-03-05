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

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Racing Puzzle</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="fontawesome-free-6.4.0-web/css/all.css">
    <link rel="icon" type="image/x-icon" href="../controllers/logo.ico">


    <style>

        body {
            /* Specify the background image URL */
            background-image: url('../gallop.jpg');
            /* Adjust background properties */
            background-size: cover; /* Cover the entire viewport */
            background-repeat: no-repeat; /* Do not repeat the image */
            background-position: center center; /* Center the image */
            /* Add other styles */
            font-family: Arial, sans-serif; /* Choose a suitable font */
            color: #333; /* Text color */
        }

        .container {
            margin-top: 50px;
        }

        .card {
            background-color: #26334e;
            color: #ede2c9;
            margin-bottom: 20px;
        }

        .card-title {
            color: #ede2c9;
            text-align: center; /* Center-align */
        }

        .card-text {
            color: #ede2c9;
            text-align: center; /* Center-align */
        }
        
        /* Table styling */
        .table-responsive {
            overflow-x: auto;
        }

        .table {
            width: 100%;
            margin-bottom: 0;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 12px; /* Adjusted cell padding */
            vertical-align: middle;
            border-top: 1px solid #dee2e6;
            text-align: center; /* Center-align table data */
            color: #ede2c9; /* Set text color */
        }

        .table th {
            background-color: #26334e;
            color: #fff;
            font-weight: bold;
            text-align: center; /* Center-align table headers */
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 123, 255, 0.1);
        }

        .table-striped tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.2);
        }

        .card {
    box-shadow: 0 4px 8px rgba(0, 0, 50, 50); /* Add shadow effect */
}

.card h3 {
    text-align: center; /* Center-align card titles */
    background-color: #26334e;
    color: #ede2c9; /* Set text color */
}


        .rolling-bar {
            overflow: hidden;
            white-space: nowrap; /* Prevent line breaks */
            background-color: #26334e; /* Set background color */
            color: #ede2c9; /* Set text color */
            padding: 10px; /* Add some padding for better appearance */
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

        .footer {
    width: 100%;
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

    <div class="container">
    <h2 style="color: #ff6d64;">Paddock Portfolio</h2>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card rounded">
                    <div class="card-body">
                        <h3 class="card-title">Total Profit</h3>
                        <p class="card-text"><i></i> <?php echo $total_profit; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card rounded">
                    <div class="card-body">
                        <h3 class="card-title">Average Price</h3>
                        <p class="card-text"><?php echo number_format($average_odds, 2); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card rounded">
                    <div class="card-body">
                        <h3 class="card-title">Horses Tracked</h3>
                        <p class="card-text"><?php echo $num_horses; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

<div class="container-fluid">
    <div class="row">

        <div class="col-md-4 mb-4">
            <div class="card">
                <h3>Top Racecourse</h3>
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
                    echo "<td>£{$row['TotalProfit']}</td>";
                    echo "</tr>";
                    $rank++;
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
</div>
        <div class="col-md-4 mb-4">
            <div class="card">
                <h3>Top Jockey</h3>
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
                    echo "<td>£{$row['TotalProfit']}</td>";
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
            <div class="col-md-4 mb-4">
            <div class="card">
                <h3>Top Trainer</h3>
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
                    echo "<td>£{$row['TotalProfit']}</td>";
                    echo "</tr>";
                    $rank++;
                }
                ?>
            </tbody>
        </table>
    </div>
            </div>
            </div>

            <footer class="footer bg-dark text-white py-3 mt-4">
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

    document.addEventListener("DOMContentLoaded", function () {
        var dropdown = document.querySelector('.dropdown');
        dropdown.addEventListener('mouseenter', function () {
            dropdown.querySelector('.dropdown-menu').style.display = 'block';
        });
        dropdown.addEventListener('mouseleave', function () {
            dropdown.querySelector('.dropdown-menu').style.display = 'none';
        });
    });
</script>

</body>

</html>