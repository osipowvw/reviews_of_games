<?php
// Подключение к базе данных
$host = "localhost"; 
$user = "root"; 
$password = "deputat1703"; 
$database = "kurs"; 

$connection = mysqli_connect($host, $user, $password, $database);

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Получение game_id из GET-запроса
if(isset($_GET['game_id'])) {
    $gameId = mysqli_real_escape_string($connection, $_GET['game_id']);

    // Выполняем запрос на получение средней оценки игры
    $query = "SELECT average_rating FROM games WHERE game_id = '$gameId'";
    $result = mysqli_query($connection, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $averageRating = $row['average_rating'];

        // Возвращаем среднюю оценку в формате JSON
        echo json_encode($averageRating);
    } else {
        echo "Ошибка: Средняя оценка не найдена.";
    }
} else {
    echo "Ошибка: game_id не был получен.";
}

mysqli_close($connection);
?>
