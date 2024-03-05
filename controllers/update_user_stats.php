<?php

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection code
include '../helpers/db_connection.php';

// Debugging: Output a message to indicate the script execution
echo "update_user_stats.php is executing...\n";

// Query to update user stats
$query = "UPDATE users_stats SET total_profit = (
            SELECT SUM(profit) FROM Results WHERE User_ID = :user_id
          )
          WHERE User_ID = :user_id";

// Debugging: Output the query to see if it's formed correctly
echo "Query: $query\n";

// Prepare the SQL statement
$stmt = $pdo->prepare($query);

// Get the user ID
$user_id = $_SESSION['user_id'];

// Bind the user ID parameter
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

// Execute the query
if ($stmt->execute()) {
    // Debugging: Output a success message
    echo "User stats updated successfully.\n";
} else {
    // Debugging: Output an error message if the query fails
    echo "Error updating user stats: " . $stmt->errorInfo()[2] . "\n";
}

?>

