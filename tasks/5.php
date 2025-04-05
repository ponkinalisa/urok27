<?php
$width = 500;
$height = 500;
$img = imagecreatetruecolor($width, $height);  // Создаем изображение

for ($i = 0; $i < $width; $i++){
    $new   = imagecreatetruecolor($width, $height - $i);
    $color = imagecolorallocate($new, 255 - floor(round(255 / 500, $precision = 3) * $i), 0, floor(round(255 / 500, $precision = 3) * $i));
    imagefill($new, 0, 0, $color);
    imagecopy($img, $new, 0, $i, 0, 0, $width, $height);
}


header('Content-Type: image/png');
imagepng($img);  // Выводим изображение
imagedestroy($img);  // Освобождаем ресурсы
exit();
?>
