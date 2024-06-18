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

// Получение game_id из POST-запроса
if(isset($_POST['game_id'])) {
    $gameId = mysqli_real_escape_string($connection, $_POST['game_id']);
    echo "gameId: " . $gameId; // Отладочное сообщение

    $query = "SELECT AVG(rating) AS average_rating FROM reviews WHERE game_id = '$gameId'";
$result = mysqli_query($connection, $query);
echo "Number of rows: " . mysqli_num_rows($result); // Отладочное сообщение

    // Вычисление среднего рейтинга для данной игры
    $query = "SELECT AVG(rating) AS average_rating FROM reviews WHERE game_id = '$gameId'";
    $result = mysqli_query($connection, $query);
    
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $averageRating = $row['average_rating'];
    
        // Проверяем, было ли получено значение среднего рейтинга
        if ($averageRating !== null) {
            echo "Average Rating: " . $averageRating; // Отладочное сообщение
    
            // Обновление записи в таблице games с новым средним рейтингом
            $update_query = "UPDATE games SET rating = '$averageRating' WHERE game_id = '$gameId'";
            if(mysqli_query($connection, $update_query)) {
                echo "Средний рейтинг успешно обновлен.";
            } else {
                echo "Ошибка при обновлении среднего рейтинга: " . mysqli_error($connection);
            }
        } else {
            echo "Ошибка: Средний рейтинг не был получен.";
        }
    } else {
        echo "Ошибка при выполнении запроса на получение среднего рейтинга: " . mysqli_error($connection);
    }
    
    
} else {
    echo "Ошибка: game_id не был получен.";
}

mysqli_close($connection);
?>
