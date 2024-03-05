<?php

session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect or handle unauthorized access
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
        $date = $_POST['date'] ?? '';
        $racecourse = $_POST['racecourse'] ?? '';
        $selection = $_POST['selection'] ?? '';
        $jockey = $_POST['jockey'] ?? '';
        $trainer = $_POST['trainer'] ?? '';
        $stake = $_POST['stake'] ?? '';
        $odds = $_POST['odds'] ?? '';
        $outcome = $_POST['outcome'] ?? '';
        $return = $_POST['return'] ?? '';
        $profit = $_POST['profit'] ?? '';

        // Prepare SQL statement for insertion
        $stmt = $db->prepare("INSERT INTO Results (User_ID, Date, Racecourse, Selection, Jockey, Trainer, Stake, Odds, Outcome, Return, Profit) 
                              VALUES (:userID, :date, :racecourse, :selection, :jockey, :trainer, :stake, :odds, :outcome, :return, :profit)");

        // Bind values to parameters
        $stmt->bindValue(':userID', $userID, SQLITE3_INTEGER);
        $stmt->bindValue(':date', $date, SQLITE3_TEXT);
        $stmt->bindValue(':racecourse', $racecourse, SQLITE3_TEXT);
        $stmt->bindValue(':selection', $selection, SQLITE3_TEXT);
        $stmt->bindValue(':jockey', $jockey, SQLITE3_TEXT);
        $stmt->bindValue(':trainer', $trainer, SQLITE3_TEXT);
        $stmt->bindValue(':stake', $stake, SQLITE3_TEXT);
        $stmt->bindValue(':odds', $odds, SQLITE3_TEXT);
        $stmt->bindValue(':outcome', $outcome, SQLITE3_TEXT);
        $stmt->bindValue(':return', $return, SQLITE3_TEXT);
        $stmt->bindValue(':profit', $profit, SQLITE3_TEXT);

        // Execute SQL statement
        $result = $stmt->execute();

        // Check if the execution was successful
        if (!$result) {
            // Error handling if insertion fails
            $error_message = 'Failed to insert data into Results table';
            echo json_encode(array('error' => $error_message));
        } else {
            // Update user statistics
            require_once 'update_user_stats.php';

            // Return success message
            echo json_encode(array('success' => 'Data inserted successfully into Results table'));
        }
    } else {
        echo json_encode(array('error' => 'User not found'));
    }
} else {
    echo json_encode(array('error' => 'Failed to retrieve User_ID'));
}

// Close database connection
$db->close();

?>

