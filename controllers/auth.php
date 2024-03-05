<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Function to check if the user is logged in
function isLoggedIn() {
    return isset($_SESSION['username']);
}

// Redirect users to the login page if they are not logged in
if (!isLoggedIn()) {
    header("Location: login.php"); // Replace "login.php" with the actual login page URL
    exit(); // Stop further execution
}
?>