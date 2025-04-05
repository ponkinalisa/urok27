<?php
$file_png = __DIR__ . '/../donut.png';
// Получаем информацию о PNG изображении
$png_info = getimagesize($file_png);
$width = floor($png_info[0] * 0.5);
$height = floor($png_info[1] * 0.5);

$img = imagecreatefrompng('../donut.png');  // Загружаем изображение
$tmp = imagecreatetruecolor($width, $height);  // Создаем новое изображение с заданными размерами
imagecopyresampled($tmp, $img, 0, 0, 0, 0, $width, $height, imagesx($img), imagesy($img));  // Масштабируем изображение
header('Content-Type: image/png');
imagepng($tmp);  // Выводим изображение
imagedestroy($tmp);  // Освобождаем ресурсы
imagedestroy($img);  // Освобождаем ресурсы
exit();
?>
