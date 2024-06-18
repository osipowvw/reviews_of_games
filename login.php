<?php
// Подключение к базе данных
$host = "localhost"; // Имя хоста
$user = "root"; // Имя пользователя базы данных
$password = "deputat1703"; // Пароль пользователя базы данных
$database = "kurs"; // Имя базы данных

$connection = mysqli_connect($host, $user, $password, $database);

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Получение данных из формы
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Хэширование введенного пароля
    $hashed_password = hash('sha256', $password);

    // Проверка, существует ли пользователь с таким же логином и паролем
    $check_query = "SELECT * FROM users WHERE username='$username' AND password='$hashed_password'";
    $check_result = mysqli_query($connection, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        session_start(); // Начало сессии
        $_SESSION['username'] = $username; // Установка переменной сессии
        echo "success"; // Отправка сообщения об успешном входе
    } else {
        echo "Неправильный логин или пароль."; // Отправка сообщения об ошибке
    }    
}

mysqli_close($connection);
?>
