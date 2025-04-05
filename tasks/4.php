<?php
$img = imagecreatefrompng('../donut.png');  // Загружаем изображение
$width = 200;  // Новая ширина
$height = 200;  // Новая высота


$tmp = imagecreatetruecolor($width, $height);  // Создаем новое изображение с заданными размерами
imagecopy($tmp, $img, 0, 0, 100, 100, $width, $height);
header('Content-Type: image/png');
imagepng($tmp);  // Выводим изображение
imagedestroy($tmp);  // Освобождаем ресурсы
imagedestroy($img);  // Освобождаем ресурсы
exit();
?>