<?php 
// Start the session
session_start();

// Include database connection code
include '../helpers/db_connection.php';

// Check if the user is logged in
if(isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
}

// Check if the league ID is set in the URL parameter
$league_id = $_GET['leagueID'] ?? null;
if($league_id !== null) {
    // Set the league ID in the session
    $_SESSION['league_id'] = $league_id;
}

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

    <style>
        /* Custom CSS styles */
        .header {
            background: linear-gradient(45deg, #333, #555); /* Gradient background */
            color: #fff;
            padding: 15px 0; /* Increased padding */
            z-index: 1000; /* Ensure header is above sidebar */
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



<div class="container">
    <h1 class="text-center mt-5">League Management</h1>

    <div class="row mt-4">
        <div class="col-md-6">
            <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#createLeagueModal">Create a League</button>
        </div>
        <div class="col-md-6">
            <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#joinLeagueModal">Join a League</button>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <h2>Your Leagues</h2>
            <table class="table">
                <thead>
                <tr>
                    <th>League Name</th>
                    <th>League Code</th>
                    <th>Members</th>
                </tr>
                </thead>
                <tbody id="userLeaguesTable">
                <!-- Data will be dynamically inserted here using JavaScript -->
                </tbody>
            </table>
        </div>
    </div>
</div>

    <!-- Create League Modal -->
    <div class="modal fade" id="createLeagueModal" tabindex="-1" role="dialog" aria-labelledby="createLeagueModalLabel" aria-hidden="true">
    <!-- Modal content goes here -->
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createLeagueModalLabel">Create a League</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="createLeagueForm" onsubmit="createLeague(event)">
                        <div class="form-group">
                            <label for="leagueName">League Name:</label>
                            <input type="text" class="form-control" id="leagueName" name="leagueName" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Join League Modal -->
    <div class="modal fade" id="joinLeagueModal" tabindex="-1" role="dialog" aria-labelledby="joinLeagueModalLabel" aria-hidden="true">
    <!-- Modal content goes here -->
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="joinLeagueModalLabel">Join a League</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="joinLeagueForm" onsubmit="joinLeague(event)">
                        <div class="form-group">
                            <label for="leagueCode">League Code:</label>
                            <input type="text" class="form-control" id="leagueCode" name="leagueCode" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Join</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- League Code Modal -->
    <div class="modal fade" id="leagueCodeModal" tabindex="-1" role="dialog" aria-labelledby="leagueCodeModalLabel" aria-hidden="true">
    <!-- Modal content goes here -->
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="leagueCodeModalLabel">League Code</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Your league code is: <span id="leagueCodeDisplay"></span></p>
            </div>
        </div>
    </div>
</div>



<!-- Bootstrap JS and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    // Function to handle league creation
    function createLeague(event) {
        // Prevent the default form submission
        event.preventDefault();

        // Get the form data
        var formData = {
            leagueName: $('#leagueName').val(), // Corrected key name to match form field
            user_id: <?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'null'; ?> // Ensure user_id is set
        };

        // Send an AJAX request to create the league
        jQuery.ajax({
            url: 'create_league.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                // Handle the success response
                console.log(response);
                if (response.success) {
                    // Display the league code to the user
                    $('#leagueCodeModal').modal('show');
                    $('#leagueCodeDisplay').text(response.leagueCode);
                    // Redirect or display a success message
                    alert('League created successfully!');
                } else {
                    // Display an error message
                    alert('Failed to create league. Please try again.');
                }
            },
            error: function(xhr, status, error) {
                // Handle the error
                console.error(xhr.responseText);
                alert('An error occurred while creating the league. Please try again.');
            }
        });
    }

    // Function to handle joining a league
    function joinLeague(event) {
        event.preventDefault();

        // Get the league code from the form
        var leagueCode = $('#leagueCode').val();

        // Send an AJAX request to join the league
        $.ajax({
            url: 'join_league.php',
            type: 'POST',
            data: {
                leagueCode: leagueCode
            },
            dataType: 'json',
            success: function(response) {
                // Handle the success response
                if (response.success) {
                    // Display a success message
                    alert(response.message);
                    // Optionally, redirect the user to the league dashboard
                    window.location.href = 'league_dashboard.php';
                } else {
                    // Display an error message
                    alert(response.error);
                }
            },
            error: function(xhr, status, error) {
                // Handle errors
                alert('An error occurred while joining the league: ' + error);
            }
        });
    }

    $(document).ready(function() {
        // Function to fetch user leagues and populate the table
        function fetchUserLeagues() {
            $.ajax({
                url: 'fetch_user_leagues.php', // URL to fetch user leagues
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    // Clear existing table rows
                    $('#userLeaguesTable').empty();

// Populate the table with fetched data
response.forEach(function(league) {
    // Append a new row for each league
    var row = '<tr>' +
        '<td><a href="league_table.php?leagueID=' + league.league_id + '">' + league.league_name + '</a></td>' +
        '<td>' + league.league_code + '</td>' +
        '<td>' + league.members + '</td>' +
        '</tr>';
    $('#userLeaguesTable').append(row);
});


                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    // Handle error
                    alert('An error occurred while fetching user leagues. Please try again.');
                }
            });
        }

        // Call fetchUserLeagues function when the page loads
        fetchUserLeagues();
    });

</script>
</body>
</html>
