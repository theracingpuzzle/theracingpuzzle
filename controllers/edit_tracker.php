<?php
// Include database connection code
include('../theracinghub.db');

// Check if Tracker_ID is provided in the URL
if(isset($_GET['id'])) {
    $tracker_id = $_GET['id'];
    
    // Query to retrieve data associated with the provided Tracker_ID
    $query = "SELECT * FROM Tracker WHERE Tracker_ID = :tracker_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':tracker_id', $tracker_id);
    $stmt->execute();

    // Fetch the data
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the row exists
    if($row) {
        // Populate form fields with data for editing
        $horseName = $row['Horse'];
        $trainerName = $row['Trainer'];
        $comment = $row['Comment'];

        // Include your HTML code for the edit form here, with form fields populated with $horseName, $trainerName, $comment
    } else {
        // Redirect to the main page or display an error message
        header("Location: index.php");
        exit();
    }
} else {
    // Redirect to the main page or display an error message
    header("Location: index.php");
    exit();
}
?>
