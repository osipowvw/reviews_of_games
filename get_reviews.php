<?php
session_start();
$host = "localhost";
$user = "root";
$password = "deputat1703";
$database = "kurs";

$connection = mysqli_connect($host, $user, $password, $database);

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_GET['game_id'])) {
    $game_id = $_GET['game_id'];

    $query = "SELECT reviews.review_id, reviews.rating, reviews.comment, users.username 
              FROM reviews 
              JOIN users ON reviews.user_id = users.user_id 
              WHERE reviews.game_id = '$game_id'";
    $result = mysqli_query($connection, $query);

    if ($result) {
        $reviews = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $reviews[] = $row;
        }
        echo json_encode($reviews);
    } else {
        echo "Ошибка при выполнении запроса: " . mysqli_error($connection);
    }
}

mysqli_close($connection);
?>
