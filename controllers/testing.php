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
        .header {
        height: 60px; /* Adjust the height as needed */
    }

    .navbar-brand {
            color: white; /* Title color */
            font-size: 35px; /* Title font size */
            margin: auto; /* Center the navbar brand */
        }

        .header,
        .sidebar {
            background-color: #26334e;
            color: white;
        }

        .sidebar {
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

        .sidebar.collapsed .fa-caret-right {
            display: none;
        }

        .sidebar .fa-caret-left {
            display: none;
        }

        .sidebar.collapsed .fa-caret-left {
            display: inline;
        }

        .sidebar a {
            padding: 15px 10px; /* Adjusted padding to add more space */
            text-decoration: none;
            color: #fff;
            display: block;
            margin-bottom: 10px; /* Added margin to create space */
        }

        .sidebar a:hover {
            background-color: #1a2433;
        }

        .sidebar .active {
            background-color: #1a2433;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
            background-color: #fff; /* Set content background color */
        }

        .username-label {
            color: #fff;
        }
    </style>
</head>

<body>

<!-- Header -->
<header class="header">
    <div class="container">
        <!-- Move the navbar brand and toggle button inside a flex container -->
        <div class="d-flex justify-content-between align-items-center">
            <div></div> <!-- Placeholder for left content, if any -->
            <a class="navbar-brand mx-auto" href="#">The Racing Puzzle</a> <!-- Move to the middle -->
            <!-- User credentials -->
            <div class="d-flex align-items-center"> <!-- Container for user credentials -->
                <?php
                include('auth.php');
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                if (isset($_SESSION['username'])) {
                    echo '<div class="username-label text-white mr-3">Welcome, ' . $_SESSION['username'] . '</div>';
                    echo '<a href="logout.php" class="btn btn-danger">Log Out</a>';
                } else {
                    echo '<a href="registration.php" class="btn btn-primary">Sign Up</a>';
                }
                ?>
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
            </div>
            <!-- Toggle button for sidebar -->
            <div>
                <button class="btn btn-dark toggle-btn" id="toggleSidebar">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            <!-- Sidebar links -->
            <a href="racinghubhome.php" class="active">Racing Hub</a>
            <a href="trackertodb.php">Tracker</a>
            <a href="hrform.php">Record</a>
            <a href="#" class="dropdown-toggle" id="tools-dropdown" role="button"
               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Tools</a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="calculator.html">Bet Calculator</a>
                <a class="dropdown-item" href="leagues.php">Leagues</a>
                <a class="dropdown-item" href="testing.php">Testing Page</a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content">
        <!-- Your main content here -->
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
    });
</script>

</body>

</html>
