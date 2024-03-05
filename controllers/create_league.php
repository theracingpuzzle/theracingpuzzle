<?php
session_start(); // Start the session

// Include database connection code
include '../helpers/db_connection.php';

// Get the league name from the AJAX request
$leagueName = $_POST['leagueName']; // Adjusted to match the form field name

// Generate a random 6-digit code for the league
$leagueCode = mt_rand(100000, 999999);

try {
    // Begin a transaction
    $pdo->beginTransaction();

    // Insert the league name and code into the leagues table
    $query = "INSERT INTO leagues (league_name, league_code) VALUES (:leagueName, :leagueCode)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':leagueName' => $leagueName, ':leagueCode' => $leagueCode]);

    // Add the current user to the league as the creator/administrator
    $userID = $_SESSION['user_id'];
    $role = 'admin';
    $leagueID = $pdo->lastInsertId(); // Get the last inserted league ID
    $query = "INSERT INTO league_memberships (league_id, user_id, role) VALUES (:leagueID, :userID, :role)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':leagueID' => $leagueID, ':userID' => $userID, ':role' => $role]); // Corrected placeholder name

    // Commit the transaction
    $pdo->commit();

    // Return a success message
    echo json_encode(['success' => true, 'message' => 'League created successfully!', 'leagueCode' => $leagueCode]);
} catch (PDOException $e) {
    // Roll back the transaction on error
    $pdo->rollBack();

    // Return an error message
    echo json_encode(['success' => false, 'error' => 'Failed to create league: ' . $e->getMessage()]);
}
?>

