<?php
session_start();

if(isset($_SESSION['username'])) {
    // Если пользователь вошел в систему, перенаправляем его на личный кабинет
    header("Location: profile.php");
    exit();
}
?>
