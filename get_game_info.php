<?php
$host = "localhost"; 
$user = "root"; 
$password = "deputat1703"; 
$database = "kurs"; 

$connection = mysqli_connect($host, $user, $password, $database);

if (!$connection) {
    die(json_encode(["success" => false, "message" => "Connection failed: " . mysqli_connect_error()]));
}

$game_id = $_GET['game_id'];

$query = "SELECT * FROM games WHERE game_id = '$game_id'";
$result = mysqli_query($connection, $query);

if ($result) {
    $gameInfo = mysqli_fetch_assoc($result);
    echo json_encode($gameInfo);
} else {
    echo json_encode(["success" => false, "message" => "Ошибка при выполнении запроса: " . mysqli_error($connection)]);
}

mysqli_close($connection);
?>
