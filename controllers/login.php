<?php
session_start(); // Start the session

// Initialize invalid flags
$invalidUsername = false;
$invalidPassword = false;
$databaseError = false;

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get input from the login form
    $username = trim($_POST['username']); // Trim whitespace
    $password = trim($_POST['password']); // Trim whitespace

    // Connect to SQLite database
    $db = new SQLite3('../theracinghub.db');

    if (!$db) {
        // Database connection error
        $databaseError = true;
    } else {
        echo "Database connected successfully!";
        
        // Prepare SQL statement to check if the username exists
        $stmt = $db->prepare('SELECT * FROM users WHERE username = :username');
        $stmt->bindValue(':username', $username, SQLITE3_TEXT);

        // Execute the query
        $result = $stmt->execute();

        // Inside the section where you execute the query and fetch the result
if ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    // Verify password
    if (password_verify($password, $row['Password'])) { // Corrected column name to 'Password'
        // Set the username in session
        $_SESSION['username'] = $username;

        // After successful login
$_SESSION['user_id'] = $row['User_ID']; 


       // Redirect to the racinghubhome
header('Location: racinghubhome.php');
exit(); // Ensure that script execution stops after redirection
    } else {
        $invalidPassword = true;
    }
} else {
    $invalidUsername = true;
}

        }

        // Close the result set
        $stmt->close();
        
        // Close the database connection
        $db->close();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            padding: 20px;
            background-image: url('../puzzle.png'); /* Add background image */
            background-size: cover; /* Ensure the background image covers the entire screen */
            background-size: 40%;
        }
        .error {
            color: red;
        }
        
    </style>
</head>
<body>
    <h2 class="text-center">Login</h2>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form action="" method="POST" class="needs-validation" novalidate>
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" id="username" name="username" class="form-control" required>
                        <div class="invalid-feedback">Please enter your username.</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                        <div class="invalid-feedback">Please enter your password.</div>
                    </div>
                    
                    <?php if ($invalidUsername || $invalidPassword || $databaseError): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php if ($invalidUsername) echo "Invalid username. "; ?>
                            <?php if ($invalidPassword) echo "Invalid password. "; ?>
                            <?php if ($databaseError) echo "Database error. Please try again later."; ?>
                        </div>
                    <?php endif; ?>

                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                </form>
            </div>
        </div>
    </div>

    <p class="text-center mt-3">Don't have an account? <a href="registration.php">Create Account</a> 
    <p class="text-center mt-3">Forgotten Password? <a href="forgot_password.php">Reset Password</a></p>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Bootstrap form validation
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var forms = document.getElementsByClassName('needs-validation');
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
    </script>
</body>
</html>