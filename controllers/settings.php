<?php 
// Start the session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection code
include '../helpers/db_connection.php'; // Adjust the path as needed

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
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

.white-container {
    background-color: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Optional: Add a subtle shadow */
    margin: 0 auto; /* Center the container horizontally */
    max-width: 500px; /* Set a maximum width for better readability */
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
            echo '<li class="list-inline-item"><a href="TRHhome.php"><div class="logout-icon">
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
            <a href="racinghubhome.php">
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
    <a href="settings.php" class="active">
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

    <!-- Content -->
    <div class="content">
        <!-- Your content here -->
        <div class="white-container">
            <h1>Settings</h1>
            <h2>Edit Username</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <label for="new_username">New Username:</label>
                <input type="text" id="new_username" name="new_username">
                <button type="submit">Update Username</button>
            </form>

            <h2>Add Profile Image</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                <label for="profile_image">Profile Image:</label>
                <input type="file" id="profile_image" name="profile_image">
                <button type="submit">Upload Image</button>
            </form>

            <h2>Set Preferences</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <label for="preferences">Preferences:</label>
                <input type="text" id="preferences" name="preferences" value="<?php echo $user_data['preferences']; ?>">
                <button type="submit">Save Preferences</button>
            </form>
            <h2>Choose Your Preferences</h2>
    <form action="update_preferences.php" method="post">
        <label for="odds_format">Odds Format:</label>
        <select name="odds_format" id="odds_format">
            <option value="fractions">Fractions</option>
            <option value="decimals">Decimals</option>
        </select>
        <br><br>
        <label for="point_system">Point System:</label>
        <select name="point_system" id="point_system">
            <option value="default">Default</option>
            <option value="points">Points</option>
        </select>
        <br><br>
        <input type="submit" value="Save Preferences">
    </form>
        </div>
    </div>
</div>

<!-- Your footer content here -->

</body>
</html>

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
    </script>

