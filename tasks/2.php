<?php
$file_png = __DIR__ . '/../donut.png';  // Путь к PNG изображению

// Проверка существования файлов
if (!file_exists($file_png)) {
    die("файл не найден.");
}

// Получаем информацию о PNG изображении
$png_info = getimagesize($file_png);
$width = $png_info[0];
$height = $png_info[1];

// Загружаем PNG изображение
$png = imageCreateFromPng($file_png);


// Применяем фильтры:

// 1. Инвертируем цвета
imagefilter($png, IMG_FILTER_NEGATE);

// 2. Применяем черно-белый фильтр
imagefilter($png, IMG_FILTER_GRAYSCALE);

// Отображаем результат
header('Content-Type: image/png');
imagepng($png);  // Выводим изображение в браузер

// Освобождаем память
imagedestroy($png);
imagedestroy($bg);
imagedestroy($png);
?>
