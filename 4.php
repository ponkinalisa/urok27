<?php
$file_png = __DIR__ . '/donut.png';  // Путь к PNG изображению
$file_jpg = __DIR__ . '/donut_bg.jpg';  // Путь к JPG фону

// Проверка существования файлов
if (!file_exists($file_png) || !file_exists($file_jpg)) {
    die("Один или оба файла не найдены.");
}

// Получаем информацию о PNG изображении
$png_info = getimagesize($file_png);
$width = $png_info[0];
$height = $png_info[1];

// Получаем информацию о JPG фоне
$jpg_info = getimagesize($file_jpg);

// Загружаем JPG изображение (фон)
$bg = imageCreateFromJpeg($file_jpg);

// Загружаем PNG изображение
$png = imageCreateFromPng($file_png);

// Создаем новое изображение с размерами фона (JPG)
$tmp = imageCreateTrueColor($jpg_info[0], $jpg_info[1]);

// Настроим прозрачность для PNG (если необходимо)
if ($png_info[2] == IMAGETYPE_PNG) {
    imagealphablending($tmp, false); // Отключаем смешивание для прозрачности
    imagesavealpha($tmp, true); // Сохраняем альфа-канал
    $transparent = imagecolorallocatealpha($tmp, 0, 0, 0, 127); // Создаем прозрачный цвет
    imagefill($tmp, 0, 0, $transparent); // Заполняем холст прозрачностью
}

// Копируем JPG фон на новое изображение
imagecopy($tmp, $bg, 0, 0, 0, 0, $jpg_info[0], $jpg_info[1]);

// Копируем PNG изображение поверх фона
$png_x = ($jpg_info[0] - $width) / 2;  // Центрируем PNG по ширине
$png_y = ($jpg_info[1] - $height) / 2; // Центрируем PNG по высоте
imagecopy($tmp, $png, $png_x, $png_y, 0, 0, $width, $height);

// Применяем фильтры:

// 1. Инвертируем цвета
imagefilter($tmp, IMG_FILTER_NEGATE);

// 2. Применяем черно-белый фильтр
imagefilter($tmp, IMG_FILTER_GRAYSCALE);

// 3. Добавляем размытие
imagefilter($tmp, IMG_FILTER_GAUSSIAN_BLUR);

// 4. Можно добавить яркость или контраст (от -255 до 255)
imagefilter($tmp, IMG_FILTER_BRIGHTNESS, 0); // Яркость
imagefilter($tmp, IMG_FILTER_CONTRAST, -10);  // Контраст

// Отображаем результат
header('Content-Type: image/png');
imagepng($tmp);  // Выводим изображение в браузер

// Освобождаем память
imagedestroy($tmp);
imagedestroy($bg);
imagedestroy($png);
?>
