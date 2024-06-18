<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'db_connection.php';
    
    $game_id = $_POST['game_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];
    $username = $_SESSION['username'];
    
    $query = "INSERT INTO reviews (game_id, rating, comment, username) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiss", $game_id, $rating, $comment, $username);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
    
    $stmt->close();
    $conn->close();
}
?>
