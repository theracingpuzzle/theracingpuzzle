<?php
// Establish connection to SQLite database
try {
    $pdo = new PDO('sqlite:../theracinghub.db');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Handle connection error
    die("Connection failed: " . $e->getMessage());
}

// Your PHP logic goes here
// For example, querying the database, fetching results, etc.
?>


