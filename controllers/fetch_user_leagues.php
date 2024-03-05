<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect the user to the login page or handle the situation appropriately
    exit('User is not logged in');
}

try {
    // Connect to the SQLite database
    $pdo = new PDO('sqlite:../theracinghub.db');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare the SQL statement to fetch user's leagues with member count
    $sql = "SELECT l.league_id, l.league_name, l.league_code, 
        (SELECT COUNT(*) FROM league_memberships WHERE league_id = l.league_id) AS members
        FROM leagues l 
        JOIN league_memberships lm ON l.league_id = lm.league_id
        WHERE lm.user_id = :user_id
        GROUP BY l.league_id";


    // Bind parameters and execute the statement
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();

    // Fetch leagues as associative array
    $leagues = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Encode leagues as JSON
    $jsonResponse = json_encode($leagues);

    // Set the appropriate content type header
    header('Content-Type: application/json');

    // Output the JSON response
    echo $jsonResponse;
} catch (PDOException $e) {
    // Handle database connection or query errors
    exit('Database error: ' . $e->getMessage());
}
?>


