<?php
session_start(); // Начало сессии

// Удаление всех переменных сессии
$_SESSION = array();

// Уничтожение сессии
session_destroy();

// Перенаправление на главную страницу
header("Location: home.html");
exit();
?>
