<?php
$img = imagecreatefrompng('donut.png');  // Загружаем изображение
$width = 200;  // Ширина обрезанного фрагмента
$height = 150;  // Высота обрезанного фрагмента


$tmp = imagecreatetruecolor($width, $height);  // Создаем новое изображение
imagecopy($tmp, $img, 0, 0, 50, 50, $width, $height);  // Обрезаем изображение (начиная с координат 50, 50)


header('Content-Type: image/png');
imagepng($tmp);  // Выводим обрезанное изображение
imagedestroy($tmp);  // Освобождаем ресурсы
imagedestroy($img);  // Освобождаем ресурсы
exit();
?>
