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
    $id = $user['id'];

    $sql = "SELECT * FROM images";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $cards = $stmt->fetchAll();
}catch (PDOException $e) {
    $error =  'Возникла неожиданная ошибка обращения к базе данных';
}
?>


<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Мои фото</title>
    <link rel="stylesheet" type="text/css" href="../css/photo.css">
</head>
<body>
<div class="btn" onclick="download()">Редактировать</div>
    <main>
        <?php 
        foreach($cards as $card){
            $width = getimagesize('../user_img/'.$user['login'].'/'. $card['name'])[0];
            $height = getimagesize('../user_img/'.$user['login'].'/'. $card['name'])[1];
            if ($width > 150){
                $width = 150;
                $height = (int)($height / ($width / 150));
            }
            if ($height > 150){
                $height = 150;
                $width = (int)($width / ($height / 150));
            }
            else{
                $height = 150;
                $width = (int)($width / ($height / 150));
            }
            
            echo '<div class="card">';
            echo '<img src="../user_img/'.$user['login'].'/'. $card['name'] .'" width="'. $width. 'px" height="'. $height .'px">';
            echo '<h4>' . $card['name'] . '</h4>';
            echo '<p>' . $card['description'] . '</p>';
            echo '</div>';
        }
        ?>
    </main>
    <?php 
    if (($error)){
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