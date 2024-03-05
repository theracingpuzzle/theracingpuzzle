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
$stmt = $db->prepare("SELECT Date, Racecourse, Selection, Jockey, Trainer, Stake, Odds, Outcome, Return, Profit FROM Results WHERE User_ID = (SELECT User_ID FROM Users WHERE username = :username)");
$stmt->bindValue(':username', $username, SQLITE3_TEXT);

// Execute SQL query
$results = $stmt->execute();

// Initialize an empty array to store the fetched data
$data = array();

// Fetch data row by row
while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
    // Add the fetched row to the data array
    $data[] = $row;
}

// Close the database connection
$db->close();

// Return the fetched data as JSON
echo json_encode($data);

?>

