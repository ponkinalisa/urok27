<?php
$img = imagecreatefrompng('../donut.png');  // Загружаем изображение PNG
$transparent = imagecolorallocatealpha($img, 0, 0, 0, 127);  // Устанавливаем прозрачность
$img = imagerotate($img, -90, $transparent);  // Поворот против часовой стрелки на 45°
header('Content-Type: image/jpeg');
imagepng($img);  // Выводим изображение
imagedestroy($img);  // Освобождаем ресурсы
exit();
?>