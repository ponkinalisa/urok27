<?php
$img = imagecreatefrompng('donut.png');  // Загружаем изображение
$width = 200;  // Новая ширина
$height = 350;  // Новая высота


$tmp = imagecreatetruecolor($width, $height);  // Создаем новое изображение с заданными размерами
imagecopyresampled($tmp, $img, 0, 0, 0, 0, $width, $height, imagesx($img), imagesy($img));  // Масштабируем изображение
header('Content-Type: image/png');
imagepng($tmp);  // Выводим изображение
imagedestroy($tmp);  // Освобождаем ресурсы
imagedestroy($img);  // Освобождаем ресурсы
exit();
?>
