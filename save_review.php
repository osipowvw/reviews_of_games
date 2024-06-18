<?php
session_start();

// Подключение к базе данных
$host = "localhost"; 
$user = "root"; 
$password = "deputat1703"; 
$database = "kurs"; 

$connection = mysqli_connect($host, $user, $password, $database);

if (!$connection) {
    die(json_encode(["success" => false, "message" => "Connection failed: " . mysqli_connect_error()]));
}

// Получение данных отзыва из POST-запроса
$data = json_decode(file_get_contents("php://input"), true);

// Получение user_id из сессии
if(isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $query = "SELECT user_id FROM users WHERE username='$username'";
    $result = mysqli_query($connection, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $user_id = $row['user_id'];

        // Добавление отзыва в базу данных
        $gameId = mysqli_real_escape_string($connection, $data['game_id']);
        $userId = mysqli_real_escape_string($connection, $user_id);
        $rating = mysqli_real_escape_string($connection, $data['rating']);
        $comment = mysqli_real_escape_string($connection, $data['comment']);

        $insert_query = "INSERT INTO reviews (game_id, user_id, rating, comment, date_posted) VALUES ('$gameId', '$userId', '$rating', '$comment', NOW())";
        if(mysqli_query($connection, $insert_query)) {
            // После успешного сохранения отзыва, обновляем среднюю оценку
            $update_query = "
                UPDATE games g
                JOIN (
                    SELECT game_id, AVG(rating) AS avg_rating
                    FROM reviews
                    GROUP BY game_id
                ) r ON g.game_id = r.game_id
                SET g.average_rating = r.avg_rating
                WHERE g.game_id = '$gameId'";
            
            if(mysqli_query($connection, $update_query)) {
                echo json_encode(["success" => true, "message" => "Отзыв успешно сохранен и средняя оценка обновлена."]);
            } else {
                echo json_encode(["success" => false, "message" => "Ошибка при обновлении средней оценки: " . mysqli_error($connection)]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Ошибка при сохранении отзыва: " . mysqli_error($connection)]);
        }
        
    } else {
        echo json_encode(["success" => false, "message" => "Ошибка при получении данных пользователя."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Пользователь не авторизован."]);
}

mysqli_close($connection);
?>
