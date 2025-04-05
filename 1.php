<?php
$file_jpg = __DIR__ . '/donut.jpg';  // Путь к изображению
// Получаем информацию о изображении
$info = getimagesize($file_jpg);
$type = $info[2];  // Тип изображения (1 - GIF, 2 - JPEG, 3 - PNG)
// Устанавливаем правильный MIME-тип в зависимости от типа изображения
switch ($type) {
    case IMAGETYPE_GIF:
        header('Content-Type: image/gif');
        $img = imagecreatefromgif($file_jpg);  // Загружаем изображение как GIF
        imagegif($img);  // Выводим изображение
        break;
    case IMAGETYPE_JPEG:
        header('Content-Type: image/jpeg');
        $img = imagecreatefromjpeg($file_jpg);  // Загружаем изображение как JPEG
        imagejpeg($img, null, 100);  // Выводим изображение с качеством 100
        break;
    case IMAGETYPE_PNG:
        header('Content-Type: image/png');
        $img = imagecreatefrompng($file_jpg);  // Загружаем изображение как PNG
        imagepng($img);  // Выводим изображение
        break;
    default:
        die('Неподдерживаемый формат изображения.');
}
// Освобождаем ресурсы
imagedestroy($img);
exit();  // Завершаем выполнение скрипта
?>
