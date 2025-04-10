<?php 
require_once 'config.php';
include '../includes/functions.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    if (isset($_SESSION['path']) and isset($_SESSION['type'])){
        $path = $_SESSION['path'];
        $type = $_SESSION['type'];
        $blur = 0;
        $rotation = 0;
        $scale = 0;
        $crop = 0;
        $bw = 0;
        $inversion = 0;
        if (!file_exists($path)){
            $error = 'Ошибка получения файла';
        }else{
        header("Content-Type: application/download");
        switch ($type) {
            case 'image/gif':
                $img = imageCreateFromGif($path);
                if ($_SESSION['blur'] == 1){
                    blur($img);
                    $blur = 1;
                }
                if ($_SESSION['wb'] == 1){
                    bw($img);
                    $bw = 1;
                }
                if ($_SESSION['invers'] == 1){
                    negate($img);
                    $inversion = 1;
                }
                if (isset($_SESSION['crop']) and $_SESSION['crop'] == 1){
                    $img = crop($img, imagesx($img), imagesy($img));
                    $crop = 1;
                }
                if (isset($_SESSION['scale']) and $_SESSION['scale'] != 100){
                    $scale = 1;
                }
                if (isset($_SESSION['rotation']) and $_SESSION['rotation'] != 0){
                    $rotation = 1;
                }
                $img = rotate($img, (int)$_SESSION['rotation']);
                $img = scale($img);
                header("Content-Disposition: attachment; filename=your_new_image.gif");
                header('Content-Type: image/gif');
                imagepgif($img);
                imagepgif($img, $path);
                imagedestroy($img);
                break;
            case 'image/jpg':
            case 'image/jpeg':
                $img = imageCreateFromJpeg($path);
                if ($_SESSION['blur'] == 1){
                    blur($img);
                    $blur = 1;
                }
                if ($_SESSION['wb'] == 1){
                    bw($img);
                    $bw = 1;
                }
                if ($_SESSION['invers'] == 1){
                    negate($img);
                    $inversion = 1;
                }
                if (isset($_SESSION['crop']) and $_SESSION['crop'] == 1){
                    $img = crop($img, imagesx($img), imagesy($img));
                    $crop = 1;
                }
                if (isset($_SESSION['scale']) and $_SESSION['scale'] != 100){
                    $scale = 1;
                }
                if (isset($_SESSION['rotation']) and $_SESSION['rotation'] != 0){
                    $rotation = 1;
                }
                $img = rotate($img, (int)$_SESSION['rotation']);
                $img = scale($img);
                header("Content-Disposition: attachment; filename=your_new_image.jpeg");
                header('Content-Type: image/jpeg');
                imagejpeg($img);
                imagejpeg($img, $path);
                imagedestroy($img);
                break;
            case 'image/png':
                $img = imageCreateFromPng($path);
                if ($_SESSION['blur'] == 1){
                    blur($img);
                    $blur = 1;
                }
                if ($_SESSION['wb'] == 1){
                    bw($img);
                    $bw = 1;
                }
                if ($_SESSION['invers'] == 1){
                    negate($img);
                    $inversion = 1;
                }
                if (isset($_SESSION['crop']) and $_SESSION['crop'] == 1){
                    $img = crop($img, imagesx($img), imagesy($img));
                    $crop = 1;
                }
                if (isset($_SESSION['scale']) and  $_SESSION['scale'] != 100){
                    $scale = 1;
                }
                if (isset($_SESSION['rotation']) and $_SESSION['rotation'] != 0){
                    $rotation = 1;
                }
                $img = rotate($img, (int)$_SESSION['rotation']);
                $img = scale($img);
                header("Content-Disposition: attachment; filename=your_new_image.png");
                header('Content-Type: image/png');
                imagepng($img);
                imagepng($img, $path);
                imagedestroy($img);
                break;
            }
        }
        try{
            $sql = "UPDATE images SET blur = :blur, scale = :scale, crop = :crop, bw = :bw, inversion = :inversion, rotation = :rotation  WHERE user_id = :user_id AND name = :name";
            $stmt = $pdo->prepare($sql);
            $file_name_sep = mb_split("/", $path);
            $stmt->execute(['blur' => $blur, 'bw' => $bw, 'scale' => $scale, 'rotation' => $rotation, 'inversion' => $inversion, 'crop' => $crop, 'user_id' => $_SESSION['id'], 'name' => $file_name_sep[count($file_name_sep) - 1]]);
            $stmt->fetch(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e) {
            $error =  'Возникла неожиданная ошибка обращения к базе данных';
        }
    }
}
?>


<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Загрузка изображения</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
</head>
<body>
<div class="btn" onclick="download()">Редактировать</div>
    <form enctype="multipart/form-data" action="download.php" method="post">
        <h3>Загрузка изображения</h3>
        <img src="img.php" alt="здесь должно быть ваше фото" <?php 
        if (isset($_SESSION['width']) and isset($_SESSION['width'])){
            $width = $_SESSION['width'];
            $height = $_SESSION['height'];
            if ($width > 200){
                $height = (int)($height / ($width / 200));
                $width = 200;
            }
            else if ($height > 200){
                $width = (int)($width / ($height / 200));
                $height = 200;
            }
        }else{
            $height = 200;
            $width = 200;
        }
        echo "width='$width px' height='$height px'";
        ?>>
        <input type="submit" value="Скачать">
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