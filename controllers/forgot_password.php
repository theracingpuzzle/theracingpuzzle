<?php
session_start(); // Start the session

// Include the file that contains the database connection configuration
include('../helpers/db_connection.php');

// Initialize invalid flags
$invalidEmail = false;
$databaseError = false;

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get input from the forgot password form
    $email = trim($_POST['email']); // Trim whitespace

    // Check if the email exists in the database
    $query = "SELECT * FROM Users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        // Database query error
        $databaseError = true;
    } else {
        // Check if the email exists in the database
        if (mysqli_num_rows($result) > 0) {
            // Email exists, proceed with sending reset instructions
            // Your code to send reset instructions via email goes here
        } else {
            // Email does not exist in the database
            $invalidEmail = true;
        }
    }
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
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <h2 class="text-center">Forgot Password</h2>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form action="" method="POST" class="needs-validation" novalidate>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                        <div class="invalid-feedback">Please enter a valid email.</div>
                    </div>
                    
                    <?php if ($invalidEmail || $databaseError): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php if ($invalidEmail) echo "Email not found. "; ?>
                            <?php if ($databaseError) echo "Database error. Please try again later."; ?>
                        </div>
                    <?php endif; ?>

                    <button type="submit" class="btn btn-primary btn-block">Submit</button>
                </form>
            </div>
        </div>
    </div>

    <p class="text-center mt-3">Don't have an account? <a href="registration.php">Create Account</a> 
    <p class="text-center mt-3">Remember Password <a href="login.php">Login</a></p>


    <!-- Scripts for form validation -->
</body>
</html>
