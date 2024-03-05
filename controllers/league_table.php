<?php
// Establish a connection to the SQLite database
$db = new SQLite3('../theracinghub.db');

// Retrieve the league ID from the URL parameter
$leagueID = $_GET['leagueID'] ?? null;
if ($leagueID === null) {
    die("League ID is missing.");
}

// Retrieve the league name from the database
$query = "SELECT league_name FROM leagues WHERE league_id = :leagueID";
$stmt = $db->prepare($query);
if (!$stmt) {
    die("Error preparing query: " . $db->lastErrorMsg());
}
$stmt->bindValue(':leagueID', $leagueID, SQLITE3_INTEGER);
$result = $stmt->execute();
if (!$result) {
    die("Error executing query: " . $db->lastErrorMsg());
}

// Fetch the league name
$leagueName = $result->fetchArray(SQLITE3_ASSOC)['league_name']; // Change 'name' to 'league_name'
if (!$leagueName) {
    die("League name not found for ID: $leagueID");
}

// Close the first query
$stmt->close();

// Close the database connection
$db->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>League: <?php echo $leagueName; ?></title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="mt-4 mb-3">League: <?php echo $leagueName; ?></h1>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Position</th>
                        <th>Username</th>
                        <th>Total Profit</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Establish a new connection to retrieve user data
                    $db = new SQLite3('../theracinghub.db');

                    $query = "SELECT users.username, users_stats.total_profit
                              FROM users
                              JOIN league_memberships ON users.user_id = league_memberships.user_id
                              JOIN users_stats ON users.user_id = users_stats.user_id
                              WHERE league_memberships.league_id = :leagueID
                              ORDER BY users_stats.total_profit DESC";
                    $stmt = $db->prepare($query);
                    if (!$stmt) {
                        die("Error preparing query: " . $db->lastErrorMsg());
                    }
                    $stmt->bindValue(':leagueID', $leagueID, SQLITE3_INTEGER);
                    $result = $stmt->execute();
                    if (!$result) {
                        die("Error executing query: " . $db->lastErrorMsg());
                    }

                    $position = 1;
                    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                        echo "<tr>";
                        echo "<td>$position</td>";
                        echo "<td>{$row['Username']}</td>";
                        echo "<td>{$row['total_profit']}</td>";
                        echo "</tr>";
                        $position++;
                    }

                    // Close the database connection
                    $db->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

