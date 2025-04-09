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

    $sql = "SELECT * FROM images WHERE 1";

    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
        if (!empty($_POST)){
            if ($_POST['blur']){
                $sql .= ' AND blur = 1';
            }
            if ($_POST['bw']){
                $sql .= ' AND bw = 1';
            }
            if ($_POST['invers']){
                $sql .= ' AND inversion = 1';
            }
            if ($_POST['crop']){
                $sql .= ' AND crop = 1';
            }
            if ($_POST['rotation']){
                $sql .= ' AND rotation = 1';
            };
            if ($_POST['scale']){
                $sql .= ' AND scale = 1';
            };
        }
    }
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
    <title>Все фото</title>
    <link rel="stylesheet" type="text/css" href="../css/photo.css">
</head>
<body>
<div class="btn" onclick="download()">Редактировать</div>
<form action="all_photo.php" method="post">
    <h3>Сортировки</h3>
    <div class="item">
        <input type="checkbox" name="blur" id="blur" value="blur" class="toggle">
        <label for="blur">С размытием</label>
    </div>
    <div class="item">
        <input type="checkbox" name="bw" id="bw" value="bw" class="toggle">
        <label for="bw">Черно-белое</label>
    </div>
    <div class="item">
        <input type="checkbox" name="invers" id="invers" value="invers" class="toggle">
        <label for="invers">С инверсией</label>
    </div>
    <div class="item">
        <input type="checkbox" name="crop" id="crop" value="crop" class="toggle">
        <label for="crop">Обрезанное</label>
    </div>
    <div class="item">
        <input type="checkbox" name="rotation" id="rotation" value="rotation" class="toggle">
        <label for="rotation">Повернутое</label>
    </div>
    <div class="item">
        <input type="checkbox" name="scale" id="scale" value="scale" class="toggle">
        <label for="scale">Измененный масштаб</label>
    </div>
    <input type="submit" value="Применить сортировки">
</form>
    <main>
        <?php 
        foreach($cards as $card){
            $width = getimagesize('../user_img/'.$user['login'].'/'. $card['name'])[0];
            $height = getimagesize('../user_img/'.$user['login'].'/'. $card['name'])[1];
            if ($width > 150){
                $height = (int)($height / ($width / 150));
                $width = 150;
            }
            if ($height > 150){
                $width = (int)($width / ($height / 150));
                $height = 150;
            }
            else{
                $width = (int)($width / ($height / 150));
                $height = 150;
            }
            
            echo '<div class="card"">';
            echo '<img src="../user_img/'.$user['login'].'/'. $card['name'] .'" width="'. $width. 'px" height="'. $height .'px">';
            echo '<h4>' . $card['name'] . '</h4>';
            echo '<p>' . $card['description'] . '</p>';
            echo '<p>' . $card['date'] . '</p>';
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