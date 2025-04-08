<?php
session_start();
function crop($img, $oldWidth, $oldHeight){
    $width = $_SESSION['width'];
    $height = $_SESSION['height'];
    $top = $_SESSION['top'];
    $left = $_SESSION['left'];
    $cw = $_SESSION['cw'];
    $ch = $_SESSION['ch'];
    echo $width;
    echo $height;
}

$img = imagecreatefromjpeg('donut_bg.jpg');  // Загружаем изображение PNG
crop($img, imagesx($img), imagesy($img));

?>
