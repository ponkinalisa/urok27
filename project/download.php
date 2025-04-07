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
                $img = rotate($img, (int)$_SESSION['rotation']);
                $img = scale($img);
                header("Content-Disposition: attachment; filename=your_new_image.png");
                header('Content-Type: image/png');
                imagepng($img);
                imagedestroy($img);
                break;
            }
            unlink($path);
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
        <img src="img.php" alt="здесь должно быть ваше фото" width='150px' height='150px'>
        <input type="submit" value="Скачать">
    </form>
    <?php 
    if (isset($error)){
        echo "<div class='error'>$error</div>";
    };
    ?>
</body>
</html>