<?php 
include('includes/functions.php');
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    if (isset($_SESSION['path']) and isset($_SESSION['type'])){
        $path = $_SESSION['path'];
        $type = $_SESSION['type'];
        if (!file_exists($path)){
            $error = 'Ошибка получения файла';
        }else{
        header("Content-Type: application/download");
        switch ($type) {
            case 'image/gif':
                $img = imageCreateFromGif($path);
                if ($_SESSION['blur'] == 1){
                    blur($img);
                }
                if ($_SESSION['wb'] == 1){
                    bw($img);
                }
                if ($_SESSION['invers'] == 1){
                    negate($img);
                }
                if (isset($_SESSION['crop']) and $_SESSION['crop'] == 1){
                    $img = crop($img, imagesx($img), imagesy($img));
                }
                $img = rotate($img, (int)$_SESSION['rotation']);
                $img = scale($img);
                header("Content-Disposition: attachment; filename=your_new_image.gif");
                header('Content-Type: image/gif');
                imagepgif($img);
                imagedestroy($img);
                break;
            case 'image/jpg':
            case 'image/jpeg':
                $img = imageCreateFromJpeg($path);
                if ($_SESSION['blur'] == 1){
                    blur($img);
                }
                if ($_SESSION['wb'] == 1){
                    bw($img);
                }
                if ($_SESSION['invers'] == 1){
                    negate($img);
                }
                if (isset($_SESSION['crop']) and $_SESSION['crop'] == 1){
                    $img = crop($img, imagesx($img), imagesy($img));
                }
                $img = rotate($img, (int)$_SESSION['rotation']);
                $img = scale($img);
                header("Content-Disposition: attachment; filename=your_new_image.jpeg");
                header('Content-Type: image/jpeg');
                imagejpeg($img);
                imagedestroy($img);
                break;
            case 'image/png':
                $img = imageCreateFromPng($path);
                if ($_SESSION['blur'] == 1){
                    blur($img);
                }
                if ($_SESSION['wb'] == 1){
                    bw($img);
                }
                if ($_SESSION['invers'] == 1){
                    negate($img);
                }
                if (isset($_SESSION['crop']) and $_SESSION['crop'] == 1){
                    $img = crop($img, imagesx($img), imagesy($img));
                }
                $img = rotate($img, (int)$_SESSION['rotation']);
                $img = scale($img);
                header("Content-Disposition: attachment; filename=your_new_image.png");
                header('Content-Type: image/png');
                imagepng($img);
                imagedestroy($img);
                break;
            }
        }}
}
?>


<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Загрузка изображения</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
    <form enctype="multipart/form-data" action="download.php" method="post">
        <h3>Загрузка изображения</h3>
        <img src="img.php" alt="здесь должно быть ваше фото" <?php 
        if (isset($_SESSION['width']) and isset($_SESSION['width'])){
            if ($_SESSION['width'] > 150){
                $width = 150;
                $height = (int)($_SESSION['height'] / ($_SESSION['width'] / 150));
            }
            if ($_SESSION['height'] > 150){
                $height = 150;
                $width = (int)($_SESSION['width'] / ($_SESSION['height'] / 150));
            }
        }else{
            $height = 150;
            $width = 150;
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
</body>
</html>