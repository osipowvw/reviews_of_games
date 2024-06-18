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
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Проверка, существует ли пользователь с таким же логином или email
    $check_query = "SELECT * FROM users WHERE username='$username' OR email='$email'";
    $check_result = mysqli_query($connection, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        // Отправляем сообщение об ошибке в формате JSON
        echo json_encode(array("error" => "Пользователь с таким именем или адресом электронной почты уже существует."));
    } else {
        // Хэширование пароля
        $hashedPassword = hash('sha256', $password);
        
        // Добавление нового пользователя в базу данных
        $insert_query = "INSERT INTO users (username, email, password, date_registred) VALUES ('$username', '$email', '$hashedPassword', NOW())";
        $insert_result = mysqli_query($connection, $insert_query);

        if ($insert_result) {
            // Регистрация успешна, перенаправляем на главную страницу
            header("Location: home.html");
            exit(); // После перенаправления выходим из скрипта, чтобы избежать дальнейшего выполнения кода
        } else {
            // Выводим сообщение об ошибке
            echo "Ошибка регистрации. Повторите попытку позже.";
        }
    }
}

mysqli_close($connection);
?>
