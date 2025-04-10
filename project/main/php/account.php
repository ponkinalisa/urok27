<?php 
require_once 'config.php';
session_start();
if (!isset($_SESSION['id']) or !isset($_SESSION['login'])){
    die('Сначала войдите в аккаунт...');
}
try{
    $sql = "SELECT * FROM users WHERE id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['user_id' => $_SESSION['id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}catch (PDOException $e) {
    $error =  'Возникла неожиданная ошибка обращения к базе данных';
}
?>


<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Главная</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
</head>
<body>
    <div class="btn" onclick="download()">Редактировать</div>
    <form>
    <h3>Личный кабинет</h3>
        <?php 
        echo "<p>Имя пользователя:<br> " . $user['login'] . "</p>";
        echo "<p>Дата начала использования приложения:<br> " . $user['date'] . "</p>";
        ?>
        <div onclick="my_photo()" class="button">Мои фото</div>
</form>
    <?php 
    if (($error)){
        echo "<div class='error'>$error</div>";
    };
    ?>
    <script>
        function download(){
            window.location.href = "start_screen.php";
        }
        function my_photo(){
            window.location.href = "my_photo.php";
        }

    </script>
</body>
</html>