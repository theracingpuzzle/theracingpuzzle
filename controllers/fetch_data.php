<?php
session_start(); // Start the session

// Check if the username session variable is set
if (!isset($_SESSION['username'])) {
    // Redirect or handle unauthorized access
    echo json_encode(array('error' => 'User not logged in'));
    exit(); // Stop further execution
}

// Get the username from the session
$username = $_SESSION['username'];

// Connect to SQLite database
$db = new SQLite3('../theracinghub.db');

// Prepare SQL statement to select Tracker entries based on User_ID
$stmt = $db->prepare("SELECT Horse, Trainer, Comment FROM Tracker WHERE User_ID = (SELECT User_ID FROM Users WHERE username = :username)");
$stmt->bindValue(':username', $username, SQLITE3_TEXT);

// Execute SQL statement
$result = $stmt->execute();

// Check if the query was successful
if ($result) {
    // Fetch all rows as an associative array
    $rows = array();
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $rows[] = $row;
    }

    // Return the fetched data as JSON
    echo json_encode($rows);
} else {
    // Error handling if query fails
    echo json_encode(array('error' => 'Failed to fetch data'));
}

// Close database connection
$db->close();
?>

