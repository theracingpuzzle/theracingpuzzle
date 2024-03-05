<?php

session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Return error JSON response if user is not logged in
    echo json_encode(array('error' => 'User not logged in'));
    exit(); // Stop further execution
}

// Get the username from the session
$username = $_SESSION['username'];

// Connect to SQLite database
$db = new SQLite3('../theracinghub.db');

// Prepare SQL statement to retrieve User_ID based on username
$stmt = $db->prepare("SELECT User_ID FROM Users WHERE username = :username");
$stmt->bindValue(':username', $username, SQLITE3_TEXT);

// Execute SQL statement
$result = $stmt->execute();

// Check if the query was successful
if ($result) {
    // Fetch the User_ID
    $row = $result->fetchArray(SQLITE3_ASSOC);
    if ($row) {
        $userID = $row['User_ID'];

        // Get form data
        $horseName = $_POST['horseName'] ?? '';
        $trainerName = $_POST['trainerName'] ?? '';
        $comment = $_POST['comment'] ?? '';

        // Prepare SQL statement for insertion
        $stmt = $db->prepare("INSERT INTO Tracker (User_ID, Horse, Trainer, Comment) VALUES (:userID, :horseName, :trainerName, :comment)");
        $stmt->bindValue(':userID', $userID, SQLITE3_TEXT);
        $stmt->bindValue(':horseName', $horseName, SQLITE3_TEXT);
        $stmt->bindValue(':trainerName', $trainerName, SQLITE3_TEXT);
        $stmt->bindValue(':comment', $comment, SQLITE3_TEXT);

        // Execute SQL statement
        $result = $stmt->execute();

        if ($result) {
            // Return the inserted data as JSON
            echo json_encode(array(
                'horseName' => $horseName,
                'trainerName' => $trainerName,
                'comment' => $comment,
                'userID' => $userID
            ));
        } else {
            // Error handling if insertion fails
            echo json_encode(array('error' => 'Failed to insert data'));
        }
    } else {
        // Handle case where username is not found
        echo json_encode(array('error' => 'Username not found'));
    }
} else {
    // Error handling if query fails
    echo json_encode(array('error' => 'Failed to retrieve User_ID'));
}

// Close database connection
$db->close();

?>


