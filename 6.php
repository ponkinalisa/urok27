<?php
$width = 400;
$height = 300;
$img = imagecreatetruecolor($width, $height);  // Создаем изображение


// Заливка фона белым цветом
$white = imagecolorallocate($img, 255, 255, 255);
imagefill($img, 0, 0, $white);  // Заливаем все изображение белым


// Рисуем текст
$black = imagecolorallocate($img, 255, 0, 0);
imagestring($img, 5, 150, 130, "Hello, PHP!", $black);


header('Content-Type: image/png');
imagepng($img);  // Выводим изображение
imagedestroy($img);  // Освобождаем ресурсы
exit();
?>
