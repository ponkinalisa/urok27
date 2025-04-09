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

    // Хешируем пароль
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Проверка уникальности логина и e-mail
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE login = :username");
    $stmt->execute(['username' => $username]);
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        $error = 'Логин уже используется!';
    }
    if (!isset($error)){
        // Сохраняем данные в базе данных
        $sql = "INSERT INTO users (login, password, date) VALUES (:username, :password, CURRENT_DATE())";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['username' => $username, 'password' => $hashedPassword]);

        $sql = "SELECT * FROM users WHERE login = :username";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['username' => $username]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        $_SESSION['id'] = $admin['id'];
        $_SESSION['login'] = $admin['login'];

        echo "Пользователь успешно зарегистрирован.";
        echo '<div class="btn" onclick="download()">Редактировать</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Регистрация</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">

</head>
<body>
    <form action="registration.php" method="post">
        <h3>Регистрация</h3>
        <input type="text" name="login" placeholder="Введите логин..." required>
        <input type="password" name="password" placeholder="Введите пароль..." required>
        <a href="login.php">Уже есть аккаунт?</a>
        <input type="submit" value="Зарегистрироваться">
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
