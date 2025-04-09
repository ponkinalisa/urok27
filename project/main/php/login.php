<?php
require_once 'config.php';

session_start(); // Начнем сессию для работы с CSRF-токеном

// Проверяем, был ли отправлен POST-запрос с данными для регистрации
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Проверка правильности введённых данных
    $username = trim($_POST['login']); // Удаляем лишние пробелы
    $password = $_POST['password'];

    // Валидация данных
    if (empty($username) || empty($password)) {
        $error = 'Заполните все поля!';
    }

    // Проверка длины пароля
    if (strlen($password) < 6) {
        $error = 'Пароль должен содержать минимум 6 символов!';
    }
    if (!isset($error)){
        $sql = "SELECT * FROM users WHERE login = :username";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['username' => $username]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && password_verify($password, $admin['password'])) {
            // Пароль верен, создаем сессию
            $_SESSION['id'] = $admin['id'];
            $_SESSION['login'] = $admin['login'];
            echo "Вы в аккаунте!";
            echo '<div class="btn" onclick="download()">Редактировать</div>';
        } else {
            $error = "Неверный логин или пароль.";
        }
     }
}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Авторизация</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
</head>
<body>
    <form action="login.php" method="post">
        <h3>Авторизация</h3>
        <input type="text" name="login" placeholder="Введите логин..." required>
        <input type="password" name="password" placeholder="Введите пароль..." required>
        <a href="registration.php">Ещё нет аккаунта?</a>
        <input type="submit" value="Войти">
    </form>
    <?php 
    if (isset($error)){
        echo "<div class='error'>$error</div>";
    };
    ?>
    <script>
        function download(){
            window.location.href = "start_screen.php";
        }
    </script>
</body>
</html>
