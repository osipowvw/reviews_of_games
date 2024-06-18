<?php
session_start();
$host = "localhost";
$user = "root";
$password = "deputat1703";
$database = "kurs";

$connection = mysqli_connect($host, $user, $password, $database);

if (!$connection) {
    die(json_encode(["success" => false, "message" => "Connection failed: " . mysqli_connect_error()]));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $review_id = $_POST['review_id'];

    // Получаем данные отзыва перед удалением
    $review_query = "SELECT game_id FROM reviews WHERE review_id = '$review_id'";
    $review_result = mysqli_query($connection, $review_query);
    
    if ($review_result && mysqli_num_rows($review_result) > 0) {
        $review_data = mysqli_fetch_assoc($review_result);
        $game_id = $review_data['game_id'];
        
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
            $query = "DELETE FROM reviews WHERE review_id = '$review_id'";
            $result = mysqli_query($connection, $query);
            if ($result) {
                // После успешного удаления отзыва, обновляем среднюю оценку
                $update_query = "
                    UPDATE games g
                    JOIN (
                        SELECT game_id, AVG(rating) AS avg_rating
                        FROM reviews
                        GROUP BY game_id
                    ) r ON g.game_id = r.game_id
                    SET g.average_rating = r.avg_rating
                    WHERE g.game_id = '$game_id'";
                
                if (mysqli_query($connection, $update_query)) {
                    echo json_encode(["success" => true, "message" => "Отзыв успешно удален и средняя оценка обновлена."]);
                } else {
                    echo json_encode(["success" => false, "message" => "Ошибка при обновлении средней оценки: " . mysqli_error($connection)]);
                }
            } else {
                echo json_encode(["success" => false, "message" => "Ошибка при удалении отзыва: " . mysqli_error($connection)]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Недостаточно прав для удаления отзыва."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Отзыв не найден."]);
    }
}

mysqli_close($connection);
?>
