<?php
// Подключение к базе данных
$conn = new mysqli('localhost', 'root', 'deputat1703', 'kurs');

// Проверка соединения
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

// Получение данных из формы добавления игры
$title = $_POST['title'];
$genre = $_POST['genre'];
$release_date = $_POST['release_date'];
$developer = $_POST['developer'];
$platforms = $_POST['platforms'];
// Проверяем, был ли загружен файл изображения
if(isset($_FILES["image"]) && !empty($_FILES["image"]["name"])) {
    // Путь к папке, где будут храниться изображения
    $targetDir = "images/";
    // Имя файла изображения
    $imageName = basename($_FILES["image"]["name"]);
    // Полный путь к загружаемому изображению
    $targetFilePath = $targetDir . $imageName;
    // Проверка, является ли загруженный файл изображением
    $imageFileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
    if($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg" || $imageFileType == "gif") {
        // Перемещаем загруженное изображение в папку images на сервере
        if(move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
            // Успешно загружаем изображение, записываем путь к изображению в базу данных
            $imageUrl = $targetFilePath;
        } else {
            echo "Ошибка при загрузке изображения.";
        }
    } else {
        echo "Допускаются только файлы JPG, JPEG, PNG и GIF.";
    }
} else {
    echo "Изображение не выбрано.";
}

// После этого можно вставить код добавления игры в базу данных с учетом переменной $imageUrl

// Подготовленный запрос для добавления новой игры с учетом изображения
$sql = "INSERT INTO games (title, genre, release_date, developer, platforms, image_url) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssss", $title, $genre, $release_date, $developer, $platforms, $imageUrl);


// Выполнение запроса
if ($stmt->execute()) {
    // Игра успешно добавлена, возвращаем сообщение об успешном добавлении
    echo "Игра успешно добавлена!";
} else {
    // В случае ошибки выводим сообщение
    echo "Ошибка добавления игры: " . $conn->error;
}


// Закрытие соединения
$stmt->close();
$conn->close();
?>
