<?php
session_start();

// Уничтожаем сессию
session_destroy();

// Перенаправляем на страницу входа
header("Location: login.php");
exit;
?>