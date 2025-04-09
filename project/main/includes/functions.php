<?php 
session_start();
function blur($img){
    imagefilter($img, IMG_FILTER_GAUSSIAN_BLUR);
}

function bw($img){
    imagefilter($img, IMG_FILTER_GRAYSCALE);
}

function negate($img){
    imagefilter($img, IMG_FILTER_NEGATE);
}

function scale($img){
    $width =  (int)((int)$_SESSION['scale'] * 0.01 * imagesx($img));  // Новая ширина
    $height = (int)((int)$_SESSION['scale'] * 0.01 * imagesy($img));  // Новая высота
    $x = 0;
    $y = 0;
    if ($width > 1000){
        $height = (int)($height / ($width / 1000));
        $width = 1000;
    }
    if ($height > 1000){
        $width = (int)($width / ($height / 1000));
        $height = 1000;
    }
    $tmp = imagecreatetruecolor($width, $height);  // Создаем новое изображение с заданными размерами
    imagecopyresampled($tmp, $img, 0, 0, $x, $y, $width, $height, imagesx($img), imagesy($img));
    imagedestroy($img);
    return $tmp;
}

function rotate($img, $degree){
    $transparent = imagecolorallocatealpha($img, 0, 0, 0, 127);
    return imagerotate($img, $degree, $transparent);
}

function crop($img, $oldWidth, $oldHeight){
    $newWidth = $_SESSION['width'];
    $newHeight = $_SESSION['height'];
    $newTop = $_SESSION['top'];
    $newLeft = $_SESSION['left'];

    //Создаём изображение с новыми размерами
    $output = imagecreatetruecolor($newWidth, $newHeight);

    imagecopyresized($output, $img, 0, 0, $newLeft, $newTop, $newWidth, $newHeight, $newWidth, $newHeight);
    
    return $output;
}
?>