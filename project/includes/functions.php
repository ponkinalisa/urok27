<?php 
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
    $width = (int)((int)$_SESSION['scale'] * 0.01 * imagesx($img));  // Новая ширина
    $height = (int)((int)$_SESSION['scale'] * 0.01 * imagesy($img));  // Новая высота
    $tmp = imagecreatetruecolor($width, $height);  // Создаем новое изображение с заданными размерами
    imagecopy($tmp, $img, 0, 0, (int)(imagesx($img) / 2 - $width / 2), (int)(imagesy($img) / 2 - $height / 2), $width, $height); 
    imagedestroy($img);
    return $tmp;
}

function rotate($img, $degree){
    $transparent = imagecolorallocatealpha($img, 0, 0, 0, 127);
    return imagerotate($img, $degree, $transparent);
}

?>