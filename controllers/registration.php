<?php
// Initialize error message variable
$errorMsg = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];
    $username = $_POST["username"];
    $password = $_POST["password"]; // Password is not hashed yet
    $email = $_POST["email"];

    // Connect to SQLite database
    $db = new SQLite3('../theracinghub.db');

    // Check if username or email already exist
    $existingUserQuery = $db->query("SELECT * FROM Users WHERE username = '$username' OR email = '$email'");
    $existingUser = $existingUserQuery->fetchArray();

    if ($existingUser) {
        // Username or email already exists, set error message
        $errorMsg = "Username or email already exists. Please choose a different one.";
    } else {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Prepare SQL statement to insert data into Users table
        $stmt = $db->prepare("INSERT INTO Users (`First Name`, `Last Name`, Username, Password, Email) 
                              VALUES (:firstName, :lastName, :username, :password, :email)");
        $stmt->bindValue(':firstName', $firstName, SQLITE3_TEXT);
        $stmt->bindValue(':lastName', $lastName, SQLITE3_TEXT);
        $stmt->bindValue(':username', $username, SQLITE3_TEXT);
        $stmt->bindValue(':password', $hashedPassword, SQLITE3_TEXT);
        $stmt->bindValue(':email', $email, SQLITE3_TEXT);

        // Execute the SQL statement
        if ($stmt->execute()) {
            // Close database connection
            $db->close();

            // Redirect to a confirmation page or any other page
            header("Location: racinghubhome.php");
            exit();
        } else {
            // If insertion fails, set error message
            $errorMsg = "Failed to create account. Please try again later.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>The Racing Puzzle - Create Account</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .error-message {
            color: red;
        }

        body {
            padding: 20px;
            background-image: url('../puzzle.png'); /* Add background image */
            background-size: cover; /* Ensure the background image covers the entire screen */
            background-size: 40%;
        }

        .container {
    text-align: center; /* Center text within container */
    background-color: rgba(255, 255, 255, 0.8); /* Set container background to white with semi-transparency */
    padding: 20px; /* Add padding */
    border-radius: 10px; /* Add some rounding to the corners */
    max-width: 700px; /* Limit the width of the container */
    margin: 0 auto; /* Center the container horizontally */
}

    </style>
</head>
<body>
<div class="container">
    <h2 class="text-center">Create Your Racing Puzzle Account</h2>

    <?php
    // Check if errorMsg is set and not empty
    if (!empty($errorMsg)) {
        echo "<div class='error-message'>$errorMsg</div>";
    } ?>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <form method="post" action="" class="needs-validation" novalidate>
                <div class="form-group">
                    <label for="firstName">First Name:</label>
                    <input type="text" id="firstName" name="firstName" class="form-control" required>
                    <div class="invalid-feedback">Please enter your first name.</div>
                </div>

                <div class="form-group">
                    <label for="lastName">Last Name:</label>
                    <input type="text" id="lastName" name="lastName" class="form-control" required>
                    <div class="invalid-feedback">Please enter your last name.</div>
                </div>

                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" class="form-control" required>
                    <div class="invalid-feedback">Please choose a username.</div>
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                    <div class="invalid-feedback">Please enter a password.</div>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                    <div class="invalid-feedback">Please enter a valid email address.</div>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Create Account</button>
            </form>
            <p class="text-center m-3">Already have an account? <a href="login.php">Login</a> 
    <p class="text-center m-3">Forgot Password <a href="forgot_password.php">Reset Password</a>
        </div>
    </div>
</div>
</div>

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