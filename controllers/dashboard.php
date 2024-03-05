<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection code
include '../helpers/db_connection.php';

// Get the logged-in user's ID from the session
if(isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

}

// Query to calculate the total profit for the logged-in user
$query = "SELECT SUM(profit) AS total_profit FROM Results WHERE User_ID = :user_id";
$stmt = $pdo->prepare($query);
$stmt->execute(['user_id' => $user_id]);

// Fetch the total profit
$total_profit_row = $stmt->fetch(PDO::FETCH_ASSOC);
$total_profit = $total_profit_row['total_profit'];

if(isset($total_profit)) {

}
?>




