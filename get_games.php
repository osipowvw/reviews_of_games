<?php
// Подключение к базе данных
$conn = new mysqli('localhost', 'root', 'deputat1703', 'kurs');

// Проверка соединения
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

// Запрос к базе данных для получения списка игр
$sql = "SELECT game_id, title, image_url FROM games"; // Выбираем только необходимые поля
$result = $conn->query($sql);

// Формирование массива игр
$games = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $games[] = $row;
    }
}

// Закрытие соединения
$conn->close();

// Возвращаем список игр в формате JSON
header('Content-Type: application/json');
echo json_encode($games);
?>
