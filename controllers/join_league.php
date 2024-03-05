<?php
session_start(); // Start the session

// Include database connection code
include '../helpers/db_connection.php';

// Get the league code from the AJAX request
$leagueCode = $_POST['leagueCode'];

try {
    // Check if the league code exists
    $stmt = $pdo->prepare("SELECT league_id FROM leagues WHERE league_code = :leagueCode");
    $stmt->execute([':leagueCode' => $leagueCode]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        // League code does not exist, return an error message
        echo json_encode(['success' => false, 'error' => 'Invalid league code. Please try again.']);
        exit;
    }

    // League code exists, add the user to the league
    $leagueID = $row['league_id'];
    $userID = $_SESSION['user_id'];
    $role = 'member';

    // Check if the user is already a member of the league
    $stmt = $pdo->prepare("SELECT * FROM league_memberships WHERE league_id = :leagueID AND user_id = :userID");
    $stmt->execute([':leagueID' => $leagueID, ':userID' => $userID]);
    $existingMembership = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existingMembership) {
        // User is already a member of the league, return an error message
        echo json_encode(['success' => false, 'error' => 'You are already a member of this league.']);
        exit;
    }

    // Insert the user into the league memberships table
    $stmt = $pdo->prepare("INSERT INTO league_memberships (league_id, user_id, role) VALUES (:leagueID, :userID, :role)");
    $stmt->execute([':leagueID' => $leagueID, ':userID' => $userID, ':role' => $role]);

    // Return a success message
    echo json_encode(['success' => true, 'message' => 'Joined the league successfully!']);
} catch (PDOException $e) {
    // Return an error message
    echo json_encode(['success' => false, 'error' => 'Error joining the league: ' . $e->getMessage()]);
}
?>
